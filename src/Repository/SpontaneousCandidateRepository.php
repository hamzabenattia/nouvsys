<?php

namespace App\Repository;

use App\Entity\SpontaneousCandidate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SpontaneousCandidate>
 *
 * @method SpontaneousCandidate|null find($id, $lockMode = null, $lockVersion = null)
 * @method SpontaneousCandidate|null findOneBy(array $criteria, array $orderBy = null)
 * @method SpontaneousCandidate[]    findAll()
 * @method SpontaneousCandidate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpontaneousCandidateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SpontaneousCandidate::class);
    }

//    /**
//     * @return SpontaneousCandidate[] Returns an array of SpontaneousCandidate objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?SpontaneousCandidate
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
