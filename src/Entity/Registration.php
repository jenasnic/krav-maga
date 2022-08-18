<?php

namespace App\Entity;

use App\Entity\Payment\PriceOption;
use App\Repository\RegistrationRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RegistrationRepository::class)]
class Registration
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $comment = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $privateNote = null;

    #[ORM\Column(type: 'text', length: 55, nullable: true)]
    private ?string $licenseNumber = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?DateTime $licenseDate = null;

    #[ORM\Column(type: 'boolean')]
    private bool $ffkPassport = false;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $medicalCertificateUrl = null;

    #[ORM\Column(type: 'datetime')]
    private DateTime $registeredAt;

    #[ORM\Column(type: 'boolean')]
    #[Assert\NotNull]
    private ?bool $copyrightAuthorization = null;

    #[ORM\Column(type: 'boolean')]
    private bool $withLegalRepresentative = false;

    #[ORM\OneToOne(targetEntity: LegalRepresentative::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[ORM\JoinColumn(nullable: true, onDelete: 'CASCADE')]
    #[Assert\Valid]
    private ?LegalRepresentative $legalRepresentative = null;

    #[ORM\ManyToOne(targetEntity: Purpose::class)]
    #[ORM\JoinColumn(nullable: true)]
    #[Assert\NotNull]
    private ?Purpose $purpose = null;

    #[ORM\ManyToOne(targetEntity: PriceOption::class)]
    #[ORM\JoinColumn(nullable: true)]
    #[Assert\NotNull]
    private ?PriceOption $priceOption = null;

    #[ORM\OneToOne(targetEntity: Emergency::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[Assert\Valid]
    private ?Emergency $emergency = null;

    #[ORM\OneToOne(targetEntity: Adherent::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    #[Assert\Valid]
    private Adherent $adherent;

    #[ORM\ManyToOne(targetEntity: Season::class)]
    protected Season $season;

    #[ORM\Column(type: 'boolean')]
    private bool $verified = false;

    private ?UploadedFile $medicalCertificateFile = null;

    public function __construct(Adherent $adherent, Season $season)
    {
        $this->adherent = $adherent;
        $this->season = $season;
        $this->registeredAt = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPrivateNote(): ?string
    {
        return $this->privateNote;
    }

    public function setPrivateNote(?string $privateNote): self
    {
        $this->privateNote = $privateNote;

        return $this;
    }

    public function getLicenseNumber(): ?string
    {
        return $this->licenseNumber;
    }

    public function setLicenseNumber(?string $licenseNumber): self
    {
        $this->licenseNumber = $licenseNumber;

        return $this;
    }

    public function getLicenseDate(): ?DateTime
    {
        return $this->licenseDate;
    }

    public function setLicenseDate(?DateTime $licenseDate): self
    {
        $this->licenseDate = $licenseDate;

        return $this;
    }

    public function isFfkPassport(): bool
    {
        return $this->ffkPassport;
    }

    public function setFfkPassport(bool $ffkPassport): self
    {
        $this->ffkPassport = $ffkPassport;

        return $this;
    }

    public function getMedicalCertificateUrl(): ?string
    {
        return $this->medicalCertificateUrl;
    }

    public function setMedicalCertificateUrl(?string $medicalCertificateUrl): self
    {
        $this->medicalCertificateUrl = $medicalCertificateUrl;

        return $this;
    }

    public function getRegisteredAt(): DateTime
    {
        return $this->registeredAt;
    }

    public function setRegisteredAt(DateTime $registeredAt): Registration
    {
        $this->registeredAt = $registeredAt;

        return $this;
    }

    public function getCopyrightAuthorization(): ?bool
    {
        return $this->copyrightAuthorization;
    }

    public function setCopyrightAuthorization(?bool $copyrightAuthorization): self
    {
        $this->copyrightAuthorization = $copyrightAuthorization;

        return $this;
    }

    public function isWithLegalRepresentative(): bool
    {
        return $this->withLegalRepresentative;
    }

    public function setWithLegalRepresentative(bool $withLegalRepresentative): self
    {
        $this->withLegalRepresentative = $withLegalRepresentative;

        if (!$this->withLegalRepresentative) {
            $this->legalRepresentative = null;
        } elseif (null === $this->legalRepresentative) {
            $this->legalRepresentative = new LegalRepresentative();
        }

        return $this;
    }

    public function getLegalRepresentative(): ?LegalRepresentative
    {
        return $this->legalRepresentative;
    }

    public function setLegalRepresentative(?LegalRepresentative $legalRepresentative): self
    {
        $this->legalRepresentative = $legalRepresentative;

        return $this;
    }

    public function getPurpose(): ?Purpose
    {
        return $this->purpose;
    }

    public function setPurpose(?Purpose $purpose): self
    {
        $this->purpose = $purpose;

        return $this;
    }

    public function getPriceOption(): ?PriceOption
    {
        return $this->priceOption;
    }

    public function setPriceOption(?PriceOption $priceOption): self
    {
        $this->priceOption = $priceOption;

        return $this;
    }

    public function getEmergency(): ?Emergency
    {
        return $this->emergency;
    }

    public function setEmergency(?Emergency $emergency): self
    {
        $this->emergency = $emergency;

        return $this;
    }

    public function getAdherent(): Adherent
    {
        return $this->adherent;
    }

    public function setAdherent(Adherent $adherent): self
    {
        $this->adherent = $adherent;

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

    public function isVerified(): bool
    {
        return $this->verified;
    }

    public function setVerified(bool $verified): self
    {
        $this->verified = $verified;

        return $this;
    }

    public function getMedicalCertificateFile(): ?UploadedFile
    {
        return $this->medicalCertificateFile;
    }

    public function setMedicalCertificateFile(?UploadedFile $medicalCertificateFile): self
    {
        $this->medicalCertificateFile = $medicalCertificateFile;

        return $this;
    }
}
