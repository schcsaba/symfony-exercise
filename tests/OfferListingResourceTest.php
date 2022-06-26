<?php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Factory\CompanyFactory;
use App\Factory\OfferFactory;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class OfferListingResourceTest extends ApiTestCase
{
    /**
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function testGetOfferListingCollection(): void
    {
        $client = self::createClient();

        $company = CompanyFactory::new()->create();
        $factory = OfferFactory::new();
        $factory->create();
        $offer2 = $factory->create([
            'title' => 'offer2',
            'typeOfContract' => 'Part Time',
            'company' => $company
        ]);

        $imgPath = __DIR__ . '/../public/uploads/' . $company->getCompanyLogo();
        if (file_exists($imgPath)) {
            unlink($imgPath);
        }

        $client->request('GET', '/api/offers');
        self::assertJsonContains(['hydra:totalItems' => 2]);
        self::assertJsonContains(['hydra:member' => [
            1 => [
                '@id' => '/api/offers/' . $offer2->getId(),
                '@type' => 'Offer',
                'title' => 'offer2',
                'typeOfContract' => 'Part Time',
                'company' => [
                    '@type' => 'Company',
                    'companyName' => $company->getCompanyName(),
                    'companyLogo' => $company->getCompanyLogo(),
                    'companyLogoBackgroundColor' => $company->getCompanyLogoBackgroundColor(),
                    'companyTown' => $company->getCompanyTown()
                ]
            ]
        ]]);
    }

    /**
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function testGetOfferListingItem(): void
    {
        $client = self::createClient();
        $company = CompanyFactory::new()->create();
        $offer = OfferFactory::new()->create();

        $imgPath = __DIR__ . '/../public/uploads/' . $company->getCompanyLogo();
        if (file_exists($imgPath)) {
            unlink($imgPath);
        }

        $client->request('GET', '/api/offers/' . $offer->getId());
        self::assertJsonContains([
                '@id' => '/api/offers/' . $offer->getId(),
                '@type' => 'Offer',
                'title' => $offer->getTitle(),
                'typeOfContract' => $offer->getTypeOfContract(),
                'description' => $offer->getDescription(),
                'profileDescription' => $offer->getProfileDescription(),
                'competences' => $offer->getCompetences(),
                'positionDescription' => $offer->getPositionDescription(),
                'positionMissions' => $offer->getPositionMissions(),
                'slug' => $offer->getSlug(),
                'company' => [
                    '@type' => 'Company',
                    'companyName' => $company->getCompanyName(),
                    'companyLogo' => $company->getCompanyLogo(),
                    'companyLogoBackgroundColor' => $company->getCompanyLogoBackgroundColor(),
                    'companyTown' => $company->getCompanyTown(),
                    'companyWebsite' => $company->getCompanyWebsite(),
                    'contactLastname' => $company->getContactLastname(),
                    'contactFirstname' => $company->getContactFirstname(),
                    'contactEmail' => $company->getContactEmail(),
                    'contactPhone' => $company->getContactPhone()
                ]
            ]
        );

        $client->request('GET', '/api/offers/' . ($offer->getId() + 1));
        self::assertResponseStatusCodeSame(404);

        $client->request('POST', '/api/offers');
        self::assertResponseStatusCodeSame(405);
        $client->request('PUT', '/api/offers');
        self::assertResponseStatusCodeSame(405);
        $client->request('PATCH', '/api/offers');
        self::assertResponseStatusCodeSame(405);
        $client->request('DELETE', '/api/offers');
        self::assertResponseStatusCodeSame(405);
        $client->request('PUT', '/api/offers/' . $offer->getId());
        self::assertResponseStatusCodeSame(405);
        $client->request('PATCH', '/api/offers/' . $offer->getId());
        self::assertResponseStatusCodeSame(405);
        $client->request('DELETE', '/api/offers/' . $offer->getId());
        self::assertResponseStatusCodeSame(405);
    }
}