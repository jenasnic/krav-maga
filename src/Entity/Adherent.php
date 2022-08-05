<?php

namespace App\Entity;

use App\Helper\StringHelper;
use App\Repository\AdherentRepository;
use App\ValueObject\Address;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AdherentRepository::class)]
class Adherent
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

    #[ORM\Column(type: 'string', length: 55)]
    #[Assert\NotNull]
    private ?string $gender = null;

    #[ORM\Column(type: 'datetime')]
    #[Assert\NotNull]
    private ?DateTime $birthDate = null;

    #[ORM\Column(type: 'string', length: 55, nullable: true)]
    #[Assert\NotBlank]
    #[Assert\Regex('/^[\d\s]{14}$/')]
    private ?string $phone = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    #[Assert\Email]
    private ?string $email = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $pseudonym = null;

    #[ORM\Embedded(class: Address::class)]
    #[Assert\NotNull]
    private ?Address $address = null;

    #[ORM\OneToOne(targetEntity: RegistrationInfo::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    #[Assert\Valid]
    private ?RegistrationInfo $registrationInfo = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $pictureUrl = null;

    private ?UploadedFile $pictureFile = null;

    #[ORM\Column(type: 'boolean')]
    private bool $verified = false;

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

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(?string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getBirthDate(): ?DateTime
    {
        return $this->birthDate;
    }

    public function setBirthDate(?DateTime $birthDate): self
    {
        $this->birthDate = $birthDate;

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPseudonym(): ?string
    {
        return $this->pseudonym;
    }

    public function setPseudonym(?string $pseudonym): self
    {
        $this->pseudonym = $pseudonym;

        return $this;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(?Address $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getRegistrationInfo(): ?RegistrationInfo
    {
        return $this->registrationInfo;
    }

    public function setRegistrationInfo(?RegistrationInfo $registrationInfo): self
    {
        $this->registrationInfo = $registrationInfo;

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

    public function getPictureUrl(): ?string
    {
        return $this->pictureUrl;
    }

    public function setPictureUrl(?string $pictureUrl): self
    {
        $this->pictureUrl = $pictureUrl;

        return $this;
    }

    public function getPictureFile(): ?UploadedFile
    {
        return $this->pictureFile;
    }

    public function setPictureFile(?UploadedFile $pictureFile): self
    {
        $this->pictureFile = $pictureFile;

        return $this;
    }
}
