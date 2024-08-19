<?php

namespace App\Repository;

use App\Entity\TInscription;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TInscription|null find($id, $lockMode = null, $lockVersion = null)
 * @method TInscription|null findOneBy(array $criteria, array $orderBy = null)
 * @method TInscription[]    findAll()
 * @method TInscription[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TInscriptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TInscription::class);
        $this->em = $registry->getManager();
    }
    public function getEtudiantInscrits()
    {
        return $this->createQueryBuilder('t')
            ->innerJoin("t.statut", "statut")
            ->innerJoin("t.annee", "annee")
            ->Where("statut.id = 13")
            ->andWhere("annee.validation_academique = 'non'")
            // ->andWhere("t.id >= 20000")
            ->groupBy("t.admission")
            ->getQuery()
            ->getResult()
        ;
        // dd($return);
    }
    

    public function getNiveauByPromoAnnee($promotion,$annee)
    {
        return $this->createQueryBuilder('inscription')
            ->Where("inscription.promotion = :promotion")
            ->AndWhere("inscription.annee = :annee")
            ->AndWhere("inscription.groupe is not null")
            ->setParameter('promotion', $promotion)
            ->setParameter('annee', $annee)
            ->groupBy('inscription.groupe')
            ->orderBy('inscription.groupe', 'ASC')
            ->getQuery()
            ->getResult()
        ;

    } 

}
