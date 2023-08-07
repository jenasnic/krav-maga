<?php

namespace App\Enum;

use App\Entity\Adherent;
use App\Entity\Registration;
use LogicException;

class FileTypeEnum
{
    public const LICENCE_FORM = 'licenceFormUrl';
    public const MEDICAL_CERTIFICATE = 'medicalCertificateUrl';
    public const PASS_CITIZEN = 'passCitizenUrl';
    public const PASS_SPORT = 'passSportUrl';
    public const PICTURE = 'pictureUrl';

    /**
     * @return array<string>
     */
    public static function getAll(): array
    {
        return [
            self::LICENCE_FORM,
            self::MEDICAL_CERTIFICATE,
            self::PASS_CITIZEN,
            self::PASS_SPORT,
            self::PICTURE,
        ];
    }

    /**
     * @return array<string>
     */
    public static function getForEntity(object $entity): array
    {
        return match (true) {
            $entity instanceof Registration => [
                self::LICENCE_FORM,
                self::MEDICAL_CERTIFICATE,
                self::PASS_CITIZEN,
                self::PASS_SPORT,
            ],
            $entity instanceof Adherent => [self::PICTURE],
            default => throw new LogicException('unsupported entity type'),
        };
    }
}
