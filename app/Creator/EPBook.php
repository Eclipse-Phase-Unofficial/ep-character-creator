<?php
declare(strict_types=1);

namespace App\Creator;

/**
 * A container for an object's book and page number.
 *
 * Contains a pretty print function, along with the option of getting the books abbreviation.
 *
 * @author Arthur Moore
 */
class EPBook
{

    /**
     * Translation table between books full names and their abbreviation
     */
    const SHORT_NAMES = [
        'Rimward' => 'RW',
        'Panopticon' => 'PAN',
        'Sunward' => 'SW',
        'Gatecrashing' => 'GC',
        'Transhuman' => 'TH',
        'Eclipse Phase' => 'EP',
    ];

    /**
     * Page number (or location) within the book
     * @var string
     */
    private $page;      //
    /**
     * The full name of the book
     * @var string
     */
    private $bookName;
    /**
     * The two/three letter abbreviation of the book name
     * @var string
     */
    private $shortBook;

    function __construct(string $bookName)
    {
        $provider        = new EPListProvider(getConfigLocation());
        $this->bookName  = $provider->getBookForName($bookName) ?? '';
        $this->page      = $provider->getPageForName($bookName) ?? 'Unknown';
        $this->shortBook = self::SHORT_NAMES[$this->bookName] ?? '';
    }

    function getPage(): string
    {
        return $this->page;
    }

    function getBookName(): string
    {
        return $this->bookName;
    }

    function getAbbreviation(): string
    {
        return $this->shortBook;
    }

    /**
     * Get book info suitable for printing. (short form)
     * @return string
     */
    function getPrintableName(): string
    {
        return "(" . $this->shortBook . " p." . $this->page . ")";
    }

    /**
     * Get book info suitable for printing. (long form)
     * @return string
     */
    function getPrintableNameL(): string
    {
        return $this->bookName . " P. " . $this->page;
    }
}
