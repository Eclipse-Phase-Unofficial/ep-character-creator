<?php
require_once('bookPageLayer.php');

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

// Generate a generic li element used for selecting/deselecting an item
//
// $item:       The item being selected or deselected
// $itemClass:  Used to determine what javascript function is activated when the item is clicked
// $cpCost:     How much the item costs
// $granted:    If the item is a default (non-removable)
// $checked:    If the item is checked, or has a plus icon
// $iconClass:  used to determine what javascript function is activated when the check or plus icon is selected
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
