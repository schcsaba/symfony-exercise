<?php

namespace App\Tests;

use App\Factory\CandidateFactory;
use App\Factory\CompanyFactory;
use App\Factory\OfferFactory;
use App\Repository\CandidateRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CandidateTest extends WebTestCase
{
    public function testCandidate(): void
    {
        $client = static::createClient();

        $company = CompanyFactory::new()->create();
        $offer = OfferFactory::new()->create();
        $candidate = CandidateFactory::new()->create();
        $uploadedFile = new UploadedFile(
            __DIR__ . '/../public/uploads/' . $candidate->getCv(),
            'test_cv.pdf'
        );
        $candidateRepository = self::getContainer()->get(CandidateRepository::class);

        $client->request('GET', '/en/offer/' . $offer->getSlug());

        $client->submitForm('candidate_submit', [
            'candidate[firstname]' => $candidate->getFirstname(),
            'candidate[lastname]' => $candidate->getLastname(),
            'candidate[phone]' => $candidate->getPhone(),
            'candidate[email]' => $candidate->getEmail(),
            'candidate[cv]' => $uploadedFile,
        ]);

        $foundCandidate = $candidateRepository->find($candidate->getId());
        self::assertSame($candidate->getFirstname(), $foundCandidate->getFirstName());
        self::assertSame($candidate->getLastname(), $foundCandidate->getLastName());
        self::assertSame($candidate->getPhone(), $foundCandidate->getPhone());
        self::assertSame($candidate->getEmail(), $foundCandidate->getEmail());
        self::assertSame($candidate->getCv(), $foundCandidate->getCv());

        $imgPath = __DIR__ . '/../public/uploads/' . $company->getCompanyLogo();
        if (file_exists($imgPath)) {
            unlink($imgPath);
        }

        $cvPath = __DIR__ . '/../public/uploads/' . $candidate->getCv();
        if (file_exists($cvPath)) {
            unlink($cvPath);
        }

        $origCvPath = __DIR__ . '/../public/uploads/' . $uploadedFile->getClientOriginalName();
        if (file_exists($origCvPath)) {
            unlink($origCvPath);
        }

        self::assertResponseRedirects('/en/candidate/success');
    }
}
