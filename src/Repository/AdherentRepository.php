<?php

namespace App\Repository;

use App\Entity\Adherent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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

    /**
     * @return Generator<array<string, mixed>>
     */
    public function findForExport(): Generator
    {
        $queryBuilder = $this->createQueryBuilder('adherent');

        $queryBuilder
            ->innerJoin('adherent.registrationInfo', 'registration_info')
            ->innerJoin('registration_info.purpose', 'purpose')
            ->select(
                'adherent.id',
                'adherent.firstName',
                'adherent.lastName',
                'adherent.gender',
                'DATE_FORMAT(adherent.birthDate, \'%d/%m/%Y\') AS birthDate',
                'adherent.phone',
                'adherent.email',
                'purpose.label AS purposeLabel',
                'registration_info.copyrightAuthorization',
                'DATE_FORMAT(registration_info.registeredAt, \'%d/%m/%Y\') AS registeredAt',
            )
        ;

        foreach ($queryBuilder->getQuery()->toIterable() as $item) {
            yield $item;
        }
    }
}
