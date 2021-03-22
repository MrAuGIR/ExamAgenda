<?php

namespace App\Security\Voter;

use App\Entity\User;
use App\Entity\Agenda;
use App\Entity\AgendaComment;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class AgendaVoter extends Voter
{
    const VIEW = "view";
    const EDIT = "edit";
    const CREATE = 'create';
    const DELETE = 'delete';

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attributes, $subject)
    {

        //Si l'attribut fait partie de ceux supportés et 
        //on vote seulement avec un objet de class Agenda
        return \in_array($attributes, [self::VIEW, self::EDIT, self::CREATE, self::DELETE]) && ($subject instanceof Agenda);
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            //si l'utilisateur n'est pas logger, deny access
            return false;
        }

        //Si je suis l'administrateur j'ai tous les droit
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        //si on est là, c'est que $subject est un objet Agenda
        /** @var Agenda $agenda */
        $comment = $subject;

        switch ($attribute) {
            case self::VIEW:
                return true;
            case self::CREATE:
                return true;
            case self::EDIT:
                return $agenda->getUser() == $user;
            case self::DELETE:
                return $agenda->getUser() == $user;
        }

        //retour false si l'utilisateur n'est pas autorisé
        return false;
    }
}
