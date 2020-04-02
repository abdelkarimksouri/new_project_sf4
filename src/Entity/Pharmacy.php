<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Gedmo\Mapping\Annotation as Gedmo;
use App\Entity\User;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PharmacyRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=true)
 * @ORM\HasLifecycleCallbacks()
 */
class Pharmacy
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @JMS\Groups(groups={"pharmacy", "drug"})
     * @ORM\Column(type="string", length=255)
     */
    private $generatedName;

    /**
     * @JMS\Groups(groups={"pharmacy"})
     * @ORM\Column(type="string", length=255)
     */
    private $uid;

    /**
     * @JMS\Groups(groups={"pharmacy"})
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @JMS\Groups(groups={"pharmacy", "drug"})
     * @ORM\Column(type="boolean")
     */
    private $isNight;

    /**
     * @JMS\Groups(groups={"pharmacy", "drug"})
     * @ORM\Column(type="boolean")
     */
    private $isHoliday;

    /**
     * @JMS\Groups(groups={"pharmacy"})
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @JMS\Groups(groups={"pharmacy"})
     * @ORM\Column(type="datetime", nullable=true)
     * @JMS\Type("DateTime<'Y-m-d H:i:s'>")
     */
    private $updatedAt;

    /**
     * @JMS\Groups(groups={"pharmacy", "drug", "address"})
     * @ORM\OneToOne(targetEntity=Address::class, cascade={"persist","remove"})
     */
    protected $address;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @ORM\OneToMany(targetEntity=Drug::class, cascade={"persist","remove"}, mappedBy="pharmacy")
     */
    private $drugs;

    /**
     * @JMS\Groups(groups={"pharmacy"})
     * @ORM\OneToOne(targetEntity=User::class, cascade={"persist","remove"})
     */
    private $user;

    public function __construct()
    {
        $this->drugs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGeneratedName(): ?string
    {
        return $this->generatedName;
    }

    public function setGeneratedName(string $generatedName): self
    {
        $this->generatedName = $generatedName;

        return $this;
    }

    public function getUid(): ?string
    {
        return $this->uid;
    }

    /**
     * @ORM\PrePersist
     * @return $this
     * @throws \Exception
     */
    public function setUid()
    {
        $random = mt_rand(5, 15);
        $this->uid = sha1($random);

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getIsNight(): ?bool
    {
        return $this->isNight;
    }

    public function setIsNight(bool $isNight): self
    {
        $this->isNight = $isNight;

        return $this;
    }

    public function getIsHoliday(): ?bool
    {
        return $this->isHoliday;
    }

    public function setIsHoliday(bool $isHoliday): self
    {
        $this->isHoliday = $isHoliday;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
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

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     * @return Pharmacy
     */
    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * @param mixed $deletedAt
     * @return Pharmacy
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser(\App\Entity\User $user)
    {
        $this->user = $user;
        return $this;
    }
}
