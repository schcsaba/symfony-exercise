<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Factory\CandidateFactory;
use App\Factory\CompanyFactory;
use App\Factory\OfferFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;
    private ParameterBagInterface $parameterBag;

    /**
     * @param UserPasswordHasherInterface $passwordHasher
     */
    public function __construct(UserPasswordHasherInterface $passwordHasher, ParameterBagInterface $parameterBag)
    {
        $this->passwordHasher = $passwordHasher;
        $this->parameterBag = $parameterBag;
    }

    protected function rmrf($dir) {
        foreach (glob($dir) as $file) {
            if (is_dir($file)) {
                $this->rmrf("$file/*");
                rmdir($file);
            } else {
                unlink($file);
            }
        }
    }


    public function load(ObjectManager $manager): void
    {
        $this->rmrf($this->parameterBag->get('kernel.project_dir') . '/public/uploads/');
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
