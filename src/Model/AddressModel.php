<?php

namespace App\Model;

use App\Entity\Address;

class AddressModel extends AbstractModel
{
    public string $street = '';
    public string $house_number = '';
    public string $city = '';
    public ?string $state = null;
    public string $postal_code = '';
    public string $country = '';

    public static function createFromAddress(Address $address): self {
        $model = new self();
        $model->uuid = $address->getUuid();
        $model->street = $address->getStreet();
        $model->house_number = $address->getHouseNumber();
        $model->city = $address->getCity();
        $model->state = $address->getState();
        $model->postal_code = $address->getPostalCode();
        $model->country = $address->getCountry();

        return $model;
    }
}