<?php

namespace App\Entity;

use App\Model\AddressModel;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: 'App\Repository\AddressRepository')]
class Address extends AbstractEntity
{
    #[ORM\Column(type: 'string')]
    private string $street = "";

    #[ORM\Column(type: 'string')]
    private string $houseNumber = "";

    #[ORM\Column(type: 'string')]
    private string $city = "";

    #[ORM\Column(type: 'string')]
    private string $state = "";

    #[ORM\Column(type: 'string')]
    private string $postalCode = "";

    #[ORM\Column(type: 'string')]
    private string $country = "";

    #[ORM\OneToOne(targetEntity: Contact::class, mappedBy: 'address')]
    private ?Contact $contact = null;

    public function getStreet(): string
    {
        return $this->street;
    }

    public function setStreet(string $street): Address
    {
        $this->street = $street;
        return $this;
    }

    public function getHouseNumber(): string
    {
        return $this->houseNumber;
    }

    public function setHouseNumber(string $houseNumber): Address
    {
        $this->houseNumber = $houseNumber;
        return $this;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): Address
    {
        $this->city = $city;
        return $this;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function setState(string $state): Address
    {
        $this->state = $state;
        return $this;
    }

    public function getPostalCode(): string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): Address
    {
        $this->postalCode = $postalCode;
        return $this;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function setCountry(string $country): Address
    {
        $this->country = $country;
        return $this;
    }

    public function getContact(): ?Contact
    {
        return $this->contact;
    }

    public function setContact(?Contact $contact): Address
    {
        $this->contact = $contact;
        return $this;
    }

    // Custom functions. ----------------------
    public function getSingleLineAddress(): string
    {
        return $this->street . ' ' . $this->houseNumber . ' ' . $this->city . ' ' . $this->postalCode . ' ' . $this->country;
    }
}