<?php

namespace App\Controller\Etudiant;

use DateTime;
use App\Entity\Act;
use App\Entity\User;
use App\Entity\Rendezvous;
use App\Entity\TAdmission;
use App\Entity\TInscription;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/etudiant/rendez-vous/listing')]
class RdvController extends AbstractController
{
    private $em;
    public function __construct(ManagerRegistry $doctrine)
    {
        $this->em = $doctrine->getManager();
    }
    #[Route('/', name: 'app_etudiant_rdv_listing')]
    public function index(): Response
    {
        $admission = $this->em->getRepository(TAdmission::class)->findOneBy(['code' => $this->getUser()->getUsername()]);
        $inscription = $this->em->getRepository(TInscription::class)->findOneBy(['admission' => $admission, "statut" => 13], ["id" => "desc"]);
        $actes = $this->em->getRepository(Act::class)->findBy(["promotion" => $inscription->getPromotion()]);
        return $this->render('etudiant/rdv/index.html.twig', [
            'actes' => $actes,
        ]);
    }
    #[Route('/list', name: 'app_etudiant_rdv_listing_list', options: ['expose' => true])]
    public function app_admin_rdv_listing_list(Request $request): Response
    {
        $admission = $this->em->getRepository(TAdmission::class)->findOneBy(['code' => $this->getUser()->getUsername()]);
        $inscription = $this->em->getRepository(TInscription::class)->findOneBy(['admission' => $admission, "statut" => 13], ["id" => "desc"]);

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
            ->select('r.id ,r.Code, r.nom ,r.prenom , r.cin, r.date, r.created, r.valider')
            ->from(Rendezvous::class, 'r')
            ->leftJoin('r.Actes', 'a')
            ->where('r.inscription = :inscription')
            ->andWhere('r.Annuler = 0')
            ->setParameter('inscription', $inscription)
            ->groupBy('r.id');
        if (!empty($search)) {
            $queryBuilder->andWhere('(r.Code LIKE :search OR r.nom LIKE :search OR r.prenom LIKE :search OR r.cin LIKE :search OR r.date LIKE :search OR r.created LIKE :search OR r.statut LIKE :search)')
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
            ->where('r.inscription = :inscription')
            ->andWhere('r.Annuler = 0')
            ->setParameter('inscription', $inscription)
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

    #[Route('/new', name: 'app_etudiant_rdv_listing_new', options: ['expose' => true])]
    public function app_etudiant_rdv_listing_new(Request $request): Response
    {
        $admission = $this->em->getRepository(TAdmission::class)->findOneBy(['code' => $this->getUser()->getUsername()]);
        $inscription = $this->em->getRepository(TInscription::class)->findOneBy(['admission' => $admission, "statut" => 13], ["id" => "desc"]);

        $nom = $request->request->get('nom');
        $prenom = $request->request->get('prenom');
        $date = \DateTime::createFromFormat('Y-m-d\TH:i', $request->request->get('date'));
        $cin = $request->request->get('cin');
        $actes_ids = json_decode($request->request->get('actes'));
        $actes = $this->em
            ->getRepository(Act::class)
            ->findBy(['id' => $actes_ids]);
        // dd($nom, $prenom, $cin, $date, $actes);

        if (!$nom || !$prenom || !$date || !$cin || !$actes) {
            return new JsonResponse("Merci de remplire tous les champs.", 500);
        }

        $rendezvous = new Rendezvous();


        $rendezvous->setNom($nom);
        $rendezvous->setPrenom($prenom);
        $rendezvous->setInscription($inscription);
        $rendezvous->setCin($cin);
        $rendezvous->setCreated(new DateTime('now'));
        $rendezvous->setDate($date);

        foreach ($actes as $acte) {
            $rendezvous->addActe($acte);
        }

        $this->em->persist($rendezvous);

        $this->em->flush();
        $rendezvous->setCode('RDV-FDA_' . str_pad($rendezvous->getId(), 8, '0', STR_PAD_LEFT));
        $this->em->flush();

        return new JsonResponse("Rendez-vous ajouté avec succès.", 200);
    }

    #[Route('/details/{rendezvous}', name: 'app_etudiant_rdv_listing_details', options: ['expose' => true])]
    public function app_etudiant_rdv_listing_details(Rendezvous $rendezvous): Response
    {
        $detailsRdv = $this->render("etudiant/rdv/pages/detailsReclamation.html.twig", [
            'rendezvous' => $rendezvous
        ])->getContent();
        return new JsonResponse(['detailsRdv' => $detailsRdv], 200);
    }

    #[Route('/annuler', name: 'app_etudiant_rdv_listing_annuler', options: ['expose' => true])]
    public function app_etudiant_rdv_listing_annuler(Request $request): Response
    {
        $rendezvous = $this->em->getRepository(Rendezvous::class)->find($request->request->get('rendezvous'));
        if ($rendezvous->isValider()) {
            return new JsonResponse("Ce rendez-vous est déja validé.", 500);
        }
        $rendezvous->setAnnuler(1);
        $rendezvous->setAnnulated(new DateTime('now'));
        $this->em->flush();
        return new JsonResponse("Rendez-vous annulé avec succès.", 200);
    }

    #[Route('/valider', name: 'app_etudiant_rdv_listing_valider', options: ['expose' => true])]
    public function app_etudiant_rdv_listing_valider(Request $request): Response
    {
        $rendezvous = $this->em->getRepository(Rendezvous::class)->find($request->request->get('rendezvous'));
        $rendezvous->setValider(1);
        $rendezvous->setValidated(new DateTime('now'));
        $rendezvous->setStatut('Validé');
        $this->em->flush();
        return new JsonResponse("Rendez-vous validé avec succès.", 200);
    }
}
