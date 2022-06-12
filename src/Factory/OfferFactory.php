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
    public function __construct()
    {
        parent::__construct();
    }

    protected function getDefaults(): array
    {
        return [
            'title' => self::faker()->jobTitle(),
            'typeOfContract' => self::faker()->randomElement(['Full Time', 'Part Time', 'Freelance']),
            'description' => self::faker()->paragraph(5),
            'createdAt' => DateTimeImmutable::createFromMutable(self::faker()->dateTimeThisMonth()),
            'profileDescription' => self::faker()->paragraph(4),
            'competences' => self::faker()->sentences(self::faker()->numberBetween(2, 8)),
            'positionDescription' => self::faker()->paragraph(4),
            'positionMissions' => self::faker()->sentences(self::faker()->numberBetween(2, 8)),
            'company' => CompanyFactory::random()
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
             ->afterInstantiate(function(Offer $offer): void {
                 if (!$offer->getSlug()) {
                     $slugger = new AsciiSlugger();
                     $offer->setSlug($slugger->slug($offer->getTitle()));
                 }
             })
        ;
    }

    protected static function getClass(): string
    {
        return Offer::class;
    }
}
