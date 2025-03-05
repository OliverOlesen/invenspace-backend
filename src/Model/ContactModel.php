<?php

namespace App\Model;

use App\Entity\Address;
use App\Entity\Contact;
use App\Entity\User;

class ContactModel extends AbstractModel
{
    public string $firstName = '';
    public ?string $middleName = null;
    public string $lastName = '';
    public string $email = '';
    public string $mobile = '';

    public ?AddressModel $address = null;

    public static function createFromContact(Contact $contact): self
    {
        $model = new self();
        $model->uuid = $contact->getUuid();
        $model->firstName = $contact->getFirstName();
        $model->middleName = $contact->getMiddleName();
        $model->lastName = $contact->getLastName();
        $model->email = $contact->getEmail();
        $model->mobile = $contact->getMobile();
        if ($contact->getAddress() instanceof Address) {
            $model->address = AddressModel::createFromAddress($contact->getAddress());
        }

        return $model;
    }
}