<?php
/**
 *  Panel helper functions
 *
 *  Instead of directly writing HTML, we use these helper functions whenever possible
 */

require_once('../other/bookPageLayer.php');

/**
 *  Start a panel (with a title, and a book link).
 */
function startPanel($atomName,$id){
    $output = "<span class='descriptionTitle'>".$atomName."</span>";
    $output .= "<ul class='mainlist' id='".$id."'>";
    $output .= getBPHtml($atomName);
    return $output;
}

/**
 * End a panel
 */
function endPanel(){
    return "</ul>";
}

/**
 * Add a description section to a panel.
 */
function descriptionLi($description){
    $output = "<li class='listSection'>";
    $output .= "Description";
    $output .= "</li>";
    $output .= "<li class='bmDesc'>";
    $output .= $description;
    $output .= "</li>";
    return $output;
}
?>
