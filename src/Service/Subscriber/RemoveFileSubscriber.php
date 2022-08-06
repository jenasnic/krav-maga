<?php

namespace App\Service\Subscriber;

use App\Entity\Adherent;
use App\Entity\Registration;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Doctrine\Persistence\ObjectManager;

class RemoveFileSubscriber implements EventSubscriberInterface
{
    /**
     * @var array<string>
     */
    private array $filesToRemove = [];

    public function getSubscribedEvents(): array
    {
        return [
            Events::preRemove,
            Events::postRemove,
        ];
    }

    /**
     * @param LifecycleEventArgs<ObjectManager> $args
     */
    public function preRemove(LifecycleEventArgs $args): void
    {
        $object = $args->getObject();

        if ($object instanceof Adherent && null !== $object->getPictureUrl()) {
            $this->filesToRemove[] = $object->getPictureUrl();
        }

        if ($object instanceof Registration && null !== $object->getMedicalCertificateUrl()) {
            $this->filesToRemove[] = $object->getMedicalCertificateUrl();
        }
    }

    /**
     * @param LifecycleEventArgs<ObjectManager> $args
     */
    public function postRemove(LifecycleEventArgs $args): void
    {
        foreach ($this->filesToRemove as $filePath) {
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
    }
}
