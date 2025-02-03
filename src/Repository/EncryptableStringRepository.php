<?php

namespace App\Repository;

use App\Entity\EncryptableString;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EncryptableString>
 *
 * @method EncryptableString|null find($id, $lockMode = null, $lockVersion = null)
 * @method EncryptableString|null findOneBy(array $criteria, array $orderBy = null)
 * @method EncryptableString[]    findAll()
 * @method EncryptableString[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EncryptableStringRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EncryptableString::class);
    }

//    /**
//     * @return EncryptableString[] Returns an array of EncryptableString objects
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

//    public function findOneBySomeField($value): ?EncryptableString
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
