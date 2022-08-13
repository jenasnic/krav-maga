<?php

namespace App\Repository;

use App\Entity\Adherent;
use App\Entity\Registration;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Generator;

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

    public function createSearchQueryBuilder(string $alias = 'registration'): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('adherent');

        $queryBuilder
            ->innerJoin(Registration::class, 'registration', Join::WITH, 'registration.adherent = adherent')
            ->innerJoin('registration.season', 'season')
            ->select(
                'adherent.id AS adherentId',
                'registration.id AS registrationId',
                'adherent.firstName',
                'adherent.lastName',
                'adherent.phone',
                'adherent.email',
                'registration.registeredAt',
                'season.label AS seasonLabel'
            )
            ->addOrderBy('registration.registeredAt', 'DESC')
        ;

        return $queryBuilder;
    }

    /**
     * @return Generator<array<string, mixed>>
     */
    public function findForExport(): Generator
    {
        $queryBuilder = $this->createQueryBuilder('adherent');

        $queryBuilder
            ->innerJoin('adherent.registration', 'registration')
            ->innerJoin('registration.purpose', 'purpose')
            ->select(
                'adherent.id',
                'adherent.firstName',
                'adherent.lastName',
                'adherent.gender',
                'DATE_FORMAT(adherent.birthDate, \'%d/%m/%Y\') AS birthDate',
                'adherent.phone',
                'adherent.email',
                'purpose.label AS purposeLabel',
                'registration.copyrightAuthorization',
                'DATE_FORMAT(registration.registeredAt, \'%d/%m/%Y\') AS registeredAt',
            )
        ;

        /** @var array<string, mixed> $item */
        foreach ($queryBuilder->getQuery()->toIterable() as $item) {
            yield $item;
        }
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
}
