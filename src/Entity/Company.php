<?php

namespace App\Entity;

use App\Repository\CompanyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     collectionOperations={"get"},
 *     itemOperations={"get"},
 *     normalizationContext={"groups"={"company_listing:read"}},
 *     attributes={
 *          "pagination_items_per_page"=12
 *     }
 * )
 * @ORM\Entity(repositoryClass=CompanyRepository::class)
 */
class Company
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"company_listing:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"company_listing:read", "offer_listing:read", "offer_detail:read"})
     */
    private $companyName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"company_listing:read", "offer_listing:read", "offer_detail:read"})
     */
    private $companyLogo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"company_listing:read", "offer_listing:read", "offer_detail:read"})
     */
    private $companyLogoBackgroundColor;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"company_listing:read", "offer_listing:read", "offer_detail:read"})
     */
    private $companyTown;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"company_listing:read", "offer_detail:read"})
     */
    private $companyWebsite;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"company_listing:read", "offer_detail:read"})
     */
    private $contactLastname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"company_listing:read", "offer_detail:read"})
     */
    private $contactFirstname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"company_listing:read", "offer_detail:read"})
     */
    private $contactEmail;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"company_listing:read", "offer_detail:read"})
     */
    private $contactPhone;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="company")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Offer::class, mappedBy="company", orphanRemoval=true)
     * @Groups({"company_listing:read"})
     */
    private $offers;

    public function __construct()
    {
        $this->offers = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Offer>
     */
    public function getOffers(): Collection
    {
        return $this->offers;
    }

    public function addOffer(Offer $offer): self
    {
        if (!$this->offers->contains($offer)) {
            $this->offers[] = $offer;
            $offer->setCompany($this);
        }

        return $this;
    }

    public function removeOffer(Offer $offer): self
    {
        if ($this->offers->removeElement($offer)) {
            // set the owning side to null (unless already changed)
            if ($offer->getCompany() === $this) {
                $offer->setCompany(null);
            }
        }

        return $this;
    }
}
