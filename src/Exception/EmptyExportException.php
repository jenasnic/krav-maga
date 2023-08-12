<?php

namespace App\Exception;

class EmptyExportException extends \Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
