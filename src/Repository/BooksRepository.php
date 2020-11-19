<?php

namespace App\Repository;

use App\Entity\Books;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Books|null find($id, $lockMode = null, $lockVersion = null)
 * @method Books|null findOneBy(array $criteria, array $orderBy = null)
 * @method Books[]    findAll()
 * @method Books[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BooksRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Books::class);
    }

    /**
     * @return int|mixed|string|null
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function countAll()
    {
        return $this->createQueryBuilder('b')
            ->select('count(b.id)')
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    public function findWithLimit(int $limit, int $offset, string $orderBy = 'ASC')
    {
        return $this->createQueryBuilder('b')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->orderBy('b.created', $orderBy)
            ->getQuery()
            ->getResult()
            ;
    }

    public function bookSearch(string $value, int $maxResults = 10)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.title LIKE :val')
            ->orWhere('b.isbn LIKE :val')
            ->setParameter('val', '%'.$value.'%')
            ->orderBy('b.created', 'ASC')
            ->setMaxResults($maxResults)
            ->getQuery()
            ->getResult()
            ;
    }
}
