<?php

namespace App\Controller;

use App\Entity\Candidate;
use App\Entity\Offer;
use App\Form\CandidateType;
use App\Repository\CandidateRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OfferController extends AbstractController
{
    private ParameterBagInterface $parameterBag;

    /**
     * @param ParameterBagInterface $parameterBag
     */
    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
    }


    /**
     * @Route("/{_locale<%app.supported_locales%>}/offer/{slug}", name="offer_show")
     * @throws Exception
     */
    public function show(Offer $offer, Request $request, CandidateRepository $candidateRepository): Response
    {
        $candidate = new Candidate();
        $form = $this->createForm(CandidateType::class, $candidate);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $candidate = $form->getData();
            $candidate->setOffer($offer);
            $filename = $this->getParameter('kernel.environment') === 'test' ? 'test_cv' : uuid_create();
            $directory = $this->parameterBag->get('kernel.project_dir') . '/public/uploads/';
            /** @var UploadedFile $file */
            $file = $form->get('cv')->getData();
            $extension = $file->guessExtension();
            if (!$extension) {
                $extension = 'bin';
            }
            try {
                $file->move($directory, $filename . '.' . $extension);
            } catch (FileException $e) {
                echo 'Exception Found - ' . $e->getMessage() . '<br/>';
            }
            $candidate->setCv($filename . '.' . $extension);
            $candidateRepository->add($candidate, true);
            return $this->redirectToRoute('candidate_success');
        }
        return $this->render('offer/show.html.twig', [
            'offer' => $offer,
            'form' => $form->createView()
        ]);
    }
}
