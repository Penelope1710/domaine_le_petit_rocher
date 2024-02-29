<?php
namespace App\Security;

use App\Entity\Customer;
use App\Entity\Event;
use App\Entity\EventCustomer;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;


class EventVoter extends Voter {
    const DELETE = 'delete';
    const EDIT = 'edit';
    const SUBSCRIBE = 'subscribe';
    const UNSUBSCRIBE = 'unsubscribe';

    private $security;
    public function __construct(Security $security) {
        $this->security = $security;
    }

    //support va nous indiquer si nous avons le droit de voter en fonction de l'objet et de l'action
    protected function supports(string $attribute, mixed $subject): bool
    {
        //si l'action n'est pas delete OU edit, return false
        if (!in_array($attribute, [self::DELETE, self::EDIT, self::SUBSCRIBE, self::UNSUBSCRIBE])) {
            return false;
        }
        //si le subject n'est pas une instance de la classe Event, return false
        if (!$subject instanceof Event) {
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
        // si l'attribut est bien 'DELETE', la méthode canDelete est alors appelée
        if ($attribute === self::DELETE) {
            return $this->canDelete($subject, $user);
        }
        //si l'attribut est bien 'EDIT'...canEdit
        if ($attribute === self::EDIT) {
            return $this->canEdit($subject, $user);
        }

        if ($attribute === self::SUBSCRIBE) {
            return $this->canSubscribe($subject, $user);
        }

        if ($attribute === self::UNSUBSCRIBE) {
            return $this->canUnsubscribe($subject, $user);
        }
        return true;
    }

    private function canDelete(Event $event, User $user) {

        //l'utilisateur peut supprimer s'il a crée l'évènement
        if ($event->getCreatedBy() === $user) {
            return true;
        }
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        return false;
    }
    private function canEdit(Event $event, User $user) {

        //l'utilisateur peut modifier s'il a crée l'évènement
        if ($event->getCreatedBy() === $user) {
            return true;
        }

        return false;
    }

    private function canSubscribe(Event $event, User $user)
    {
        //l'évènement doit être ouvert
        if ($event->getStatus() !== Event::OPENED_STATUS) {
            return false;
        }
        //l'utilisateur ne doit pas être à l'initiative de l'évènement
        if ($event->getCreatedBy() === $user) {
            return false;
        }

        //l'utilisateur ne doit pas déjà être inscrit
        foreach ($event->getEventCustomer() as $eventCustomer) {
            if ($eventCustomer->getCustomer() === $user->getCustomer()) {
                return false;
            }
        }
        return true;
    }

    private function canUnsubscribe(Event $event, User $user)
    {
        if ($event->getStatus() !== Event::OPENED_STATUS) {
            return false;
        }

        $isUserSubscribed = false;
        //itération pour rechercher un eventCustomer(=inscrit)
        foreach ($event->getEventCustomer() as $eventCustomer) {

            //verifie si le user est bien un eventCustomer
            if ($eventCustomer->getCustomer() === $user->getCustomer()) {
                $isUserSubscribed = true;
            }
        }
        //si le user n'est pas inscrit, on ne peut pas le désinscrire
        if (!$isUserSubscribed) {
            return false;
        }

        return  true;
    }
}