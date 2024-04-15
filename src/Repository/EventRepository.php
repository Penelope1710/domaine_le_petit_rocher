<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Event;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\SecurityBundle\Security;

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
    public function __construct(ManagerRegistry $registry, private Security $security, private PaginatorInterface $paginator)
    {
        parent::__construct($registry, Event::class);
    }

    /**
     * récupère les évènements en lien avec une recherche
     * @return Event[]
     */
    public function findSearch(SearchData $searchData, ?User $user, $page = 1)
    {
        $query = $this
            ->createQueryBuilder('e')
            ->select('c', 'e')
            ->leftJoin('e.category', 'c')
            ->orderBy('e.status', 'DESC');



        if (!empty($searchData->q))
        {
            $query
                ->andWhere('e.name LIKE :q')
                ->setParameter('q', "%{$searchData->q}%");
        }

        if (!empty($searchData->categories))
        {
            $query
                ->andWhere('c.id IN (:categories)')
                ->setParameter('categories', $searchData->categories);

        }

        if ($searchData->dateDebut !== null)
        {
            $query
                ->andWhere('e.startDate >= :dateDebut')
                ->setParameter('dateDebut', $searchData->dateDebut);
        }

        if ($searchData->dateFin !== null)
        {
            $query
                ->andWhere('e.startDate <= :dateFin')
                ->setParameter('dateFin', $searchData->dateFin);
        }

        if ($searchData->activite === 2)
        {
            $query
                ->andWhere('e.createdBy = :user')
                ->setParameter('user', $this->security->getUser());
        }

        if ($searchData->activite === 3 && $user !== null)
        {
            $query
                ->leftJoin('e.eventCustomers', 'ec')
                // ->addSelect('ec');
                ->leftJoin('ec.customer', 'cust')
                ->andWhere('cust.user = :user')
                ->setParameter('user', $this->security->getUser());
        }
        //return $query->getQuery()->getResult();

        return $this->paginator->paginate(
            $query,
            $page,
            10
        );

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
