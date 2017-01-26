<?php
require_once('../../../php/EPBook.php');

/**
 * Print out the html for a book reference
 *
 * This is designed to be slotted in with other elements in an unordered list.
 */
function getBPHtml($atomName){
    $book = new EPBook($atomName);
    echo "<li class='listSection'>";
    echo "Find more at";
    echo "</li>";
    echo "<li>";
    echo "<span class='bmDesc'>".$book->getPrintableNameL()."</span>";
    echo "</li>";
}

/**
 * Get a book's icon
 */
function getListStampHtml($atomName){
    $book = new EPBook($atomName);
    return "<span class='bookIcon ".$book->getAbbreviation()."'></span>";
}


?>
