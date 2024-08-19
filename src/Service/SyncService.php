<?php

namespace App\Service;

use App\Entity\PUnite;
use App\Entity\Uarticle;
use App\Entity\TrTransaction;
use App\Entity\UATCommandefrscab;
use App\Entity\UATCommandefrsdet;
use App\Entity\UaTFacturefrscab;
use App\Entity\UaTFacturefrsdet;
use App\Entity\UaTLivraisonfrscab;
use App\Entity\UaTLivraisonfrsdet;
use App\Entity\UGeneralOperation;
use App\Entity\UPPartenaire;
use App\Entity\User;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Doctrine\Persistence\Mapping\MappingException;
use DateTime;
use DateTimeImmutable;

class SyncService
{
    private $httpClient;
    private $entityManager;
    private $logger;

    public function __construct(HttpClientInterface $httpClient, EntityManagerInterface $entityManager, LoggerInterface $logger)
    {
        $this->httpClient = $httpClient;
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    public function synchronize($tableName, $entity, $lastId)
    {
        $query = "SELECT * FROM " . $tableName . " WHERE id > " . $lastId . " limit 50 ;";

        $response = $this->httpClient->request(
            'GET',
            'https://127.0.0.1:8000/api/sync',
            [
                'headers' => [
                    'Authorization' => 'Bearer your_api_key_here'
                ],
                'query' => [
                    "query" => $query
                ],
                'verify_peer' => false,
            ]
        );

        $data = $response->toArray();

        $lastInsertedId = $this->insertData($tableName, $entity, $data);

        $count = count($data);

        return [
            'countDone' => $count,
            'lastInsertedId' => $lastInsertedId,
        ];
        // try {

        //     $this->insertData($data);
        // } catch (\Exception $e) {
        //     $this->logger->error('Data synchronization failed: ' . $e->getMessage());
        // }
    }

    public function insertData(string $tableName, string $entityClass, array $data): string
    {
        $className = "App\\Entity\\" . $entityClass;
        if (empty($data)) {
            $lastInsertedId = $this->entityManager->getRepository($className)->findOneBy([], ['id' => 'DESC'])->getId();
            return $lastInsertedId;
        }

        $connection = $this->entityManager->getConnection();
        $columns = $connection->getSchemaManager()->listTableColumns($tableName);
        $validFields = array_map(fn ($column) => $column->getName(), $columns);

        $connection->executeStatement('SET foreign_key_checks = 0');

        $firstItem = reset($data);
        $fields = array_keys($firstItem);

        // Filter fields to only include those that exist in the table
        $fields = array_filter($fields, fn ($field) => in_array($field, $validFields));

        if (empty($fields)) {
            $connection->executeStatement('SET foreign_key_checks = 1');
            return "No valid fields found for insertion.";
        }

        $placeholders = array_map(
            function ($row) use ($fields) {
                return '(' . implode(', ', array_map(fn ($field) => ':' . $field . $row, $fields)) . ')';
            },
            array_keys($data)
        );

        $query = 'INSERT INTO ' . $tableName . ' (' . implode(', ', $fields) . ') VALUES ' . implode(', ', $placeholders);

        $stmt = $connection->prepare($query);

        // Bind values for all items in batch
        foreach ($data as $rowIndex => $item) {
            foreach ($fields as $field) {
                if (array_key_exists($field, $item)) {
                    $stmt->bindValue(':' . $field . $rowIndex, $item[$field]);
                } else {
                    continue;
                }
            }
        }

        // Execute the batch insert query
        $stmt->execute();

        $connection->executeStatement('SET foreign_key_checks = 1');
        $lastInsertedId = $this->entityManager->getRepository($className)->findOneBy([], ['id' => 'DESC'])->getId();
        return $lastInsertedId;
    }

    private function insertDataPUnite(array $data)
    {
        foreach ($data as $item) {
            $PUnite = $this->entityManager->getRepository(PUnite::class)->find($item['id']);

            if (!$PUnite) {
                $newPunite = new PUnite();
                $item['id'] && $newPunite->setId($item['id']);
                $item['code'] && $newPunite->setCode($item['code']);
                $item['designation'] && $newPunite->setDesignation($item['designation']);
                $item['abreviation'] && $newPunite->setAbreviation($item['abreviation']);
                $item['type'] && $newPunite->setType($item['type']);
                $item['typeDefault'] && $newPunite->setTypeDefault($item['typeDefault']);
                $item['active'] && $newPunite->setActive($item['active']);
                $item['old_sys'] && $newPunite->setOldSys($item['old_sys']);

                $this->entityManager->persist($newPunite);
            }
        }

        $this->entityManager->flush();
        return "done";
    }

    private function insertDataTrTransaction(array $data)
    {

        foreach ($data as $item) {
        }

        $this->entityManager->flush();
        return "done";
    }

    private function insertDataUgeneralOperation(array $data)
    {

        foreach ($data as $item) {
            // Check if the data already exists in the upartner database
            // $existingEntity = $this->entityManager->getRepository(YourEntity::class)->find($item['id']);

            // if (!$existingEntity) {
            //     $newEntity = new YourEntity();
            //     $newEntity->setId($item['id']);
            //     $newEntity->setName($item['name']);
            //     // Set other fields as needed

            //     $this->entityManager->persist($newEntity);
            // }
        }

        $this->entityManager->flush();
    }

    private function insertDataUpPartenaire(array $data)
    {

        foreach ($data as $item) {
            // Check if the data already exists in the upartner database
            // $existingEntity = $this->entityManager->getRepository(YourEntity::class)->find($item['id']);

            // if (!$existingEntity) {
            //     $newEntity = new YourEntity();
            //     $newEntity->setId($item['id']);
            //     $newEntity->setName($item['name']);
            //     // Set other fields as needed

            //     $this->entityManager->persist($newEntity);
            // }
        }

        $this->entityManager->flush();
    }

    private function insertDataCommandeFrsCab(array $data)
    {

        foreach ($data as $item) {
            // Check if the data already exists in the upartner database
            // $existingEntity = $this->entityManager->getRepository(YourEntity::class)->find($item['id']);

            // if (!$existingEntity) {
            //     $newEntity = new YourEntity();
            //     $newEntity->setId($item['id']);
            //     $newEntity->setName($item['name']);
            //     // Set other fields as needed

            //     $this->entityManager->persist($newEntity);
            // }
        }

        $this->entityManager->flush();
    }

    private function insertDataCommandeFrsDet(array $data)
    {

        foreach ($data as $item) {
            // Check if the data already exists in the upartner database
            // $existingEntity = $this->entityManager->getRepository(YourEntity::class)->find($item['id']);

            // if (!$existingEntity) {
            //     $newEntity = new YourEntity();
            //     $newEntity->setId($item['id']);
            //     $newEntity->setName($item['name']);
            //     // Set other fields as needed

            //     $this->entityManager->persist($newEntity);
            // }
        }

        $this->entityManager->flush();
    }

    private function insertDataFactureFrsCab(array $data)
    {

        foreach ($data as $item) {
            // Check if the data already exists in the upartner database
            // $existingEntity = $this->entityManager->getRepository(YourEntity::class)->find($item['id']);

            // if (!$existingEntity) {
            //     $newEntity = new YourEntity();
            //     $newEntity->setId($item['id']);
            //     $newEntity->setName($item['name']);
            //     // Set other fields as needed

            //     $this->entityManager->persist($newEntity);
            // }
        }

        $this->entityManager->flush();
    }

    private function insertDataFactureFrsDet(array $data)
    {

        foreach ($data as $item) {
            // Check if the data already exists in the upartner database
            // $existingEntity = $this->entityManager->getRepository(YourEntity::class)->find($item['id']);

            // if (!$existingEntity) {
            //     $newEntity = new YourEntity();
            //     $newEntity->setId($item['id']);
            //     $newEntity->setName($item['name']);
            //     // Set other fields as needed

            //     $this->entityManager->persist($newEntity);
            // }
        }

        $this->entityManager->flush();
    }

    private function insertDataLivraisonFrsCab(array $data)
    {

        foreach ($data as $item) {
            // Check if the data already exists in the upartner database
            // $existingEntity = $this->entityManager->getRepository(YourEntity::class)->find($item['id']);

            // if (!$existingEntity) {
            //     $newEntity = new YourEntity();
            //     $newEntity->setId($item['id']);
            //     $newEntity->setName($item['name']);
            //     // Set other fields as needed

            //     $this->entityManager->persist($newEntity);
            // }
        }

        $this->entityManager->flush();
    }

    private function insertDataLivraisonFrsDet(array $data)
    {

        foreach ($data as $item) {
            // Check if the data already exists in the upartner database
            // $existingEntity = $this->entityManager->getRepository(YourEntity::class)->find($item['id']);

            // if (!$existingEntity) {
            //     $newEntity = new YourEntity();
            //     $newEntity->setId($item['id']);
            //     $newEntity->setName($item['name']);
            //     // Set other fields as needed

            //     $this->entityManager->persist($newEntity);
            // }
        }

        $this->entityManager->flush();
    }

    private function insertDataUarticle(array $data)
    {
        foreach ($data as $item) {
            $UArticle = $this->entityManager->getRepository(Uarticle::class)->find($item['id']);

            if (!$UArticle) {
                $newUArticle = new Uarticle();
                $item['id'] && $newUArticle->setId($item['id']);
                $item['code'] && $newUArticle->setCode($item['code']);
                $item['rayounnage'] && $newUArticle->setRayounnage($item['rayounnage']);
                $item['partenaire'] && $newUArticle->setPartenaire($item['partenaire']);
                $item['image'] && $newUArticle->setImage($item['image']);
                $item['code_article_fournisseur'] && $newUArticle->setCodeArticleFournisseur($item['code_article_fournisseur']);
                $item['titre'] && $newUArticle->setTitre($item['titre']);
                $item['etat_vente'] && $newUArticle->setEtatVente($item['etat_vente']);
                $item['etat_achat'] && $newUArticle->setEtatAchat($item['etat_achat']);
                $item['description'] && $newUArticle->setDescription($item['description']);
                $item['url'] && $newUArticle->setUrl($item['url']);
                $item['stock_base'] && $newUArticle->setStockBase($item['stock_base']);
                $item['poid'] && $newUArticle->setPoid($item['poid']);
                $item['longeur'] && $newUArticle->setLongeur($item['longeur']);
                $item['largeur'] && $newUArticle->setLargeur($item['largeur']);
                $item['hauteur'] && $newUArticle->setHauteur($item['hauteur']);
                $item['surface'] && $newUArticle->setSurface($item['surface']);
                $item['volume'] && $newUArticle->setVolume($item['volume']);
                $item['prix_vente'] && $newUArticle->setPrixVente($item['prix_vente']);
                $item['prix_vente_min'] && $newUArticle->setPrixVenteMin($item['prix_vente_min']);
                $item['prix_vente_max'] && $newUArticle->setPrixVenteMax($item['prix_vente_max']);
                $item['prix_vente_moyenne'] && $newUArticle->setPrixVenteMoyenne($item['prix_vente_moyenne']);
                $item['prix_achat'] && $newUArticle->setPrixAchat($item['prix_achat']);
                $item['prix_achat_min'] && $newUArticle->setPrixAchatMin($item['prix_achat_min']);
                $item['prix_achat_max'] && $newUArticle->setPrixAchatMax($item['prix_achat_max']);
                $item['prix_achat_moyenne'] && $newUArticle->setPrixAchatMoyenne($item['prix_achat_moyenne']);
                $item['code_comptable_vente'] && $newUArticle->setCodeComptableVente($item['code_comptable_vente']);
                $item['code_comptable_vente_export'] && $newUArticle->setCodeComptableVenteExport($item['code_comptable_vente_export']);
                $item['code_comptable_achat'] && $newUArticle->setCodeComptableAchat($item['code_comptable_achat']);
                $item['active'] && $newUArticle->setActive($item['active']);
                $item['p_unite_default_id'] && $newUArticle->setDefaultUnite($this->entityManager->getRepository(PUnite::class)->find($item['p_unite_default_id']));
                $item['autre_information'] && $newUArticle->setAutreInformation($item['autre_information']);
                $item['description_detail'] && $newUArticle->setDescriptionDetail($item['description_detail']);
                $item['created'] && $newUArticle->setCreated(new \DateTime($item['created']));
                $item['updated'] && $newUArticle->setUpdated(new \DateTime($item['updated']));
                $item['gerer_en_stock'] && $newUArticle->setGererEnStock($item['gerer_en_stock']);
                $item['verification_stock'] && $newUArticle->setVerificationStock($item['verification_stock']);
                $item['code_barre'] && $newUArticle->setCodeBarre($item['code_barre']);
                $item['tva'] && $newUArticle->setTva($item['tva']);
                $item['code_ean13'] && $newUArticle->setCodeEan13($item['code_ean13']);
                $item['dosage'] && $newUArticle->setDosage($item['dosage']);
                $item['dci'] && $newUArticle->setDci($item['dci']);
                $item['niveau5'] && $newUArticle->setNiveau5($item['niveau5']);
                $item['taille'] && $newUArticle->setTaille($item['taille']);
                $item['conditionnement'] && $newUArticle->setConditionnement($item['conditionnement']);
                $item['marque'] && $newUArticle->setMarque($item['marque']);
                $item['matiere'] && $newUArticle->setMatiere($item['matiere']);
                $item['niveau6'] && $newUArticle->setNiveau6($item['niveau6']);
                $item['niveau7'] && $newUArticle->setNiveau7($item['niveau7']);
                $item['niveau8'] && $newUArticle->setNiveau8($item['niveau8']);
                $item['M_A'] && $newUArticle->setMA($item['M_A']);
                $item['A_V'] && $newUArticle->setAV($item['A_V']);
                $item['A_I'] && $newUArticle->setAI($item['A_I']);
                $item['S_NS'] && $newUArticle->setSNS($item['S_NS']);
                $item['REF_INTERNE'] && $newUArticle->setRefintern($item['REF_INTERNE']);
                $item['old_sys'] && $newUArticle->setOldSys($item['old_sys']);
                $item['remise'] && $newUArticle->setRemise($item['remise']);
                $item['prix_reference'] && $newUArticle->setPrixReference($item['prix_reference']);

                $this->entityManager->persist($newUArticle);
            }
        }
        $this->entityManager->flush();
        $lastInsertedId = $this->entityManager->getRepository(Uarticle::class)->findOneBy([], ['id' => 'DESC'])->getId();
        return $lastInsertedId;
    }
}
