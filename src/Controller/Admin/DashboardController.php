<?php

namespace App\Controller\Admin;

use App\Entity\Company;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/{_locale<%app.supported_locales%>}/admin", name="admin")
     */
    public function index(): Response
    {
        return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('DevJobs');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Users', 'fas fa-user', User::class)
            ->setController(UserCrudController::class)
            ->setPermission('ROLE_ADMIN');
        yield MenuItem::linkToCrud('User passwords', 'fas fa-key', User::class)
            ->setController(UserPasswordCrudController::class)
            ->setPermission('ROLE_ADMIN');
        yield MenuItem::linkToCrud('Companies', 'fas fa-building', Company::class);
    }
}
