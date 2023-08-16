<?php

namespace App\Domain\Export;

class ExportCSV
{
    /**
     * @var resource
     */
    protected $handle;

    protected bool $isClose;

    public function __construct(string $file)
    {
        $openResource = fopen($file, 'r+');
        if (false === $openResource) {
            throw new \LogicException(sprintf('Error when opening file "%s"!', $file));
        }
        $this->handle = $openResource;
        $this->isClose = false;
    }

    public function __destruct()
    {
        $this->close();
    }

    /**
     * @param array<string, string> $line
     */
    public function add(array $line): void
    {
        foreach ($line as $key => $col) {
            $line[$key] = mb_convert_encoding($col, 'WINDOWS-1252', 'UTF-8');
        }

        fputcsv($this->handle, $line, ';');
    }

    public function close(): void
    {
        if ($this->isClose) {
            return;
        }

        fclose($this->handle);
        $this->isClose = true;
    }
}
