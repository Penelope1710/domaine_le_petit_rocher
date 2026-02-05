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
}
