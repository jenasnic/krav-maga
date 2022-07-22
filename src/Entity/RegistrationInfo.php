<?php

namespace App\Entity;

use App\Repository\RegistrationInfoRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[ORM\Entity(repositoryClass: RegistrationInfoRepository::class)]
class RegistrationInfo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Purpose::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?Purpose $purpose = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $medicalCertificateUrl = null;

    #[ORM\Column(type: 'boolean')]
    private ?bool $copyrightAuthorization = null;

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

    public function getCopyrightAuthorization(): ?bool
    {
        return $this->copyrightAuthorization;
    }

    public function setCopyrightAuthorization(?bool $copyrightAuthorization): self
    {
        $this->copyrightAuthorization = $copyrightAuthorization;

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
