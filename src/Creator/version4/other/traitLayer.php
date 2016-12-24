<?php
require_once('../other/bookPageLayer.php');

function getStaticTraitHtml($traits){
//     if(!empty($traits)){
        echo "<li>";
        echo "		<label class='listSection'>Traits</label>";
        echo "</li>";
        foreach($traits as $t){
            echo "<li>";
            echo "		<label class='bmGranted'>".$t->name."</label>";
            echo "</li>";
        }
//         }
}

function getDynamicTraitLi($trait,$currentTraits,$defaultTraits,$traitClass,$iconClass){
    if($currentTraits == null){
        $currentTraits = array();
    }
    if($defaultTraits == null){
        $defaultTraits = array();
    }

    $costDisplay = "(".$trait->cpCost." cp)";
    if($trait->isInArray($defaultTraits)){
        $costDisplay = "(Granted)";
    }
    if($trait->cpCost == 0){
        $costDisplay = "";
    }

    echo "<li class='".$traitClass."' id='".$trait->name."'>";
    echo "  <span class='paddedLeft'>".$trait->name."</span>";
    echo "  ".getListStampHtml($trait->name);
    echo "  <span class='costInfo'>".$costDisplay."</span>";
    if($trait->isInArray($currentTraits)){
        echo "  <span class='addOrSelectedIcon ".$iconClass."' id='".$trait->name."' data-icon='&#x2b;'></span>";
    }
    else{
        echo "  <span class='addOrSelectedIcon ".$iconClass."' id='".$trait->name."' data-icon='&#x3a;'></span>";
    }
    echo "</li>";
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
