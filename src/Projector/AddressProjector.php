<?php

namespace App\Projector;

use App\Entity\Address;
use App\Event\Address\AddressCreated;
use App\Event\Address\AddressUpdated;
use App\Model\AddressModel;
use App\Repository\AddressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;

class AddressProjector extends AbstractProjector
{
    private AddressRepository $repository;
    public function __construct
    (
        EntityManagerInterface $entityManager,
    )
    {
        parent::__construct($entityManager);
        $this->repository = $entityManager->getRepository(Address::class);
    }

    public function ApplyAddressCreated(AddressCreated $event): Address|false
    {
        /** @var AddressModel $model */
        $model = $event->getData();

        if ($model instanceof AddressModel) {
            $address = (new Address());
            $this->setCommonProperties($address, $model, true);

            $this->getEntityManager()->persist($address);
            $this->getEntityManager()->flush();

            return $address;
        }
        return false;
    }

    public function ApplyAddressUpdated(AddressUpdated $event): Address|false
    {
        /** @var AddressModel $model */
        $model = $event->getData();

        if($model instanceof AddressModel) {
            /** @var Address $address */
            $address = $this->repository->findOneByUuid($model->uuid);

            if (!$address) {
                return false;
            }

            $this->setCommonProperties($address, $model);

            $this->getEntityManager()->flush();

            return $address;
        }
        return false;
    }

    public function setCommonProperties(Address $address, AddressModel $model, bool $isCreate = false): void
    {
        if ($isCreate) {
            $address->setUuid(Uuid::uuid4()->toString());
        }

        $address->setStreet($model->street);
        $address->setHouseNumber($model->house_number);
        $address->setCity($model->city);
        $address->setState($model->state);
        $address->setPostalCode($model->postal_code);
        $address->setCountry($model->country);
    }
}