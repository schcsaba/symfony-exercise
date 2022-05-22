<?php

namespace App\Controller;

use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

class ProfileController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/profile", name="profile_index")
     */
    public function index(): Response
    {
        return $this->render('profile/index.html.twig');
    }

    /**
     * @Route("/profile/change-password", name="profile_password")
     */
    public function password(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $notification = null;
        /** @var $user PasswordAuthenticatedUserInterface */
        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isSubmitted()) {
            $current_password = $form->get('current_password')->getData();
            if ($passwordHasher->isPasswordValid($user, $current_password)) {
                $new_password = $form->get('new_password')->getData();
                $password = $passwordHasher->hashPassword($user, $new_password);
                $user->setPassword($password);
                $this->entityManager->flush();
                $notification = 'Your password has been updated.';
            } else {
                $notification = 'The current password you typed is not correct.';
            }
        }

        return $this->render('profile/password.html.twig', [
            'form' => $form->createView(),
            'notification' => $notification
        ]);
    }
}
