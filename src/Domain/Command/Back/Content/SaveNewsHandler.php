<?php

namespace App\Domain\Command\Back\Content;

use App\Entity\Content\News;
use App\Enum\FileTypeEnum;
use App\Repository\Content\NewsRepository;
use App\Service\File\FileCleaner;
use App\Service\File\FileUploader;

final class SaveNewsHandler
{
    public function __construct(
        private readonly NewsRepository $newsRepository,
        private readonly FileUploader $fileUploader,
        private readonly FileCleaner $fileCleaner,
    ) {
    }

    public function handle(SaveNewsCommand $command): void
    {
        $this->processFile($command->news);

        $this->newsRepository->add($command->news, true);
    }

    private function processFile(News $news): void
    {
        $pictureFile = $news->getPictureFile();
        if (null !== $pictureFile) {
            $this->fileCleaner->cleanEntity($news, FileTypeEnum::PICTURE);
            $news->setPictureUrl($this->fileUploader->upload($pictureFile, News::PICTURE_FOLDER));
        }
    }
}
