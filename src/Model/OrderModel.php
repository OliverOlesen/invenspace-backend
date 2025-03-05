<?php

namespace App\Model;



use App\Entity\Address;
use App\Entity\Cart;
use App\Entity\Contact;
use App\Entity\DiscountCode;

class OrderModel extends AbstractModel
{
    public ?AddressModel $address = null;
    public ?AddressModel $billingAddress = null;
    public ?CartModel $cart = null;
    public ?ContactModel $contact = null;
    public ?DiscountCodeModel $discountCode = null;

    // TODO: Something is seriously missing int the Order entity
    // TODO: I am pretty sure that the Discount is not routed correctly
    // TODO: it's not accessible as a foreign key
}