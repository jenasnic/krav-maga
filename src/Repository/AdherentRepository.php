<?php

namespace App\Repository;

use App\Entity\Adherent;
use App\Entity\Payment\AbstractPayment;
use App\Entity\Registration;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Adherent>
 */
class AdherentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Adherent::class);
    }

    public function add(Adherent $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Adherent $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function createSearchQueryBuilder(int $seasonId): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('adherent');

        $queryBuilder
            ->select(
                'adherent.id AS adherentId',
                'registration.id AS registrationId',
                'adherent.firstName',
                'adherent.lastName',
                'adherent.gender',
                'adherent.phone',
                'adherent.email',
                'registration.registeredAt',
                'registration.reEnrollment',
                'season.label AS seasonLabel',
                'SUM(payment.amount) AS totalPaid',
                'price_option.amount AS toPay'
            )
            ->innerJoin(Registration::class, 'registration', Join::WITH, 'registration.adherent = adherent')
            ->innerJoin('registration.season', 'season', Join::WITH, 'season.id = :seasonId')
            ->innerJoin('registration.priceOption', 'price_option')
            ->leftJoin(
                AbstractPayment::class,
                'payment',
                Join::WITH,
                'payment.season = registration.season AND payment.adherent = adherent'
            )
            ->groupBy('adherent')
            ->addOrderBy('registration.registeredAt', 'DESC')
            ->andWhere('registration.verified = TRUE')
            ->setParameter('seasonId', $seasonId)
        ;

        return $queryBuilder;
    }

    public function createSearchAllQueryBuilder(): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('adherent');

        $queryBuilder
            ->select(
                'adherent.id AS adherentId',
                'adherent.firstName',
                'adherent.lastName',
                'adherent.gender',
                'adherent.phone',
                'adherent.email',
                'registration.verified AS registrationVerified',
                'season.label AS seasonLabel',
            )
            ->innerJoin(Registration::class, 'registration', Join::WITH, 'registration.adherent = adherent')
            ->innerJoin('registration.season', 'season')
            ->addOrderBy('registration.registeredAt', 'DESC')
        ;

        return $queryBuilder;
    }

    /**
     * @return array<array<string>>
     */
    public function findForGallery(): array
    {
        $queryBuilder = $this->createQueryBuilder('adherent');

        $queryBuilder
            ->select(
                'adherent.id',
                'adherent.firstName',
                'adherent.lastName',
                'adherent.gender',
                'adherent.pictureUrl',
            )
            ->addOrderBy('adherent.lastName', 'ASC')
            ->addOrderBy('adherent.firstName', 'ASC')
        ;

        /** @var array<array<string>> */
        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @return array<Adherent>
     */
    public function findForSeason(int $seasonId): array
    {
        $queryBuilder = $this->createQueryBuilder('adherent');

        $queryBuilder
            ->innerJoin(Registration::class, 'registration', Join::WITH, 'registration.adherent = adherent')
            ->innerJoin('registration.season', 'season')
            ->andWhere('season.id = :seasonId')
            ->setParameter('seasonId', $seasonId)
        ;

        /** @var array<Adherent> */
        return $queryBuilder->getQuery()->getResult();
    }
}
