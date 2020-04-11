<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * @ORM\Entity(repositoryClass="App\Repository\DrugRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=true)
 * @ORM\HasLifecycleCallbacks()
 */
class Drug
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @JMS\Groups(groups={"drug"})
     * @ORM\Column(type="string", length=100)
     */
    private $drugName;

    /**
     * @JMS\Groups(groups={"drug"})
     * @ORM\Column(type="string", length=100)
     */
    private $bareCode;

    /**
     * @JMS\Groups(groups={"drug"})
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @JMS\Groups(groups={"drug"})
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @JMS\Groups(groups={"create"})
     * @ORM\Column(type="datetime")
     * @JMS\Type("DateTime<'Y-m-d H:i:s'>")
     */
    private $createdAt;

    /**
     * @JMS\Groups(groups={"update"})
     * @ORM\Column(type="datetime", nullable=true)
     * @JMS\Type("DateTime<'Y-m-d H:i:s'>")
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isDeleted;

    /**
     * @JMS\Groups(groups={"drug"})
     * @ORM\Column(type="datetime")
     * @JMS\Type("DateTime<'Y-m-d H:i:s'>")
     */
    private $expiredAt;

    /**
     * @JMS\Groups(groups={"drug"})
     * @ORM\ManyToMany(targetEntity=Pharmacy::class, cascade={"persist"} )
     * @ORM\JoinTable(name="oc_pharmacy_drgus")
     */
    private $pharmacy;

    public function __construct()
    {
        $this->createdAt         = new \Datetime();
        $this->expiredAt         = new \Datetime();
        $this->pharmacy          = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }


    public function getDrugName(): ?string
    {
        return $this->drugName;
    }

    public function setDrugName(string $drugName): self
    {
        $this->drugName = $drugName;

        return $this;
    }

    public function getBareCode(): ?string
    {
        return $this->bareCode;
    }

    public function setBareCode(string $bareCode): self
    {
        $this->bareCode = $bareCode;

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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @ORM\PrePersist
     * @return $this
     * @throws \Exception
     */
    public function setCreatedAt()
    {
        $this->createdAt = new \DateTime();
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @ORM\PreUpdate
     * @return $this
     * @throws \Exception
     */
    public function setUpdatedAt()
    {
        $this->updatedAt = new \DateTime();
        return $this;
    }

    public function getDeletedAt(): ?\DateTimeInterface
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(\DateTimeInterface $deletedAt): self
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    public function getIsDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(bool $isDeleted): self
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    public function getExpiredAt(): ?\DateTimeInterface
    {
        return $this->expiredAt;
    }

    public function setExpiredAt(\DateTimeInterface $expiredAt): self
    {
        $this->expiredAt = $expiredAt;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPharmacy()
    {
        return $this->pharmacy;
    }

    /**
     * @param Pharmacy $pharmacy
     */
    public function addPharmacy(Pharmacy $pharmacy)
    {
        $this->pharmacy[] = $pharmacy;

        return $this;
    }

    /**
     * @param Pharmacy $pharmacy
     */
    public function removePharmacy(Pharmacy $pharmacy)
    {
        $this->pharmacy->removeElement($pharmacy);

        return $this;
    }

}