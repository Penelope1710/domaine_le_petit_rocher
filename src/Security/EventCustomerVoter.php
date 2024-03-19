<?php
namespace App\Security;

use App\Entity\Customer;
use App\Entity\Event;
use App\Entity\EventCustomer;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;


class EventCustomerVoter extends Voter {
    const SUBSCRIBE = 'subscribe';
    const UNSUBSCRIBE = 'unsubscribe';

    private $security;
    public function __construct(Security $security) {
        $this->security = $security;
    }

    //support va nous indiquer si nous avons le droit de voter en fonction de l'objet et de l'action
    protected function supports(string $attribute, mixed $subject): bool
    {
        //si l'action n'est pas subscribe ou unsubscribe return false
        if (!in_array($attribute, [self::SUBSCRIBE, self::UNSUBSCRIBE])) {
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
        // si l'attribut est bien 'SUBSCRIBE', la méthode canSubscribe est alors appelée
        if ($attribute === self::SUBSCRIBE) {
            return $this->canSubscribe($subject, $user);
        }

        if ($attribute === self::UNSUBSCRIBE) {
            return $this->canUnsubscribe($subject, $user);
        }
        return true;
    }

    private function canSubscribe(EventCustomer $eventCustomer, Event $event, User $user)
    {

        //l'utilisateur ne doit pas déjà être inscrit
        $isSubscribe = false;
        foreach ($event->getEventCustomer() as $eventCustomer) {
            if ($eventCustomer->getCustomer() === $user->getCustomer()) {
                $isSubscribe = true;
            }
        }
        // si je ne suis pas inscrite et que l'évènement est ouvert alors je peux m'inscrire
        if ($isSubscribe === false && $event->getStatus() === Event::OPENED_STATUS) {
            return true;
        }
        return false;
    }

    private function canUnsubscribe(EventCustomer $eventCustomer, Event $event, User $user)
    {
        $isUnsubscribe = false;
        if ($event->getStatus() === Event::OPENED_STATUS) {
            return true;
        }

        //itération pour rechercher un eventCustomer(=inscrit)
        foreach ($event->getEventCustomer() as $eventCustomer) {
            //verifie si le user est bien l'eventCustomer
            if ($eventCustomer->getCustomer() === $user->getCustomer()) {
                return true;
            }
        }

        return  false;
    }
}