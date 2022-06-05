<?php

namespace App\Entity;

use App\Repository\CompanyRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CompanyRepository::class)
 */
class Company
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $companyName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $companyLogo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $companyLogoBackgroundColor;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $companyTown;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $companyWebsite;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $contactLastname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $contactFirstname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $contactEmail;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $contactPhone;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="company")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompanyName(): string
    {
        return $this->companyName;
    }

    public function setCompanyName(string $companyName): self
    {
        $this->companyName = $companyName;

        return $this;
    }

    public function getCompanyLogo(): ?string
    {
        return $this->companyLogo;
    }

    public function setCompanyLogo(string $companyLogo): self
    {
        $this->companyLogo = $companyLogo;

        return $this;
    }

    public function getCompanyLogoBackgroundColor(): ?string
    {
        return $this->companyLogoBackgroundColor;
    }

    public function setCompanyLogoBackgroundColor(?string $companyLogoBackgroundColor): self
    {
        $this->companyLogoBackgroundColor = $companyLogoBackgroundColor;

        return $this;
    }

    public function getCompanyTown(): ?string
    {
        return $this->companyTown;
    }

    public function setCompanyTown(string $companyTown): self
    {
        $this->companyTown = $companyTown;

        return $this;
    }

    public function getCompanyWebsite(): ?string
    {
        return $this->companyWebsite;
    }

    public function setCompanyWebsite(?string $companyWebsite): self
    {
        $this->companyWebsite = $companyWebsite;

        return $this;
    }

    public function getContactLastname(): ?string
    {
        return $this->contactLastname;
    }

    public function setContactLastname(?string $contactLastname): self
    {
        $this->contactLastname = $contactLastname;

        return $this;
    }

    public function getContactFirstname(): ?string
    {
        return $this->contactFirstname;
    }

    public function setContactFirstname(?string $contactFirstname): self
    {
        $this->contactFirstname = $contactFirstname;

        return $this;
    }

    public function getContactEmail(): ?string
    {
        return $this->contactEmail;
    }

    public function setContactEmail(?string $contactEmail): self
    {
        $this->contactEmail = $contactEmail;

        return $this;
    }

    public function getContactPhone(): ?string
    {
        return $this->contactPhone;
    }

    public function setContactPhone(?string $contactPhone): self
    {
        $this->contactPhone = $contactPhone;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function __toString()
    {
        return $this->getCompanyName();
    }
}
