<?php
/**
 *  Panel helper functions
 *
 *  Instead of directly writing HTML, we use these helper functions whenever possible
 */

require_once('../other/bookPageLayer.php');

/**
 *  Start an empty panel.
 *
 * @param $id - The panel's id.
 */
function startPanel($id){
    $output .= "<ul class='mainlist' id='".$id."'>";
    return $output;
}

/**
 *  Start a panel (with a title, and a book link).
 *
 *  @param $id - The item being described
 */
function startDescriptivePanel($atomName){
    $output = "<span class='descriptionTitle'>".$atomName."</span>";
    $output .= "<ul class='mainlist' id='bmdList'>";
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
