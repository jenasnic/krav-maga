<?php

namespace App\Entity;

use App\Repository\ReEnrollmentTokenRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReEnrollmentTokenRepository::class)]
class ReEnrollmentToken
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 55)]
    private string $id;

    #[ORM\OneToOne(targetEntity: Adherent::class)]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    private Adherent $adherent;

    #[ORM\Column(type: 'datetime')]
    private DateTime $expiresAt;

    public function __construct(string $id, Adherent $adherent, DateTime $expiresAt)
    {
        $this->id = $id;
        $this->adherent = $adherent;
        $this->expiresAt = $expiresAt;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getAdherent(): Adherent
    {
        return $this->adherent;
    }

    public function getExpiresAt(): DateTime
    {
        return $this->expiresAt;
    }
}
