<?php

namespace App\Service\Export;

use App\Domain\Export\ExportCSV;
use App\Exception\EmptyExportException;
use Generator;
use Symfony\Component\HttpFoundation\StreamedResponse;

abstract class AbstractCsvExport
{
    /**
     * @return array<string>
     */
    abstract protected function getHeaders(): array;

    /**
     * @return array<string>
     */
    abstract protected function buildLine(mixed $data): array;

    abstract protected function getFilename(): string;

    /**
     * @param Generator<mixed> $generator
     */
    protected function getStreamedResponse(Generator $generator): StreamedResponse
    {
        $current = $generator->current();
        if (null === $current) {
            throw new EmptyExportException('No result found!');
        }

        return new StreamedResponse(function () use ($generator) {
            $export = new ExportCSV('php://output');
            $export->add($this->getHeaders());

            do {
                $result = $generator->current();
                $export->add($this->buildLine($result));
                $generator->next();
                $next = $generator->current();
            } while (null !== $next);

            $export->close();
        }, 200, [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename='.$this->getFilename(),
        ]);
    }
}
