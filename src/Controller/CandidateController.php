<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CandidateController extends AbstractController
{
    /**
     * @Route("/{_locale<%app.supported_locales%>}/candidate/success", name="candidate_success")
     */
    public function success(): Response
    {
        return $this->render('candidate/index.html.twig');
    }
}
