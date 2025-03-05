<?php

namespace App\Entity;

use App\Model\ContactModel;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: 'App\Repository\ContactRepository')]
class Contact extends AbstractEntity
{
    // properties
    #[ORM\Column(type: 'string')]
    private string $firstName = '';
    #[ORM\Column(type: 'string', nullable: true)]
    private string $middleName = '';
    #[ORM\Column(type: 'string')]
    private string $lastName = '';
    #[ORM\Column(type: 'string')]
    private string $email = '';
    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $mobile = '';
    #[ORM\OneToOne(targetEntity: User::class, mappedBy: 'contact')]
    private ?User $user = null;
    #[ORM\OneToOne(targetEntity: Address::class, inversedBy: 'contact')]
    private ?Address $address = null;
    // constructor if needed

    // getters and setters
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): Contact
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getMiddleName(): string
    {
        return $this->middleName;
    }

    public function setMiddleName(string $middleName): Contact
    {
        $this->middleName = $middleName;
        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): Contact
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): Contact
    {
        $this->email = $email;
        return $this;
    }

    public function getMobile(): ?string
    {
        return $this->mobile;
    }

    public function setMobile(?string $mobile): Contact
    {
        $this->mobile = $mobile;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): Contact
    {
        $this->user = $user;
        return $this;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(?Address $address): Contact
    {
        $this->address = $address;
        return $this;
    }

    // Custom functions -----------------------
    public function getFullName(): string
    {
        return $this->firstName . ' ' . $this->middleName . ' ' . $this->lastName;
    }

    // TODO: Are we sure we want to do this in the entities?
    // TODO: -> Create a function public static (CreateFromModel)

    public static function createFromContactModel(ContactModel $contactModel): self
    {
        $contact = new self();
        $contact->setFirstName($contactModel->firstName);
        $contact->setLastName($contactModel->lastName);
        $contact->setEmail($contactModel->email);
        $contact->setMobile($contactModel->mobile);
        $contact->setUser(null);
        $contact->setAddress($contactModel->address);

        return $contact;
    }
}