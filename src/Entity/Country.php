<?php

namespace App\Entity;

use BusinessBundle\Entity\Opportunity;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;


/**
 * @ORM\Entity(repositoryClass="App\Repository\CountryRepository")
 */
class Country
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @JMS\Groups(groups={"pharmacy", "drug"})
     * @ORM\Column(type="string", length=20)
     */
    private $countryCode;

    /**
     * @JMS\Groups(groups={"pharmacy", "drug"})
     * @ORM\Column(type="string", length=200)
     */
    private $countryName;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    public function setCountryCode(string $countryCode): self
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    public function getCountryName(): ?string
    {
        return $this->countryName;
    }

    public function setCountryName(string $countryName): self
    {
        $this->countryName = $countryName;

        return $this;
    }

}
