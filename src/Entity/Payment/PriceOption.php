<?php

namespace App\Entity\Payment;

use App\Entity\Season;
use App\Repository\Payment\PriceOptionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PriceOptionRepository::class)]
class PriceOption
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private string $label;

    #[ORM\Column(type: 'float')]
    private float $amount;

    #[ORM\Column(type: 'integer')]
    private int $rank = 0;

    #[ORM\ManyToOne(targetEntity: Season::class, inversedBy: 'priceOptions')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'cascade')]
    private Season $season;

    public function __construct(string $label, float $amount, Season $season)
    {
        $this->label = $label;
        $this->amount = $amount;
        $this->season = $season;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getRank(): int
    {
        return $this->rank;
    }

    public function setRank(int $rank): self
    {
        $this->rank = $rank;

        return $this;
    }

    public function getSeason(): Season
    {
        return $this->season;
    }

    public function setSeason(Season $season): self
    {
        $this->season = $season;

        return $this;
    }
}
