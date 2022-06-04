<?php

namespace App\Controller\Admin;

use App\Entity\Company;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatableMessage;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/{_locale<%app.supported_locales%>}/admin", name="admin")
     */
    public function index(): Response
    {
        return $this->render('admin/content.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('DevJobs');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard(new TranslatableMessage('easyadmin.dashboard'), 'fa fa-home');
        $message = $this->isGranted('ROLE_ADMIN') ? 'easyadmin.users' : 'easyadmin.myprofile';
        yield MenuItem::linkToCrud(new TranslatableMessage($message), 'fas fa-user', User::class)
            ->setController(UserCrudController::class);
        $message = $this->isGranted('ROLE_ADMIN') ? 'easyadmin.users.passwords' : 'easyadmin.mypassword';
        yield MenuItem::linkToCrud(new TranslatableMessage($message), 'fas fa-key', User::class)
            ->setController(UserPasswordCrudController::class);
        $message = $this->isGranted('ROLE_ADMIN') ? 'easyadmin.companies' : 'easyadmin.mycompany';
        yield MenuItem::linkToCrud(new TranslatableMessage($message), 'fas fa-building', Company::class);
    }
}
