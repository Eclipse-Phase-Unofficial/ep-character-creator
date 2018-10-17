<?php
require_once('panelHelper.php');

function getDynamicTraitLi($trait,$currentTraits,$defaultTraits,$traitClass,$iconClass){
    if($currentTraits == null){
        $currentTraits = array();
    }
    if($defaultTraits == null){
        $defaultTraits = array();
    }

    $li = new li($trait->name,$traitClass);
    $li->addCost($trait->cpCost,$trait->isInArray($defaultTraits));
    $li->addBookIcon($trait->name);
    $li->addPlusChecked($iconClass,$trait->isInArray($currentTraits));
    return $li->getHtml();
}

function getDynamicTrait($trait,$currentTraits,$defaultTraits,$traitClass,$iconClass){
    if($currentTraits == null){
        $currentTraits = array();
    }
    if($defaultTraits == null){
        $defaultTraits = array();
    }

    // $li = new li($trait->name,$traitClass);
    // $li->addCost($trait->cpCost,$trait->isInArray($defaultTraits));
    
    $trait->book = getBookAbbreviation($trait->name);
    
    // TODO: Add this mechanism in the frontend.
    $trait->isSelected = $trait->isInArray($currentTraits);
    
    return $trait;
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
