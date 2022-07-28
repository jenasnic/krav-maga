<?php

namespace App\Repository;

use App\Entity\RegistrationInfo;
use App\Enum\GenderEnum;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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

    /**
     * @return array<string, mixed>
     */
    public function search(?string $filter = null): array
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

        switch ($filter) {
            case 'ado':
                $queryBuilder
                    ->andWhere('adherent.birthDate > :minorDate')
                    ->setParameter('minorDate', new DateTime('-18 years'))
                ;
                break;
            case 'homme':
                $queryBuilder
                    ->andWhere('adherent.gender = :gender')
                    ->setParameter('gender', GenderEnum::MALE)
                ;
                break;
            case 'femme':
                $queryBuilder
                    ->andWhere('adherent.gender = :gender')
                    ->setParameter('gender', GenderEnum::FEMALE)
                ;
                break;
        }

        /** @var array<string, mixed> */
        return $queryBuilder->getQuery()->getResult();
    }
}
