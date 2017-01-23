<?php
require_once('EPListProvider.php');

/**
 * A container for an object's book and page number.
 *
 * Contains a pretty print function, along with the option of getting the books abbreviation.
 *
 * @author Arthur Moore
 */
class EPBook {

    static $BOOK_RIMWARD 		= "Rimward";
    static $BOOK_PANOPTICON 	= "Panopticon";
    static $BOOK_SUNWARD 		= "Sunward";
    static $BOOK_GATECRASHING 	= "Gatecrashing";
    static $BOOK_TRANSHUMAN 	= "Transhuman";
    static $BOOK_ECLIPSEPHASE   = "Eclipse Phase";

    private $page;      // Page number within the book
    private $bookName;  // The full name of the book
    private $shortBook; // The two/three letter abreviation of the book name

    function __construct($name) {
        $provider = new EPListProvider('../../../php/config.ini');
        $this->bookName = $provider->getBookForName($name);
        $this->page = $provider->getPageForName($name);

        if($this->bookName == EPBook::$BOOK_RIMWARD)           $this->shortBook =  "RW";
        else if($this->bookName == EPBook::$BOOK_PANOPTICON)   $this->shortBook =  "PAN";
        else if($this->bookName == EPBook::$BOOK_SUNWARD)      $this->shortBook =  "SW";
        else if($this->bookName == EPBook::$BOOK_GATECRASHING) $this->shortBook =  "GC";
        else if($this->bookName == EPBook::$BOOK_TRANSHUMAN)   $this->shortBook =  "TH";
        else if($this->bookName == EPBook::$BOOK_ECLIPSEPHASE) $this->shortBook =  "EP";
        else                                             $this->shortBook =  "";
    }

    //Generic getters
    function getPage(){
        return $this->page;
    }
    function getBookName(){
        return $this->bookName;
    }
    function getAbbreviation(){
        return $this->shortBook;
    }

    /**
     * Get book info suitable for printing.
     */
     function getPrintableName(){
        return "(".$this->shortBook." p.".$this->page.")";
     }
}
?>
