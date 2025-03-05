<?php

namespace App\Entity;

use App\Model\UserModel;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: 'App\Repository\UserRepository')]
class User extends AbstractEntity implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Column(type: 'string')]
    private string $username;

    #[ORM\Column(type: 'string')]
    private string $password;

    #[ORM\Column(type: 'string')]
    private string $email;

    #[ORM\Column(type: 'json')]
    private array $roles = [];
    #[ORM\OneToOne(targetEntity: Contact::class, inversedBy: 'user')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Contact $contact = null;


    public function __construct()
    {
        parent::__construct();
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): User
    {
        $this->username = $username;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): User
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): User
    {
        $this->password = $password;
        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): User
    {
        $this->roles = $roles;
        return $this;
    }

    public function getContact(): ?Contact
    {
        return $this->contact;
    }


    public function setContact(Contact $contact): User
    {
        $this->contact = $contact;
        return $this;
    }

    public function getUserIdentifier(): string
    {
        return $this->username;
    }

    public function eraseCredentials(): void
    {
        // If you store any temporary sensitive data on the user, clear it here
        // Example: $this->plainPassword = null;
    }

    // ------------------------------------------------------------> Custom methods

    public static function createFromUserModel(UserModel $userModel): self
    {
        $user = new self();
        $user->setUsername($userModel->username);
        $user->setPassword($userModel->password);
        $user->setRoles($userModel->roles);

        return $user;
    }

    public function getChoices(): array {
        $choices = [];
        foreach ($this->roles as $id => $role)
        {
            $choices[] = $role;
        }

        return $choices;
    }

}