<?php
require_once('bookPageLayer.php');
require_once('gearHelper.php');

function getStaticTraitHtml($traits){
    $output = "";
//     if(!empty($traits)){
        $output .= "<li class='listSection'>";
        $output .= "Traits";
        $output .= "</li>";
        foreach($traits as $t){
            $output .= "<li>";
            $output .= "		<label class='bmGranted'>".$t->name."</label>";
            $output .= "</li>";
        }
//     }
    return $output;
}

function getDynamicTraitLi($trait,$currentTraits,$defaultTraits,$traitClass,$iconClass){
    if($currentTraits == null){
        $currentTraits = array();
    }
    if($defaultTraits == null){
        $defaultTraits = array();
    }

    echo getFormattedLi($trait, $traitClass, $trait->cpCost, $trait->isInArray($defaultTraits), $trait->isInArray($currentTraits), $iconClass);
}

function isTraitLegal($morph,$trait){
    if($morph->morphType == EPMorph::$INFOMORPH)
        return false;

    if($trait->canUse == EPTrait::$CAN_USE_EVERYBODY)
        return true;
    else if($trait->canUse == EPTrait::$CAN_USE_BIO){
        if($morph->morphType == EPMorph::$BIOMORPH)
            return true;
    }
    else if($trait->canUse == EPTrait::$CAN_USE_SYNTH){
        if($morph->morphType == EPMorph::$SYNTHMORPH)
            return true;
    }
    else if($trait->canUse == EPTrait::$CAN_USE_POD){
        if($morph->morphType == EPMorph::$PODMORPH)
            return true;
    }
    return false;
}
?>
