<?php

namespace App\Factory;

use App\Entity\Offer;
use App\Repository\OfferRepository;
use DateTimeImmutable;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<Offer>
 *
 * @method static Offer|Proxy createOne(array $attributes = [])
 * @method static Offer[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Offer|Proxy find(object|array|mixed $criteria)
 * @method static Offer|Proxy findOrCreate(array $attributes)
 * @method static Offer|Proxy first(string $sortedField = 'id')
 * @method static Offer|Proxy last(string $sortedField = 'id')
 * @method static Offer|Proxy random(array $attributes = [])
 * @method static Offer|Proxy randomOrCreate(array $attributes = [])
 * @method static Offer[]|Proxy[] all()
 * @method static Offer[]|Proxy[] findBy(array $attributes)
 * @method static Offer[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Offer[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static OfferRepository|RepositoryProxy repository()
 * @method Offer|Proxy create(array|callable $attributes = [])
 */
final class OfferFactory extends ModelFactory
{
    protected function getDefaults(): array
    {
        $typeOfContract = self::faker()->randomElement(['Full Time', 'Part Time', 'Freelance']);
        $isFullTime = $typeOfContract === 'Full Time';
        return [
            'title' => self::faker()->jobTitle(),
            'typeOfContract' => $typeOfContract,
            'description' => self::faker()->paragraph(5),
            'profileDescription' => self::faker()->paragraph(4),
            'competences' => self::faker()->sentences(self::faker()->numberBetween(2, 8)),
            'positionDescription' => self::faker()->paragraph(4),
            'positionMissions' => self::faker()->sentences(self::faker()->numberBetween(2, 8)),
            'company' => CompanyFactory::random(),
            'isFullTime' => $isFullTime
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
             //->afterInstantiate(function(Offer $offer): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Offer::class;
    }
}
