<?php

namespace App\Entity;

use App\Repository\RegistrationInfoRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RegistrationInfoRepository::class)]
class RegistrationInfo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Purpose::class)]
    #[ORM\JoinColumn(nullable: true)]
    #[Assert\NotNull]
    private ?Purpose $purpose = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $medicalCertificateUrl = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $comment = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $privateNote = null;

    #[ORM\Column(type: 'text', length: 55, nullable: true)]
    private ?string $licenceNumber = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?DateTime $licenceDate = null;

    #[ORM\Column(type: 'boolean')]
    #[Assert\NotNull]
    private ?bool $copyrightAuthorization = null;

    #[ORM\Column(type: 'boolean')]
    private bool $ffkPassport = false;

    #[ORM\OneToOne(targetEntity: Emergency::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    #[Assert\Valid]
    private ?Emergency $emergency = null;

    #[ORM\OneToOne(mappedBy: 'registrationInfo', targetEntity: Adherent::class)]
    private ?Adherent $adherent = null;

    #[ORM\Column(type: 'datetime')]
    private DateTime $registeredAt;

    private ?UploadedFile $medicalCertificateFile = null;

    public function __construct()
    {
        $this->registeredAt = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getMedicalCertificateUrl(): ?string
    {
        return $this->medicalCertificateUrl;
    }

    public function setMedicalCertificateUrl(?string $medicalCertificateUrl): self
    {
        $this->medicalCertificateUrl = $medicalCertificateUrl;

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

    public function getPrivateNote(): ?string
    {
        return $this->privateNote;
    }

    public function setPrivateNote(?string $privateNote): self
    {
        $this->privateNote = $privateNote;

        return $this;
    }

    public function getLicenceNumber(): ?string
    {
        return $this->licenceNumber;
    }

    public function setLicenceNumber(?string $licenceNumber): self
    {
        $this->licenceNumber = $licenceNumber;

        return $this;
    }

    public function getLicenceDate(): ?DateTime
    {
        return $this->licenceDate;
    }

    public function setLicenceDate(?DateTime $licenceDate): self
    {
        $this->licenceDate = $licenceDate;

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

    public function isFfkPassport(): bool
    {
        return $this->ffkPassport;
    }

    public function setFfkPassport(bool $ffkPassport): self
    {
        $this->ffkPassport = $ffkPassport;

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

    public function getAdherent(): ?Adherent
    {
        return $this->adherent;
    }

    public function setAdherent(?Adherent $adherent): self
    {
        $this->adherent = $adherent;

        return $this;
    }

    public function getRegisteredAt(): DateTime
    {
        return $this->registeredAt;
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
