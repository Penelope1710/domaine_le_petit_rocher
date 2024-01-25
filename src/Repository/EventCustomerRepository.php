<?php

namespace App\Repository;

use App\Entity\EventCustomer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EventCustomer>
 *
 * @method EventCustomer|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventCustomer|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventCustomer[]    findAll()
 * @method EventCustomer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventCustomerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventCustomer::class);
    }

//    /**
//     * @return EventCustomer[] Returns an array of EventCustomer objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?EventCustomer
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
