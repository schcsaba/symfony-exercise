<?php

namespace App\Security\Voter;

use App\Entity\Candidate;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class AdminCandidateVoter extends Voter
{
    public const VIEW = 'ADMIN_CANDIDATE_VIEW';
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
        return $attribute === self::VIEW;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        if ($subject && !$subject instanceof Candidate) {
            throw new \LogicException('Subject is not an instance of Candidate?');
        }

        // ... (check conditions and return true to grant permission) ...
        if ($attribute === self::VIEW) {
            return !$subject || ($subject && $user === $subject->getOffer()->getCompany()->getUser()) || $this->security->isGranted('ROLE_ADMIN');
        }

        return false;
    }
}