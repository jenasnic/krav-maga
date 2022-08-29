<?php

namespace App\Service\File;

use App\Enum\FileTypeEnum;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class FileCleaner
{
    public function __construct(protected PropertyAccessorInterface $propertyAccessor)
    {
    }

    public function cleanEntity(object $entity, ?string $type = null): void
    {
        if (null === $type) {
            foreach (FileTypeEnum::getForEntity($entity) as $currentType) {
                $this->removeFile($entity, $currentType);
            }
        } else {
            $this->removeFile($entity, $type);
        }
    }

    private function removeFile(object $entity, string $property): void
    {
        /** @var string|null $value */
        $value = $this->propertyAccessor->getValue($entity, $property);
        if (null !== $value && file_exists($value)) {
            unlink($value);
        }

        $this->propertyAccessor->setValue($entity, $property, null);
    }
}
