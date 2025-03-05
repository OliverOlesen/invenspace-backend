<?php

namespace App\Model;

use App\Constants\UserRoleEnum;
use App\Entity\Contact;
use App\Entity\User;

class UserModel extends AbstractModel
{
    public ?string $username = null;
    public ?string $password = null;
    public ?string $email = null;
    public array $roles = [];
    public ?ContactModel $contact = null;

    // I can have a user without contact,
    // so I create a user without a contact first, and then I create the contact in the contact creator and set the user on the contact
    // if it's not nullable then you need to create contact first
    // if both are needed then you need to call the projector
    // from class B in class A and only flush to database after both are created and linked
    public static function createFromUser(User $user): self
    {
        $model = new self();
        $model->uuid = $user->getUuid();
        $model->username = $user->getUsername();
        $model->password = $user->getPassword();
        $model->email = $user->getEmail();
        $model->roles = $user->getRoles();

        if ($user->getContact() instanceof Contact) {
            $model->contact = ContactModel::createFromContact($user->getContact());
        }

        return $model;
    }

    public static function createNewUser(): self
    {
        $model = new self();
        $model->roles = [UserRoleEnum::ROLE_USER];

        return $model;
    }
}