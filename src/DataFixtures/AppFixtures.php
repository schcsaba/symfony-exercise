<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Factory\CandidateFactory;
use App\Factory\CompanyFactory;
use App\Factory\OfferFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    /**
     * @param UserPasswordHasherInterface $passwordHasher
     */
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }


    public function load(ObjectManager $manager): void
    {
        UserFactory::createMany(5);
        $user = new User();
        $user->setUsername('admin');
        $pass = $this->passwordHasher->hashPassword($user, 'motdepasse');
        $user->setPassword($pass);
        $user->setRoles(['ROLE_ADMIN']);

        $manager->persist($user);

        CompanyFactory::createMany(5);
        OfferFactory::createMany(20);
        CandidateFactory::createMany(30);

        $manager->flush();
    }
}
