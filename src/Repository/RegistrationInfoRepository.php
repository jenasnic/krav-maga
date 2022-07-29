<?php

namespace App\Repository;

use App\Entity\RegistrationInfo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RegistrationInfo>
 */
class RegistrationInfoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RegistrationInfo::class);
    }

    public function add(RegistrationInfo $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(RegistrationInfo $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function createSearchQueryBuilder(string $alias = 'registration_info'): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('registration_info');

        $queryBuilder
            ->innerJoin('registration_info.adherent', 'adherent')
            ->select(
                'adherent.id AS adherentId',
                'registration_info.id AS registrationInfoId',
                'adherent.firstName',
                'adherent.lastName',
                'adherent.phone',
                'adherent.email',
                'registration_info.registeredAt',
            )
            ->addOrderBy('registration_info.registeredAt', 'DESC')
        ;

        return $queryBuilder;
    }
}
