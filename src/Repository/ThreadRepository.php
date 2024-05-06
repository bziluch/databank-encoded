<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Thread;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @extends ServiceEntityRepository<Thread>
 *
 * @method Thread|null find($id, $lockMode = null, $lockVersion = null)
 * @method Thread|null findOneBy(array $criteria, array $orderBy = null)
 * @method Thread[]    findAll()
 * @method Thread[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ThreadRepository extends ServiceEntityRepository
{
    public function __construct(
        private readonly Security $security,
        ManagerRegistry $registry
    )
    {
        parent::__construct($registry, Thread::class);
    }

    public function getCategoryThreads(?Category $category)
    {
        $qb = $this->createQueryBuilder('t');
        if ($category) {
            $qb
                ->andWhere('t.category = :category')
                ->setParameter('category', $category);
        } else {
            $qb
                ->andWhere('t.category IS NULL');
        }
        if (!$this->security->getUser()) {
            $qb->andWhere('t.secure = 0 OR t.secure IS NULL');
        }
        return $qb->getQuery()->getResult();
    }

//    /**
//     * @return Thread[] Returns an array of Thread objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Thread
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
