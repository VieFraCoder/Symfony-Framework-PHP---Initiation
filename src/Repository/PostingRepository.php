<?php

namespace App\Repository;

use App\Entity\Posting;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Posting|null find($id, $lockMode = null, $lockVersion = null)
 * @method Posting|null findOneBy(array $criteria, array $orderBy = null)
 * @method Posting[]    findAll()
 * @method Posting[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Posting::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Posting $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Posting $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
    * @return Posting[] Returns an array of Posting objects
    */
    public function findAllDesc()
    {
        return $this->createQueryBuilder('p')
            // ->andWhere('p.exampleField = :val')
            // ->setParameter('val', $value)
            ->orderBy('p.id', 'DESC')
            // ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

        /**
    * @return Posting[] Returns an array of User objects
    */
  
    public function countPosting()
    {
        return $this->createQueryBuilder('u')
            // ->andWhere('u.exampleField = :val')
            // ->setParameter('val', $value)
            // ->orderBy('u.id', 'ASC')
            ->select('count(u.id)')
            // ->setMaxResults(10)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }


    /*
    public function findOneBySomeField($value): ?Posting
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
