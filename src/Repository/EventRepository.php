<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Event;
use App\Entity\EventCustomer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Event>
 *
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    /**
     * récupère les évènements en lien avec une recherche
     * @return Event[]
     */
    public function findSearch(SearchData $searchData): array
    {
        $query = $this
            ->createQueryBuilder('e')
            ->select('c', 'e')
            ->leftJoin('e.category', 'c');


        if (!empty($searchData->q))
        {
            $query = $query
                ->andWhere('e.name LIKE :q')
                ->setParameter('q', "%{$searchData->q}%");
        }
        return $query->getQuery()->getResult();
    }


//    public function findOneBySomeField($value): ?Event
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
