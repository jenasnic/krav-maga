<?php

namespace App\Service\Grid;

use App\Enum\GenderEnum;
use DateTime;
use Doctrine\ORM\QueryBuilder;

class AdherentFilter implements FilterInterface
{
    public const YOUNG = 'YOUNG';
    public const MALE = 'MALE';
    public const FEMALE = 'FEMALE';

    public function apply(QueryBuilder $queryBuilder, ?string $filter = null): QueryBuilder
    {
        if (null === $filter || !in_array($filter, $this->getFilters())) {
            return $queryBuilder;
        }

        switch ($filter) {
            case self::YOUNG:
                $queryBuilder
                    ->andWhere('adherent.birthDate > :minorDate')
                    ->setParameter('minorDate', new DateTime('-18 years'))
                ;
                break;
            case self::MALE:
                $queryBuilder
                    ->andWhere('adherent.gender = :gender')
                    ->setParameter('gender', GenderEnum::MALE)
                ;
                break;
            case self::FEMALE:
                $queryBuilder
                    ->andWhere('adherent.gender = :gender')
                    ->setParameter('gender', GenderEnum::FEMALE)
                ;
                break;
        }

        return $queryBuilder;
    }

    /***
     * @return array<string>
     */
    public function getFilters(): array
    {
        return [
            self::YOUNG,
            self::MALE,
            self::FEMALE,
        ];
    }
}
