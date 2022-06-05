<?php

namespace App\Entity;

use App\Repository\OfferRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OfferRepository::class)
 */
class Offer
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
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $typeOfContract;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="text")
     */
    private $profileDescription;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $competencies = [];

    /**
     * @ORM\Column(type="text")
     */
    private $positionDescription;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $positionMissions = [];

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\ManyToOne(targetEntity=Company::class, inversedBy="offers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $company;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getTypeOfContract(): ?string
    {
        return $this->typeOfContract;
    }

    public function setTypeOfContract(string $typeOfContract): self
    {
        $this->typeOfContract = $typeOfContract;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getProfileDescription(): ?string
    {
        return $this->profileDescription;
    }

    public function setProfileDescription(string $profileDescription): self
    {
        $this->profileDescription = $profileDescription;

        return $this;
    }

    public function getCompetencies(): ?array
    {
        return $this->competencies;
    }

    public function setCompetencies(?array $competencies): self
    {
        $this->competencies = $competencies;

        return $this;
    }

    public function getPositionDescription(): ?string
    {
        return $this->positionDescription;
    }

    public function setPositionDescription(string $positionDescription): self
    {
        $this->positionDescription = $positionDescription;

        return $this;
    }

    public function getPositionMissions(): ?array
    {
        return $this->positionMissions;
    }

    public function setPositionMissions(?array $positionMissions): self
    {
        $this->positionMissions = $positionMissions;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): self
    {
        $this->company = $company;

        return $this;
    }
}
