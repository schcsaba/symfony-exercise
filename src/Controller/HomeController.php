<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/{_locale<%app.supported_locales%>}/", name="home_index")
     */
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }

    /**
     * @Route("/", name="home_index_nolocale")
     */
    public function indexNoLocale(): Response
    {
        return $this->redirectToRoute('home_index', ['_locale' => 'fr']);
    }
}
