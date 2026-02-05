<?php

namespace App\Repository;

use App\Data\SearchUserData;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;


/**
 * @extends ServiceEntityRepository<User>
 *
 * @implements PasswordUpgraderInterface<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry, private PaginatorInterface $paginator)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new Exception(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();

        dd([
            'user_id' => $user->getId(),
            'user_username' => $user->getUsername(),
            'new_hashed_password' => $newHashedPassword,
        ]);
    }

    public function findUserSearch(SearchUserData $searchUserData, $page = 1): PaginationInterface
    {
        $query = $this->createQueryBuilder('u')
            ->orderBy('u.id', 'DESC');

        if (!empty($searchUserData->q))
        {
            $query
                ->leftJoin('u.customer', 'c')
                ->Where('c.lastName LIKE :q')
                ->orWhere('c.firstName LIKE :q')
                ->orWhere('u.email LIKE :q')
                ->setParameter('q', "%{$searchUserData->q}%");
        }
        if ($searchUserData->active !== null)
        {
            $query
                ->andWhere('u.isValid = :active')
                ->setParameter('active', $searchUserData->active);
        }

        if ($searchUserData->role !== null)
        {
            $query
                ->andWhere('u.roles LIKE :role')
                ->setParameter('role', "%{$searchUserData->role}%" );
        }

        $pagination = $this->paginator->paginate(
            $query,
            $page,
            10
        );

        return $pagination;
    }

    public function userecuriepaginationQuery($page = 1)
    {
        $query = $this->createQueryBuilder('u')
            ->where('u.roles LIKE :role')
            ->setParameter('role', '%ROLE_ECURIE%')
            ->orderBy('u.id', 'ASC')
            ->getQuery()
        ;

        $pagination = $this->paginator->paginate(
            $query,
            $page,
            5
        );

        return $pagination;
    }
}
