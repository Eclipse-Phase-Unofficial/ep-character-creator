<?php
require_once('../../../php/EPBook.php');

/**
 * Print out the html for a book reference
 *
 * This is designed to be slotted in with other elements in an unordered list.
 */
function getBPHtml($atomName){
    $book = new EPBook($atomName);
    $output = "<li class='listSection'>";
    $output .= "Find more at";
    $output .= "</li>";
    $output .= "<li class='bmDesc'>";
    $output .= $book->getPrintableNameL();
    $output .= "</li>";
    return $output;
}

/**
 * Get a book's icon
 */
function getListStampHtml($atomName){
    $book = new EPBook($atomName);
    return "<span class='bookIcon ".$book->getAbbreviation()."'></span>";
}

function getBookAbbreviation($atomName){
    $book = new EPBook($atomName);
    return $book->getAbbreviation();
}

?>
