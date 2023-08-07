<?php

namespace App\Entity;

use App\Entity\Payment\PriceOption;
use App\Repository\SeasonRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SeasonRepository::class)]
class Season
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 55)]
    private string $label;

    #[ORM\Column(type: 'boolean')]
    private bool $active;

    #[ORM\Column(type: 'datetime')]
    #[Assert\NotNull]
    private ?DateTime $startDate = null;

    #[ORM\Column(type: 'datetime')]
    #[Assert\NotNull]
    private ?DateTime $endDate = null;

    /**
     * @var Collection<int, PriceOption>
     */
    #[ORM\OneToMany(mappedBy: 'season', targetEntity: PriceOption::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[ORM\OrderBy(['rank' => 'ASC'])]
    private Collection $priceOptions;

    public function __construct(string $label)
    {
        $this->label = $label;
        $this->active = false;

        $this->priceOptions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getStartDate(): ?DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(?DateTime $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?DateTime
    {
        return $this->endDate;
    }

    public function setEndDate(?DateTime $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * @return Collection<int, PriceOption>
     */
    public function getPriceOptions(): Collection
    {
        return $this->priceOptions;
    }

    public function addPriceOption(PriceOption $priceOption): self
    {
        if (!$this->priceOptions->contains($priceOption)) {
            $this->priceOptions->add($priceOption);
        }

        return $this;
    }

    public function removePriceOption(PriceOption $priceOption): self
    {
        $this->priceOptions->removeElement($priceOption);

        return $this;
    }

    public function getDisplayLabel(): string
    {
        return sprintf('%s-%s', $this->getStartDate()?->format('Y'), $this->getendDate()?->format('Y'));
    }
}
