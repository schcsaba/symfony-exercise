<?php

namespace App\Factory;

use App\Entity\Company;
use App\Repository\CompanyRepository;
use RuntimeException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
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
    private $tmpPath;
    private $tmpFile;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        parent::__construct();

        $this->parameterBag = $parameterBag;
        $this->tmpPath = $this->parameterBag->get('kernel.project_dir') . '/public/uploads/tmp/';
        $this->tmpFile = $this->tmpPath . self::faker()->uuid() . '.svg';
    }



    protected function getDefaults(): array
    {
        $companyName = self::faker()->company();
        $path = $this->parameterBag->get('kernel.project_dir') . '/public/uploads/';
        if (!file_exists($path) && !mkdir($path, 0777, true) && !is_dir($path)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $path));
        }
        if (!file_exists($this->tmpPath) && !mkdir($this->tmpPath, 0777, true) && !is_dir($this->tmpPath)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $this->tmpPath));
        }
        $data = '<svg viewBox="0 0 38 38" xmlns="http://www.w3.org/2000/svg"><style>.heavy { font: bold 4px sans-serif; fill: white; }</style><text x="50%" y="50%" alignment-baseline="middle" text-anchor="middle" textLength="90%" lengthAdjust="spacingAndGlyphs" class="heavy">' . $companyName . '</text></svg>';
        file_put_contents($this->tmpFile, $data);
        return [
            'companyName' => $companyName,
            'companyLogo' => self::faker()->file(
                $this->tmpPath,
                $path,
                false
            ),
            'companyLogoBackgroundColor' => self::faker()->hexColor(),
            'companyTown' => self::faker()->city(),
            'companyWebsite' => self::faker()->domainName(),
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
        return $this->afterInstantiate(function(Company $company): void {
            unlink($this->tmpFile);
        });
    }

    protected static function getClass(): string
    {
        return Company::class;
    }
}
