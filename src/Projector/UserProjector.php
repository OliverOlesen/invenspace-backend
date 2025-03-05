<?php

namespace App\Projector;

use App\Entity\User;
use App\Event\Contact\ContactCreated;
use App\Event\Contact\ContactUpdated;
use App\Event\User\UserCreated;
use App\Event\User\UserDeleted;
use App\Event\User\UserPasswordReset;
use App\Event\User\UserUpdated;
use App\Model\ContactModel;
use App\Model\UserModel;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserProjector extends AbstractProjector
{
    private UserRepository $repository;
    public function __construct
    (
        EntityManagerInterface $entityManager,
        private readonly ContactProjector $contactProjector,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly JWTTokenManagerInterface $JWTTokenManager,
        private readonly MailerInterface $mailer,
    )
    {
        parent::__construct($entityManager);
        $this->repository = $entityManager->getRepository(User::class);
    }

    public function ApplyUserCreated(UserCreated $event): User|false
    {
        /** @var UserModel $model */
        $model = $event->getData();

        if ($model instanceof UserModel) {
            $user = (new User());
            $hashedPassword = $this->passwordHasher->hashPassword($user, $model->password);
            $user->setPassword($hashedPassword);

            $this->setCommonProperties($user, $model, true);

            if ($model->contact instanceof ContactModel) {
                $event = new ContactCreated($model->contact);
                $contact = $this->contactProjector->ApplyContactCreated($event);
                $user->setContact($contact);
            }

            $this->getEntityManager()->persist($user);
            $this->getEntityManager()->flush();

            return $user;
        }

        return false;
    }

    public function ApplyUserUpdated(UserUpdated $event): User|false
    {
        /** @var UserModel $model */
        $model = $event->getData();

        if($model instanceof UserModel) {
            $user = $this->repository->findOneByUuid($model->uuid);
            if (!$user) {
                return false;
            }

            $hashedPassword = $this->passwordHasher->hashPassword($user, $model->password);
            $user->setPassword($hashedPassword);

            $this->setCommonProperties($user, $model);

            if ($model->contact instanceof ContactModel) {
                $event = new ContactUpdated($model->contact);
                $contact = $this->contactProjector->ApplyContactUpdated($event);
                $user->setContact($contact);
            }

            $this->getEntityManager()->flush();

            return $user;
        }
        return false;
    }

    public function ApplyUserDeleted(UserDeleted $event): bool
    {
        /** @var UserModel $model */
        $model = $event->getData();

        if ($model instanceof UserModel) {
            $user = $this->repository->findOneByUuid($model->uuid);
            if (!$user) {
                return false;
            }

            $this->getEntityManager()->remove($user);
            $this->getEntityManager()->flush();

            return true;
        }

        return false;
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function ApplyPasswordResetRequest(UserPasswordReset $event): bool
    {
        /** @var UserModel $model */
        $model = $event->getData();

        if ($model instanceof UserModel) {
            $user = $this->repository->findOneByUuid($model->uuid);
            if (!$user) {
                return false;
            }

            // Assuming the User entity has a getUuid() method
            $customPayload = [
                'uuid' => $user->getUuid(),
                'exp' => (new \DateTime('+15 minutes'))->getTimestamp(), // Token valid for 15 minutes
            ];

            $token = $this->JWTTokenManager->createFromPayload($user, $customPayload);

            // Create the email object
            $email = (new Email())
                ->from('support@invenspace.com')  // Sender email
                ->to($model->email) // Recipient email
                ->subject('Password Reset Request')
                ->text('This is to reset your password') // Plain text body
                ->html('<p>Click here to reset your password: </p>' . '<a href="http://localhost:5173/user/resetPassword/' . $token . '" target="_blank">Reset Password</a>'); // HTML body (optional)

            // Send the email
            $this->mailer->send($email);

            // Generate the token
            return true;
        }

        return false;
    }
    public function GetUserFromAuthenticationToken(string $token): User|null
    {
        if (empty($token)) {
            return null;
        }

        try {
            $payload = $this->JWTTokenManager->parse($token);

            if (!isset($payload['uuid'])) {
                return null;
            }

            // Fetch the user by UUID
            return $this->repository->findOneByUuid($payload['uuid']);
        } catch (JWTDecodeFailureException $e) {
            // Handle the exception if the token is invalid
            return null;
        }
    }

    public function SetNewUserPassword(UserPasswordReset $event): bool
    {
        /** @var UserModel $model */
        $model = $event->getData();

        if ($model instanceof UserModel) {
            $user = $this->repository->findOneByUuid($model->uuid);
            if (!$user) {
                return false;
            }

            $hashedPassword = $this->passwordHasher->hashPassword($user, $model->password);
            $user->setPassword($hashedPassword);

            $this->getEntityManager()->flush();

            return true;
        }

        return false;
    }

    public function setCommonProperties(User $user, UserModel $model, bool $isCreate = false): void
    {
        if ($isCreate) {
            $user->setUuid(Uuid::uuid4()->toString());
        }
        $user->setUsername($model->username);
        $user->setEmail($model->email);
        $user->setRoles($model->roles);
    }
}