<?php

namespace App\Security\Voter;

use App\Entity\Company;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class AdminCompanyVoter extends Voter
{
    public const EDIT = 'ADMIN_COMPANY_EDIT';
    private Security $security;

    /**
     * @param Security $security
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return $attribute === self::EDIT;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        if ($subject && !$subject instanceof Company) {
            throw new \LogicException('Subject is not an instance of Company?');
        }

        // ... (check conditions and return true to grant permission) ...
        if ($attribute === self::EDIT) {
            return !$subject || ($subject && $user === $subject->getUser()) || $this->security->isGranted('ROLE_ADMIN');
        }

        return false;
    }
}
