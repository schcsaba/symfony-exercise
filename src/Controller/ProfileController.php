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
use Symfony\Contracts\Translation\TranslatorInterface;

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
     * @Route("/{_locale<%app.supported_locales%>}/profile", name="profile_index")
     */
    public function index(): Response
    {
        return $this->render('profile/index.html.twig');
    }

    /**
     * @Route("/{_locale<%app.supported_locales%>}/profile/change-password", name="profile_password")
     */
    public function password(Request $request, UserPasswordHasherInterface $passwordHasher, TranslatorInterface $translator): Response
    {
        $notification = null;
        /** @var $user PasswordAuthenticatedUserInterface */
        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $current_password = $form->get('current_password')->getData();
            if ($passwordHasher->isPasswordValid($user, $current_password)) {
                $new_password = $form->get('new_password')->getData();
                $password = $passwordHasher->hashPassword($user, $new_password);
                $user->setPassword($password);
                $this->entityManager->flush();
                $notification = $translator->trans('account.password.updated');
            } else {
                $notification = $translator->trans('account.password.incorrect');
            }
        }

        return $this->render('profile/password.html.twig', [
            'form' => $form->createView(),
            'notification' => $notification
        ]);
    }
}
