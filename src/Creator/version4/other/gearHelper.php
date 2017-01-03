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

function getFormatedGearList($listFiltered,$morph,$iconClass){
    $htmlResult = "";
    foreach($listFiltered as $m){
        if(isGearLegal($morph,$m)){
            $htmlResult .=  "<li class='morphGear' id='".$m->name."'>";
            $htmlResult .=  "<span>".$m->name.getListStampHtml($m->name)."</span>";
            $htmlResult .=  getCostHtml($m->getCost(),$m->isInArray($_SESSION['cc']->getCurrentDefaultMorphGear($morph)));
            if($_SESSION['cc']->haveGearOnMorph($m,$morph)){
                $htmlResult .=  "<span class='addOrSelectedIcon ".$iconClass."' id='".$m->name."' data-icon='&#x2b;'></span>";
            }else{
                $htmlResult .=  "<span class='addOrSelectedIcon ".$iconClass."' id='".$m->name."' data-icon='&#x3a;'></span>";
            }
            $htmlResult .=  "</li>";
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
