<?php

namespace App\Domain\Command\Back;

use App\Entity\Adherent;
use App\Enum\FileTypeEnum;
use App\Repository\AdherentRepository;
use App\Service\File\FileCleaner;
use App\Service\File\FileUploader;

final class SaveAdherentHandler
{
    public function __construct(
        private readonly AdherentRepository $adherentRepository,
        private readonly FileUploader $fileUploader,
        private readonly FileCleaner $fileCleaner,
    ) {
    }

    public function handle(SaveAdherentCommand $command): void
    {
        $this->processFile($command->adherent);

        $this->adherentRepository->add($command->adherent, true);
    }

    private function processFile(Adherent $adherent): void
    {
        $pictureFile = $adherent->getPictureFile();
        if (null !== $pictureFile) {
            $this->fileCleaner->cleanEntity($adherent, FileTypeEnum::PICTURE);
            $adherent->setPictureUrl($this->fileUploader->upload($pictureFile));
        }
    }
}
