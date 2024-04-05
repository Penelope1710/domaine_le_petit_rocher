<?php
namespace App\Security;

use App\Entity\Event;
use App\Entity\EventCustomer;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;


class EventCustomerVoter extends Voter {
    const UNSUBSCRIBE = 'unsubscribe';

    private $security;
    public function __construct(Security $security) {
        $this->security = $security;
    }

    //support va nous indiquer si nous avons le droit de voter en fonction de l'objet et de l'action
    protected function supports(string $attribute, mixed $subject): bool
    {
        //si l'action n'est pas subscribe ou unsubscribe return false
        if (!in_array($attribute, [self::UNSUBSCRIBE])) {
            return false;
        }
        //si le subject n'est pas une instance de la classe EventCustomer, return false
        if (!$subject instanceof EventCustomer) {
            return false;
        }

        return true;
    }
    //fait le lien entre support et canDelete
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        //on récupère le User via le token
        $user = $token->getUser();

        //la méthode vérifie s'il s'agit d'une instance de la classe User(s'il est bien connecté)
        if (!$user instanceof User) {
            return false;
        }

        if ($attribute === self::UNSUBSCRIBE) {
            return $this->canUnsubscribe($subject, $user);
        }
        return true;
    }


    private function canUnsubscribe(EventCustomer $eventCustomer, User $user): bool
    {
        // Je peux me désinscrire si l'évènement est ouvert et si l'inscription appartient à l'utilisateur connecté (dénominateur commun : customer)
        $event = $eventCustomer->getEvent();
        if (
            $event->getStatus() === Event::OPENED_STATUS &&
            $eventCustomer->getCustomer() === $user->getCustomer()
        ) {
            return true;
        }

        return  false;
    }
}