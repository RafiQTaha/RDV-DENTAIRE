<?php

namespace App\Controller\Admin;

use DateTime;
use Mpdf\Mpdf;
use App\Entity\Act;
use App\Entity\User;
use App\Entity\Rendezvous;
use App\Entity\TAdmission;
use App\Entity\TInscription;
use Doctrine\Persistence\ManagerRegistry;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/rendez-vous/listing')]
class RdvController extends AbstractController
{
    private $em;
    public function __construct(ManagerRegistry $doctrine)
    {
        $this->em = $doctrine->getManager();
    }

    #[Route('/', name: 'app_admin_rdv_listing')]
    public function index(): Response
    {
        return $this->render('admin/rdv/index.html.twig');
    }

    #[Route('/list', name: 'app_admin_rdv_listing_list', options: ['expose' => true])]
    public function app_admin_rdv_listing_list(Request $request): Response
    {
        $draw = $request->query->get('draw');
        $start = $request->query->get('start') ?? 0;
        $length = $request->query->get('length') ?? 10;
        $search = $request->query->all('search')["value"];
        $orderDir = null;
        $orderDir = null;
        if (!empty($request->query->all('order'))) {
            $orderColumnIndex = $request->query->all('order')[0]['column'];
            $orderColumn = $request->query->all("columns")[$orderColumnIndex]['name'];
            $orderDir = $request->query->all('order')[0]['dir'] ?? 'asc';
        }

        $queryBuilder = $this->em->createQueryBuilder()
            ->select('r.id ,r.Code, r.nom ,r.prenom , r.cin, r.date, r.created, etu.nom as nomEtu, etu.prenom as prenomEtu, adm.code as admCode, r.valider')
            ->from(Rendezvous::class, 'r')
            ->leftJoin('r.Actes', 'a')
            ->innerJoin('r.inscription', 'ins')
            ->innerJoin('ins.admission', 'adm')
            ->innerJoin('adm.preinscription', 'pre')
            ->innerJoin('pre.etudiant', 'etu')
            ->where('r.Annuler = 0')
            ->groupBy('r.id');
        if (!empty($search)) {
            $queryBuilder->andWhere('(r.Code LIKE :search OR r.nom LIKE :search OR r.prenom LIKE :search OR r.cin LIKE :search OR r.date LIKE :search OR r.created LIKE :search OR a.designation LIKE :search OR etu.nom LIKE :search OR etu.prenom LIKE :search OR adm.code LIKE :search OR r.statut LIKE :search)')
                ->setParameter('search', "%$search%");
        }

        $dateFilter = $request->query->get('filterDate');
        if ($dateFilter != "all") {
            $date = (new \DateTime($dateFilter))->format('Y-m-d');
            // dd($date);
            $queryBuilder->andWhere('r.date LIKE :date')
                ->setParameter('date', $date . '%');
        }

        if (!empty($orderColumn)) {
            $queryBuilder->orderBy("$orderColumn", $orderDir);
        }

        $filteredRecords = count($queryBuilder->getQuery()->getResult());

        // Paginate results
        $queryBuilder->setFirstResult($start)
            ->setMaxResults($length);

        $results = $queryBuilder->getQuery()->getResult();
        // dd($results);
        foreach ($results as &$res) {
            $rendezvous = $this->em->getRepository(Rendezvous::class)->find($res["id"]);
            $acts = $rendezvous->getActes();
            $act = [];

            foreach ($acts as $act) {
                $designation = $act->getDesignation();
                if (strlen($designation) > 20) {
                    $designation = substr($designation, 0, 20);
                }
                $actNames[] = $designation;
            }

            if (count($actNames) > 1) {
                $res['acts'] = implode(' - ', $actNames);
            } else {
                $res['acts'] = reset($actNames);
            }
        }
        // dd($results);
        $totalRecords = $this->em->createQueryBuilder()
            ->select('COUNT(r.id)')
            ->from(Rendezvous::class, 'r')
            ->where('r.Annuler = 0')
            // ->innerJoin('u.client', 'c')
            ->getQuery()
            ->getSingleScalarResult();

        return new JsonResponse([
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $results,
        ]);
    }

    #[Route('/details/{rendezvous}', name: 'app_admin_rdv_listing_details', options: ['expose' => true])]
    public function app_admin_rdv_listing_details(Rendezvous $rendezvous): Response
    {
        $detailsRdv = $this->render("admin/rdv/pages/detailsReclamation.html.twig", [
            'rendezvous' => $rendezvous
        ])->getContent();
        return new JsonResponse(['detailsRdv' => $detailsRdv], 200);
    }

