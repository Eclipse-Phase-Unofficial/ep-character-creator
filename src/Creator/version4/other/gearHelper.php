<?php
require_once('bookPageLayer.php');


/**
 * Tell the user how much something costs.
 *
 * @param $cost      - The item's cost. (if 0, nothing is displayed; takes precidence over $isDefault)
 * @param $isDefault - If the item is automatically given to the player. (if true, "(Granted)" is displayed)
 * @returns The HTML for a 'costInfo' <span\>.
 */
function getCostHtml($cost,$isDefault){
    $costDisplay = "(".$cost." cp)";
    if($isDefault){
        $costDisplay = "(Granted)";
    }
    if($cost == 0){
        $costDisplay = "";
    }
    return "<span class='costInfo'>".$costDisplay."</span>";
}

/**
 * Generate a generic li element used for selecting/deselecting an item
 *
 * @param $item       - The item being selected or deselected
 * @param $itemClass  - Used to determine what javascript function is activated when the item is clicked
 * @param $cpCost     - How much the item costs
 * @param $granted    - If the item is a default (non-removable)
 * @param $checked    - If the item is checked, or has a plus icon
 * @param $iconClass  - Used to determine what javascript function is activated when the check or plus icon is selected
 * @returns The HTML for a <li\> element.
 */
function getFormattedLi($item,$itemClass,$cpCost,$granted,$checked,$iconClass){
    $htmlResult  = "";
    $htmlResult .= "<li class='".$itemClass."' id='".$item->name."'>";
    $htmlResult .= $item->name;
    $htmlResult .= getListStampHtml($item->name);
    $htmlResult .= getCostHtml($cpCost,$granted);
    if($checked){
        $htmlResult .= "<span class='addOrSelectedIcon ".$iconClass."' id='".$item->name."' data-icon='&#x2b;'></span>";
    }
    else{
        $htmlResult .= "<span class='addOrSelectedIcon ".$iconClass."' id='".$item->name."' data-icon='&#x3a;'></span>";
    }
    $htmlResult .= "</li>";
    return $htmlResult;
}

function getFormatedGearList($listFiltered,$morph,$iconClass){
    $htmlResult = "";
    foreach($listFiltered as $m){
        if(isGearLegal($morph,$m)){
            $htmlResult .= getFormattedLi($m, 'morphGear', $m->getCost(), $m->isInArray($_SESSION['cc']->getCurrentDefaultMorphGear($morph)), $_SESSION['cc']->haveGearOnMorph($m,$morph),$iconClass);
        }
    }
    return $htmlResult;
}

function isGearLegal($morph,$gear){
    //Removed so infomorphs can buy gear
    //if($morph->morphType == EPMorph::$INFOMORPH)
    //    return false;
    if($gear->gearRestriction == EPGear::$CAN_USE_EVERYBODY)
        return true;
    //this check hides gear that you want to exist, but not render on the list
    else if($gear->gearRestriction == EPGear::$CAN_USE_CREATE_ONLY)
        return false;
    else if($gear->gearRestriction == EPGear::$CAN_USE_BIO){
        if($morph->morphType == EPMorph::$BIOMORPH)
            return true;
    }
    else if($gear->gearRestriction == EPGear::$CAN_USE_SYNTH){
        if($morph->morphType == EPMorph::$SYNTHMORPH)
            return true;
    }
    else if($gear->gearRestriction == EPGear::$CAN_USE_POD){
        if($morph->morphType == EPMorph::$PODMORPH)
            return true;
    }
    else if($gear->gearRestriction == EPGear::$CAN_USE_BIO_POD){
        if($morph->morphType == EPMorph::$BIOMORPH || $morph->morphType == EPMorph::$PODMORPH)
            return true;
    }
    else if($gear->gearRestriction == EPGear::$CAN_USE_SYNTH_POD){
        if($morph->morphType == EPMorph::$SYNTHMORPH || $morph->morphType == EPMorph::$PODMORPH)
            return true;
    }
    return false;
}

?>
