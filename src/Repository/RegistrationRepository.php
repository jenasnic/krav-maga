<?php

namespace App\Repository;

use App\Entity\Registration;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Registration>
 */
class RegistrationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Registration::class);
    }

    public function add(Registration $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Registration $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getForAdherent(int $adherentId): Registration
    {
        $registration = $this
            ->createQueryBuilder('registration')
            ->innerJoin('registration.adherent', 'adherent')
            ->andWhere('adherent.id = :adherentId')
            ->setParameter('adherentId', $adherentId)
            ->getQuery()
            ->getOneOrNullResult()
        ;

        if (!$registration instanceof Registration) {
            throw new \LogicException(sprintf('no registration for adherent %d', $adherentId));
        }

        return $registration;
    }

    /**
     * @return \Generator<Registration>
     */
    public function findForExport(): \Generator
    {
        $queryBuilder = $this->createQueryBuilder('registration');

        $queryBuilder
            ->innerJoin('registration.purpose', 'purpose')
            ->innerJoin('registration.adherent', 'adherent')
        ;

        /** @var Registration $item */
        foreach ($queryBuilder->getQuery()->toIterable() as $item) {
            yield $item;
        }
    }
}