    #[Route('/export_excel', name: 'app_admin_rdv_listing_export_excel', options: ['expose' => true])]
    public function app_admin_rdv_listing_export_excel(Request $request)
    {
        $dateDebut = $request->query->get('dateDebut') ? new \DateTime($request->query->get('dateDebut')) : null;
        $dateFin = $request->query->get('dateFin') ? new \DateTime($request->query->get('dateFin')) : null;

        $rendezvous = $this->em->getRepository(Rendezvous::class)->findRendezVousBetweenDates($dateDebut, $dateFin);
        // dd($rendezvous);
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'ORD');
        $sheet->setCellValue('B1', 'CODE ADMISSION');
        $sheet->setCellValue('C1', 'NOM ETUDIANT');
        $sheet->setCellValue('D1', 'PRENOM ETUDIANT');
        $sheet->setCellValue('E1', 'CODE RENDEZ-VOUS');
        $sheet->setCellValue('F1', 'NOM PATIENT');
        $sheet->setCellValue('G1', 'PRENOM PATIENT');
        $sheet->setCellValue('H1', 'CIN PATIENT');
        $sheet->setCellValue('I1', 'ACTES');
        $sheet->setCellValue('J1', 'DATE RENDEZ-VOUS');
        $sheet->setCellValue('K1', 'DATE CREATION');
        $sheet->setCellValue('L1', 'STATUT');
        $i = 2;
        $j = 1;
        foreach ($rendezvous as $rdv) {
            foreach ($rdv->getActes() as $act) {
                $sheet->setCellValue('A' . $i, $j);
                $sheet->setCellValue('B' . $i, $rdv->getInscription()->getAdmission()->getCode());
                $sheet->setCellValue('C' . $i, $rdv->getInscription()->getAdmission()->getPreinscription()->getEtudiant()->getNom());
                $sheet->setCellValue('D' . $i, $rdv->getInscription()->getAdmission()->getPreinscription()->getEtudiant()->getPrenom());
                $sheet->setCellValue('E' . $i, $rdv->getCode());
                $sheet->setCellValue('F' . $i, $rdv->getNom());
                $sheet->setCellValue('G' . $i, $rdv->getPrenom());
                $sheet->setCellValue('H' . $i, $rdv->getCin());
                $sheet->setCellValue('I' . $i, $act->getDesignation());
                $sheet->setCellValue('J' . $i, $rdv->getDate()->format('Y-m-d h:m:s'));
                $sheet->setCellValue('K' . $i, $rdv->getCreated()->format('Y-m-d h:m:s'));
                $sheet->setCellValue('L' . $i, $rdv->getStatut());
                $i++;
            }
            $j++;
        }

        // die('die');
        $writer = new Xlsx($spreadsheet);
        $fileName = "Extraction Rendez_vous.xlsx";
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($temp_file);
        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
    }

    #[Route('/export_pdf', name: 'app_admin_rdv_listing_export_pdf', options: ['expose' => true])]
    public function app_admin_rdv_listing_export_pdf(Request $request)
    {
        ini_set('memory_limit', '-1');

        $dateDebut = $request->query->get('dateDebut') ? new \DateTime($request->query->get('dateDebut')) : null;
        $dateFin = $request->query->get('dateFin') ? new \DateTime($request->query->get('dateFin')) : null;

        $inscriptions = $this->em->getRepository(TInscription::class)->getInscriptionWithRdvByDates($dateDebut, $dateFin);

        $html = $this->render("admin/rdv/pdf/export_pdf.html.twig", [
            'inscriptions' => $inscriptions
        ])->getContent();
        // dd($html);
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'margin_left' => 5,
            'margin_right' => 5,
        ]);
        $mpdf->showImageErrors = true;

        $mpdf->SetHTMLHeader(
            $this->render("admin/rdv/pdf/header.html.twig")->getContent()
        );
        // $mpdf->SetHTMLFooter(
        //     $this->render("admin/rdv/pdf/footer.html.twig")->getContent()
        // );

        $mpdf->WriteHTML($html);
        $mpdf->SetTitle('List Rendez-vous');
        $mpdf->Output("List Rendez_vous.pdf", "I");
    }
}
