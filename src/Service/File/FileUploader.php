<?php

namespace App\Service\File;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    public function __construct(
        private readonly string $uploadPath,
    ) {
    }

    public function upload(UploadedFile $uploadedFile): string
    {
        $fileName = sprintf(
            '%s.%s',
            str_replace('.', '', uniqid('', true)),
            $uploadedFile->getClientOriginalExtension()
        );

        $uploadedFile->move($this->uploadPath, $fileName);

        return $this->uploadPath.DIRECTORY_SEPARATOR.$fileName;
    }
}
