<?php

namespace App\Repository;

use App\Entity\Rendezvous;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Rendezvous>
 */
class RendezvousRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rendezvous::class);
    }

    //    /**
    //     * @return Rendezvous[] Returns an array of Rendezvous objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Rendezvous
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findRendezVousBetweenDates(?\DateTime $startDate, ?\DateTime $endDate)
    {
        $qb = $this->createQueryBuilder('r');

        $qb = $this->createQueryBuilder('r');

        if ($startDate && !$endDate) {
            $qb->andWhere('r.date LIKE :startDate')
                ->setParameter('startDate', $startDate->format('Y-m-d') . '%');
        } elseif (!$startDate && $endDate) {
            $qb->andWhere('r.date LIKE :endDate')
                ->setParameter('endDate', $endDate->format('Y-m-d') . '%');
        } elseif ($startDate && $endDate) {
            $endDate->modify('+1 day');
            $qb->andWhere('r.date BETWEEN :startDate AND :endDate')
                ->setParameter('startDate', $startDate->format('Y-m-d') . ' 00:00:00')
                ->setParameter('endDate', $endDate->format('Y-m-d') . ' 00:00:00');
        }

        return $qb->getQuery()->getResult();

        $qb->andWhere('r.Annuler == 0');

        return $qb->getQuery()->getResult();
    }
}
