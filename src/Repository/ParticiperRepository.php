<?php

namespace App\Repository;

use App\Entity\Participer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Participer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Participer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Participer[]    findAll()
 * @method Participer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParticiperRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Participer::class);
    }

    /**
     * @return int|mixed|string
     */
    public function findList()
    {
        return $this->createQueryBuilder('p')
            ->addSelect('t')
            ->addSelect('i')
            ->leftJoin('p.ticket', 't')
            ->leftJoin('p.participant', 'i')
            ->getQuery()->getResult()
            ;
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneByTicket($code)
    {
        return $this->createQueryBuilder('p')
            ->addSelect('t')
            ->addSelect('i')
            ->leftJoin('p.ticket', 't')
            ->leftJoin('p.participant', 'i')
            ->where('t.code = :code')
            ->setParameter('code', $code)
            ->getQuery()->getOneOrNullResult()
            ;
    }

    // /**
    //  * @return Participer[] Returns an array of Participer objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Participer
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
