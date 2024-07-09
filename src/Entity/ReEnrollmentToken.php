<?php

namespace App\Entity;

use App\Repository\ReEnrollmentTokenRepository;
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

    #[ORM\ManyToOne(targetEntity: Season::class)]
    private Season $season;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $expiresAt;

    public function __construct(string $id, Adherent $adherent, Season $season, \DateTime $expiresAt)
    {
        $this->id = $id;
        $this->adherent = $adherent;
        $this->season = $season;
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

    public function getSeason(): Season
    {
        return $this->season;
    }

    public function getExpiresAt(): \DateTime
    {
        return $this->expiresAt;
    }
}
