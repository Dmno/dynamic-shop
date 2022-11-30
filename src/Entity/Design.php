<?php

namespace App\Entity;

use App\Repository\DesignRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DesignRepository::class)]
class Design
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $title = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?int $titleFontSize = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Image $logoImage = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $pageColor = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $secondaryPageColor = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $textColor = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $secondaryTextColor = null;

    #[ORM\Column(nullable: true)]
    private ?int $phoneNumber = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $companyName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $address = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $country = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $postalCode = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $copyright = null;

    #[ORM\Column(nullable: true)]
    private ?int $productCount = 0;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Image $backgroundImage = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $productTitle = null;

    #[ORM\Column(length: 5, nullable: true)]
    private ?string $currency = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getTitleFontSize(): ?int
    {
        return $this->titleFontSize;
    }

    public function setTitleFontSize(?int $titleFontSize): self
    {
        $this->titleFontSize = $titleFontSize;

        return $this;
    }

    public function getLogoImage(): ?Image
    {
        return $this->logoImage;
    }

    public function setLogoImage(?Image $logoImage): self
    {
        $this->logoImage = $logoImage;

        return $this;
    }

    /**
     * @return Image|null
     */
    public function getBackgroundImage(): ?Image
    {
        return $this->backgroundImage;
    }

    /**
     * @param Image|null $backgroundImage
     */
    public function setBackgroundImage(?Image $backgroundImage): void
    {
        $this->backgroundImage = $backgroundImage;
    }

    public function getPageColor(): ?string
    {
        return $this->pageColor;
    }

    public function setPageColor(?string $pageColor): self
    {
        $this->pageColor = $pageColor;

        return $this;
    }

    public function getSecondaryPageColor(): ?string
    {
        return $this->secondaryPageColor;
    }

    public function setSecondaryPageColor(?string $secondaryPageColor): self
    {
        $this->secondaryPageColor = $secondaryPageColor;

        return $this;
    }

    public function getTextColor(): ?string
    {
        return $this->textColor;
    }

    public function setTextColor(?string $textColor): self
    {
        $this->textColor = $textColor;

        return $this;
    }

    public function getSecondaryTextColor(): ?string
    {
        return $this->secondaryTextColor;
    }

    public function setSecondaryTextColor(?string $secondaryTextColor): void
    {
        $this->secondaryTextColor = $secondaryTextColor;
    }

    public function getPhoneNumber(): ?int
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?int $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }

    public function setCompanyName(?string $companyName): self
    {
        $this->companyName = $companyName;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(?string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getCopyright(): ?string
    {
        return $this->copyright;
    }

    public function setCopyright(?string $copyright): self
    {
        $this->copyright = $copyright;

        return $this;
    }

    public function getProductCount(): ?int
    {
        return $this->productCount;
    }

    public function setProductCount(?int $productCount): self
    {
        $this->productCount = $productCount;

        return $this;
    }

    public function getProductTitle(): ?string
    {
        return $this->productTitle;
    }

    public function setProductTitle(?string $productTitle): self
    {
        $this->productTitle = $productTitle;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(?string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }
}