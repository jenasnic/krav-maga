<?php

namespace App\Entity;

use App\Repository\ConfigurationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConfigurationRepository::class)]
class Configuration
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 55)]
    private string $code;

    #[ORM\Column(type: 'text')]
    private string $value;

    public function __construct(string $code, string $value)
    {
        $this->code = $code;
        $this->value = $value;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }
}
