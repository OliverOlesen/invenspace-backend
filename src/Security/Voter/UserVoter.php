<?php

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class UserVoter extends Voter
{
    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, ['ROLE_USER', 'ROLE_ADMIN', 'ROLE_SUPER_ADMIN']);
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // you may want to use === here instead of in_array
        if ($attribute === 'ROLE_USER' && in_array($attribute, $user->getRoles())) {
            return true;
        }

        if ($attribute === 'ROLE_ADMIN' && in_array($attribute, $user->getRoles())) {
            return true;
        }

        if ($attribute === 'ROLE_SUPER_ADMIN' && in_array($attribute, $user->getRoles())) {
            return true;
        }

        return false;
    }
}