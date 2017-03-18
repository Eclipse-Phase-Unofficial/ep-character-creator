<?php
require_once('panelHelper.php');

/**
 * Get the Gear list a morph can use.
 *
 * Filters out gear morphs can't use.
 */
function getFormatedMorphGearList($listFiltered,$morph,$iconClass){
    $htmlResult = "";
    foreach($listFiltered as $m){
        if(isGearLegal($morph,$m)){
            $li = new li($m->name,'morphGear');
            $li->addCost($m->getCost(),$m->isInArray($_SESSION['cc']->getCurrentDefaultMorphGear($morph)),'cr');
            $li->addBookIcon($m->name);
            $li->addPlusChecked($iconClass,$_SESSION['cc']->haveGearOnMorph($m,$morph));
            $htmlResult .= $li->getHtml();
        }
    }
    return $htmlResult;
}

/**
 * Add/Display/Remove free gear for both morph and Ego.
 */
function getFreeGear($currentGear,$isEgo = True){
    $output  = "";
    $ego_or_morph = "Ego";
    if(!$isEgo){
        $ego_or_morph = "Morph";
    }

    $output .= "<li class='foldingListSection' id='free'>";
    $output .= "Free Gear";
    $output .= "</li>";
    $output .= "<ul class='mainlist foldingList free' id='freeGear'>";
    $output .= "    <li>";
    $output .= "            <input type='text' id='free".$ego_or_morph."GearToAdd' placeholder='Gear Name'/>";
    $output .= "            <select id='free".$ego_or_morph."GearPrice'>";
    $output .= "                    <option value=".EPCreditCost::$LOW.">".EPCreditCost::$LOW."</option>";
    $output .= "                    <option value=".EPCreditCost::$MODERATE.">".EPCreditCost::$MODERATE."</option>";
    $output .= "                    <option value=".EPCreditCost::$HIGH.">".EPCreditCost::$HIGH."</option>";
    $output .= "                    <option value=".EPCreditCost::$EXPENSIVE.">".EPCreditCost::$EXPENSIVE."</option>";
    $output .= "                    <option value=".EPCreditCost::$VERY_EXPENSIVE.">".EPCreditCost::$VERY_EXPENSIVE."</option>";
    $output .= "                    <option value=".EPCreditCost::$EXTREMELY_EXPENSIVE.">".EPCreditCost::$EXTREMELY_EXPENSIVE."</option>";
    $output .= "            </select>";
    $output .= "            <span class='addOrSelectedIcon' id='addFree".$ego_or_morph."Gear' data-icon='&#x3a;'></span>";
    $output .= "    </li>";
    foreach($currentGear as $m){
        if($m->gearType == EPGear::$FREE_GEAR){
            $li = new li($m->name);
            $li->addCost($m->getCost(),False,'Credits');
            $li->addPlusX("remFree".$ego_or_morph."Gear",False);
            $output .= $li->getHtml();
        }
    }
    $output .= "</ul>";
    return $output;
}

/**
 * Outputs a 'foldingListSection' for gear of a certain type.
 */
function getGearSection($gears,$morph,$gearType,$sectionName){
    //Generate a HTML valid Id from the section name
    $id = preg_replace("/[^A-z]/","",$sectionName);

    $listFiltered = array();
    foreach($gears as $m){
        if($m->gearType == $gearType){
            array_push($listFiltered, $m);
        }
    }
    $formatedHtml = getFormatedMorphGearList($listFiltered,$morph,'addSelMorphGearIcon');

    $output  = "";
    $output .= "<li class='foldingListSection' id='".$id."'>";
    $output .= $sectionName;
    $output .= "</li>";
    $output .= "<ul class='mainlist foldingList ".$id."''>";
    $output .= $formatedHtml;
    $output .= "</ul>";
    return $output;
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
