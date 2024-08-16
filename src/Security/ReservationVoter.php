<?php

namespace App\Security;

use App\Entity\Reservation;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ReservationVoter extends Voter
{
    const EDIT = 'edit';
    const DELETE = 'delete';

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, [self::EDIT, self::DELETE])) {
            return false;
        }

        // only vote on `Post` objects
        if (!$subject instanceof Reservation) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        //on récupère le User via le token
        $user = $token->getUser();

        //la méthode vérifie s'il s'agit d'une instance de la classe User(s'il est bien connecté)
        if (!$user instanceof User) {
            return false;
        }
       $reservation = $subject;

        return match ($attribute) {
            self::EDIT => $this->canEdit($reservation, $user),
            self::DELETE => $this->canDelete($reservation, $user),
            default => throw new \LogicException('This code should not be reached!')

        };
    }

    private function canEdit(Reservation $reservation, User $user)
    {
        if ($this->security->isGranted('ROLE_ADMIN'))
        {
            return true;
        }
        return $user === $reservation->getCustomer()->getUser();
    }

    private function canDelete(Reservation $reservation, User $user)
    {
        if ($this->security->isGranted('ROLE_ADMIN'))
        {
            return true;
        }
        return $user === $reservation->getCustomer()->getUser();
    }
}