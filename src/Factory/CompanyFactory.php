<?php

namespace App\Factory;

use App\Entity\Company;
use App\Repository\CompanyRepository;
use RuntimeException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Xvladqt\Faker\LoremFlickrProvider;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<Company>
 *
 * @method static Company|Proxy createOne(array $attributes = [])
 * @method static Company[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Company|Proxy find(object|array|mixed $criteria)
 * @method static Company|Proxy findOrCreate(array $attributes)
 * @method static Company|Proxy first(string $sortedField = 'id')
 * @method static Company|Proxy last(string $sortedField = 'id')
 * @method static Company|Proxy random(array $attributes = [])
 * @method static Company|Proxy randomOrCreate(array $attributes = [])
 * @method static Company[]|Proxy[] all()
 * @method static Company[]|Proxy[] findBy(array $attributes)
 * @method static Company[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Company[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static CompanyRepository|RepositoryProxy repository()
 * @method Company|Proxy create(array|callable $attributes = [])
 */
final class CompanyFactory extends ModelFactory
{
    private ParameterBagInterface $parameterBag;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        parent::__construct();

        $this->parameterBag = $parameterBag;
    }

    protected function getDefaults(): array
    {
        self::faker()->addProvider(new LoremFlickrProvider(self::faker()));
        $path = $this->parameterBag->get('kernel.project_dir') . '/public/uploads/';
        if (!file_exists($path) && !mkdir($path, 0777, true) && !is_dir($path)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $path));
        }
        return [
            'companyName' => self::faker()->company(),
            'companyLogo' => self::faker()->image(
                $path,
                150,
                150,
                ['companies, logos'],
                false
            ),
            'companyLogoBackgroundColor' => self::faker()->hexColor(),
            'companyTown' => self::faker()->city(),
            'companyWebsite' => self::faker()->url(),
            'contactLastname' => self::faker()->lastName(),
            'contactFirstname' => self::faker()->firstName(),
            'contactEmail' => self::faker()->companyEmail(),
            'contactPhone' => self::faker()->e164PhoneNumber(),
            'user' => UserFactory::new()
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this// ->afterInstantiate(function(Company $company): void {})
            ;
    }

    protected static function getClass(): string
    {
        return Company::class;
    }
}
