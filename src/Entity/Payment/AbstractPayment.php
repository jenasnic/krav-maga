<?php

namespace App\Entity\Payment;

use App\Entity\Adherent;
use App\Entity\Season;
use App\Repository\Payment\PaymentRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PaymentRepository::class)]
#[ORM\InheritanceType(value: 'JOINED')]
#[ORM\DiscriminatorColumn(name: 'type', type: 'string')]
#[ORM\DiscriminatorMap([
    'ancv' => AncvPayment::class,
    'cash' => CashPayment::class,
    'check' => CheckPayment::class,
    'pass' => PassPayment::class,
    'transfer' => TransferPayment::class,
])]
#[ORM\Table(name: 'payment')]
abstract class AbstractPayment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    protected ?int $id = null;

    #[ORM\Column(type: 'datetime')]
    #[Assert\NotNull]
    protected DateTime $date;

    #[ORM\Column(type: 'float')]
    #[Assert\GreaterThan(0)]
    protected ?float $amount = null;

    #[ORM\Column(type: 'text', nullable: true)]
    protected ?string $comment = null;

    #[ORM\ManyToOne(targetEntity: Adherent::class)]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    protected Adherent $adherent;

    #[ORM\ManyToOne(targetEntity: Season::class)]
    protected Season $season;

    public function __construct(Adherent $adherent, Season $season)
    {
        $this->adherent = $adherent;
        $this->season = $season;
        $this->date = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): DateTime
    {
        return $this->date;
    }

    public function setDate(DateTime $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(?float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getAdherent(): Adherent
    {
        return $this->adherent;
    }

    public function getSeason(): Season
    {
        return $this->season;
    }

    abstract public function getPaymentType(): string;
}
