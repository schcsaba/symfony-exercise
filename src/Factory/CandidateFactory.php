<?php

namespace App\Factory;

use App\Entity\Candidate;
use App\Repository\CandidateRepository;
use Fpdf\Fpdf;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<Candidate>
 *
 * @method static Candidate|Proxy createOne(array $attributes = [])
 * @method static Candidate[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Candidate|Proxy find(object|array|mixed $criteria)
 * @method static Candidate|Proxy findOrCreate(array $attributes)
 * @method static Candidate|Proxy first(string $sortedField = 'id')
 * @method static Candidate|Proxy last(string $sortedField = 'id')
 * @method static Candidate|Proxy random(array $attributes = [])
 * @method static Candidate|Proxy randomOrCreate(array $attributes = [])
 * @method static Candidate[]|Proxy[] all()
 * @method static Candidate[]|Proxy[] findBy(array $attributes)
 * @method static Candidate[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Candidate[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static CandidateRepository|RepositoryProxy repository()
 * @method Candidate|Proxy create(array|callable $attributes = [])
 */
final class CandidateFactory extends ModelFactory
{
    private ParameterBagInterface $parameterBag;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        parent::__construct();

        $this->parameterBag = $parameterBag;
    }

    protected function getDefaults(): array
    {
        $pdf = new Fpdf();
        $pdf->AddPage();
        $pdf->SetFont('Arial','B',16);
        $pdf->Cell(40,10,'CV');
        $path = $this->parameterBag->get('kernel.project_dir') . '/public/uploads/';
        if (!file_exists($path) && !mkdir($path, 0777, true) && !is_dir($path)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $path));
        }
        $tmppath = $this->parameterBag->get('kernel.project_dir') . '/public/uploads/tmp/';
        if (!file_exists($tmppath) && !mkdir($tmppath, 0777, true) && !is_dir($tmppath)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $tmppath));
        }
        $pdf->Output(
            'F',
            $tmppath . self::faker()->uuid() . '.pdf'
        );
        return [
            'firstname' => self::faker()->firstName(),
            'lastname' => self::faker()->lastName(),
            'phone' => self::faker()->e164PhoneNumber(),
            'email' => self::faker()->safeEmail(),
            'cv' => self::faker()->file(
                $tmppath,
                $path,
                false
            ),
            'offer' => OfferFactory::random()
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(Candidate $candidate): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Candidate::class;
    }
}
