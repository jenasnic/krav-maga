<?php

namespace App\Repository;

use App\Entity\ReEnrollmentToken;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ReEnrollmentToken>
 */
class ReEnrollmentTokenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReEnrollmentToken::class);
    }

    public function add(ReEnrollmentToken $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ReEnrollmentToken $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function hasExpiredToken(): bool
    {
        $queryBuilder = $this->createQueryBuilder('re_enrollment_token');

        $queryBuilder
            ->select('COUNT(re_enrollment_token)')
            ->andWhere('re_enrollment_token.expiresAt < NOW()')
        ;

        return $queryBuilder->getQuery()->getSingleScalarResult() > 0;
    }

    public function removeExpiredToken(): void
    {
        $queryBuilder = $this->getEntityManager()
            ->createQueryBuilder()
            ->delete(ReEnrollmentToken::class, 're_enrollment_token')
            ->where('re_enrollment_token.expiresAt < NOW()')
        ;

        $queryBuilder->getQuery()->execute();
    }

    public function removeAllToken(): void
    {
        $queryBuilder = $this->getEntityManager()
            ->createQueryBuilder()
            ->delete(ReEnrollmentToken::class, 're_enrollment_token')
        ;

        $queryBuilder->getQuery()->execute();
    }
}
