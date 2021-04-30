<?php

namespace App\Repository;

use App\Entity\Invitant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Invitant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Invitant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Invitant[]    findAll()
 * @method Invitant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InvitantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Invitant::class);
    }

    /**
     * @return int|mixed|string
     */
    public function findAllWithUser()
    {
        return $this->createQueryBuilder('i')
            ->addSelect('u')
            ->leftJoin('i.user', 'u')
            ->orderBy('i.nom', 'ASC')
            ->addOrderBy('i.prenoms', 'ASC')
            ->getQuery()->getResult()
            ;
    }

    // /**
    //  * @return Invitant[] Returns an array of Invitant objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Invitant
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
