<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class OffresVoter extends Voter
{
    public const VIEW = 'OFFRE_VIEW';

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::VIEW])
            && $subject instanceof \App\Entity\Offres;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {

       

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
          

            case self::VIEW:

                return $subject->isPublished();
        }

        return false;
    }
}
