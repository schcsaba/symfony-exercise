<?php

namespace App\EventSubscriber;

use App\Entity\Company;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityDeletedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Based on https://grafikart.fr/forum/33951
 */
class EasyAdminSubscriber implements EventSubscriberInterface
{

    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $passwordHasher;
    private ParameterBagInterface $parameterBag;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher, ParameterBagInterface $parameterBag)
    {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
        $this->parameterBag = $parameterBag;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            BeforeEntityPersistedEvent::class => ['addUser'],
            AfterEntityDeletedEvent::class => ['deleteCompany']
        ];
    }

    public function addUser(BeforeEntityPersistedEvent $event): void
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof User)) {
            return;
        }
        $this->setPassword($entity);
    }

    /**
     * @param User $entity
     */
    public function setPassword(User $entity): void
    {
        $pass = $entity->getPassword();
        $new_pass = $this->passwordHasher->hashPassword($entity, $pass);

        $entity->setPassword($new_pass);
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    public function deleteCompany(AfterEntityDeletedEvent $event): void
    {
        $entity = $event->getEntityInstance();
        if (!($entity instanceof Company)) {
            return;
        }
        $imgpath = $this->parameterBag->get('kernel.project_dir') . '/public/uploads/' . $entity->getCompanyLogo();
        if (file_exists($imgpath)) {
            unlink($imgpath);
        }
    }

}