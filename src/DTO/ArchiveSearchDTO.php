<?php
namespace App\DTO;

use DateTimeInterface;
use App\Validator as AcmeAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class ArchiveSearchDTO serves as an entity for the ArchiveSearchType form 
 * and to validate the transmitted data
 */
class ArchiveSearchDTO
{
    public const ORDERS = ['oldest', 'latest'];
    public const OPTIONS = ['word', 'word-parts'];

    #[Assert\Type(type: 'string')]
    #[Assert\Length(min: 3, max: 64, minMessage: 'Mindestens 3 Zeichen erlaubt.', maxMessage: 'Maximal 64 Zeichen erlaubt.')]
    #[Assert\NotBlank(allowNull: false, message: 'Suche ist leer.')]
    public ?string $search = null;

    #[Assert\Type(type: DateTimeInterface::class)]
    #[Assert\NotBlank(allowNull: false)]
    #[AcmeAssert\ArchiveDateArea(message: 'Unerlaubtes Startdatum.')]
    public ?DateTimeInterface $start_date = null;
    
    #[Assert\Type(type: DateTimeInterface::class)]
    #[Assert\NotBlank(allowNull: false)]
    #[AcmeAssert\ArchiveDateArea(message: 'Unerlaubtes Enddattum.')]
    public ?DateTimeInterface $end_date = null;

    #[Assert\Type(type: 'string')]
    #[Assert\Choice(choices: ArchiveSearchDTO::ORDERS)]
    #[Assert\NotBlank(allowNull: false, message: "Unerlaubte Sortierung ausegwählt.")]
    public ?string $order = null;

    #[Assert\Type(type: 'string')]
    #[Assert\Choice(choices: ArchiveSearchDTO::OPTIONS)]
    #[Assert\NotBlank(allowNull: false, message: "Unerlaubte Sucheinstellung ausegwählt.")]
    public ?string $option = null;

    public function __construct(
        public DateTimeInterface $minDate,
        public DateTimeInterface $maxDate, 
    ){}
}
