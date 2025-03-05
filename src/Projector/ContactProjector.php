<?php

namespace App\Projector;

use App\Entity\Address;
use App\Entity\Contact;
use App\Entity\User;
use App\Event\Address\AddressCreated;
use App\Event\Address\AddressUpdated;
use App\Event\Contact\ContactCreated;
use App\Event\Contact\ContactUpdated;
use App\Model\AddressModel;
use App\Model\ContactModel;
use App\Projector\AddressProjector;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;

class ContactProjector extends AbstractProjector
{
    private ContactRepository $repository;
    public function __construct
    (
        EntityManagerInterface $entityManager,
        private readonly AddressProjector $addressProjector
    )
    {
        parent::__construct($entityManager);
        $this->repository = $entityManager->getRepository(Contact::class);
    }

    public function ApplyContactCreated(ContactCreated $event): Contact|false
    {
        /** @var ContactModel $model */
        $model = $event->getData();

        if ($model instanceof ContactModel) {
            $contact = (new Contact());
            $this->setCommonProperties($contact, $model, true);

            if ($model->address instanceof AddressModel)
            {
                $event = new AddressCreated($model->address);
                $address = $this->addressProjector->ApplyAddressCreated($event);
                $contact->setAddress($address);
            }

            $this->getEntityManager()->persist($contact);
            $this->getEntityManager()->flush();

            return $contact;
        }
        return false;
    }

    public function ApplyContactUpdated(ContactUpdated $event): Contact|false
    {
        /** @var ContactModel $model */
        $model = $event->getData();

        if($model instanceof ContactModel) {
            $contact = $this->repository->findOneByUuid($model->uuid);
            if (!$contact) {
                return false;
            }

            $this->setCommonProperties($contact, $model);

            if ($model->address instanceof AddressModel) {
                $event = new AddressUpdated($model->address);
                $address = $this->addressProjector->ApplyAddressUpdated($event);
                $contact->setAddress($address);
            }

            $this->getEntityManager()->flush();

            return $contact;
        }
        return false;
    }

    public function setCommonProperties(Contact $contact, ContactModel $model, bool $isCreate = false): void
    {
        if ($isCreate) {
            $contact->setUuid(Uuid::uuid4()->toString());
        }
        $contact->setFirstName($model->firstName);
        $contact->setMiddleName($model->middleName);
        $contact->setLastName($model->lastName);
        $contact->setEmail($model->email);
        $contact->setMobile($model->mobile);
    }
}