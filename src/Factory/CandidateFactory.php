<?php

namespace App\Factory;

use App\Entity\Candidate;
use App\Repository\CandidateRepository;
use Fpdf\Fpdf;
use RuntimeException;
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
    private $tmpPath;
    private $tmpFile;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        parent::__construct();

        $this->parameterBag = $parameterBag;
        $this->tmpPath = $this->parameterBag->get('kernel.project_dir') . '/public/uploads/tmp/';
        $this->tmpFile = $this->tmpPath . self::faker()->uuid() . '.pdf';
    }

    protected function getDefaults(): array
    {
        $firstname = self::faker()->firstName();
        $lastname = self::faker()->lastName();
        $pdf = new Fpdf();
        $pdf->AddPage();
        $pdf->SetFont('Arial','B',16);
        $pdf->Cell(40,10,'CV: ' . $firstname . ' ' . $lastname);
        $path = $this->parameterBag->get('kernel.project_dir') . '/public/uploads/';
        if (!file_exists($path) && !mkdir($path, 0777, true) && !is_dir($path)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $path));
        }
        if (!file_exists($this->tmpPath) && !mkdir($this->tmpPath, 0777, true) && !is_dir($this->tmpPath)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $this->tmpPath));
        }
        $pdf->Output(
            'F',
            $this->tmpFile
        );
        return [
            'firstname' => $firstname,
            'lastname' => $lastname,
            'phone' => self::faker()->e164PhoneNumber(),
            'email' => self::faker()->safeEmail(),
            'cv' => self::faker()->file(
                $this->tmpPath,
                $path,
                false
            ),
            'offer' => OfferFactory::random()
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this->afterInstantiate(function(Candidate $candidate): void {
            unlink($this->tmpFile);
        });
    }

    protected static function getClass(): string
    {
        return Candidate::class;
    }
}
