<?php

namespace App\Entity;

use App\Helper\StringHelper;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
class Emergency
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 55)]
    #[Assert\NotBlank]
    private ?string $firstName = null;

    #[ORM\Column(type: 'string', length: 55)]
    #[Assert\NotBlank]
    private ?string $lastName = null;

    #[ORM\Column(type: 'string', length: 55, nullable: true)]
    #[Assert\NotBlank]
    #[Assert\Regex('/^[\d\s]{14}$/')]
    private ?string $phone = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;
        if (!empty($firstName)) {
            $this->firstName = StringHelper::capitalizeFirstname($firstName);
        }

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;
        if (!empty($lastName)) {
            $this->lastName = mb_strtoupper($lastName);
        }

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }
}
