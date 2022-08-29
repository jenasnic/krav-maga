<?php

namespace App\Enum;

use App\Entity\Adherent;
use App\Entity\Registration;
use LogicException;

class FileTypeEnum
{
    public const LICENCE_FORM = 'licenceFormUrl';
    public const MEDICAL_CERTIFICATE = 'medicalCertificateUrl';
    public const PASS_15 = 'pass15Url';
    public const PASS_50 = 'pass50Url';
    public const PICTURE = 'pictureUrl';

    /**
     * @return array<string>
     */
    public static function getAll(): array
    {
        return [
            self::LICENCE_FORM,
            self::MEDICAL_CERTIFICATE,
            self::PASS_15,
            self::PASS_50,
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
                self::PASS_15,
                self::PASS_50,
            ],
            $entity instanceof Adherent => [self::PICTURE],
            default => throw new LogicException('unsupported entity type'),
        };
    }
}
