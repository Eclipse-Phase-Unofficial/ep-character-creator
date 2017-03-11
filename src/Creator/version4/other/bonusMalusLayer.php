<?php
require_once '../../../php/EPBonusMalus.php';


/**
 * Get the section handling Bonuses / Detriments.
 *
 * @param $parentType - Good values are (origine, faction, trait, morph, morphTrait)
 *
 * @returns The HTML of multiple \<li>s
 */
function getBMHtml($bonusMalusArray,$parentName,$parentType){
    $output = "";

    //GRANTED BM
    if(grantedExist($bonusMalusArray)){
        $output .= "<li class='listSection'>";
        $output .= "Bonuses / Detriments Granted";
        $output .= "</li>";
        foreach($bonusMalusArray as $bm){
            if($bm->isGranted()){
                $output .= "<li class='bmDesc'>";
                $output .= $bm->name;
                if($bm->bonusMalusType == EPBonusMalus::$DESCRIPTIVE_ONLY){
                    $output .= "<label class='bmGrantedDesc'>".$bm->description."</label>";
                }
                $output .= "</li>";
            }
        }
    }
    //CHOICE BM
    if(choiceExist($bonusMalusArray)){
        $output .= "<li class='listSection'>";
        $output .= "Bonuses / Detriments Requiring Selection";
        $output .= "</li>";
        foreach($bonusMalusArray as $bm){
            if($bm->isChoice()){
                $output .= "<li><label class='bmChoiceInput'>";
                $output .= choosePrintOption($bm,$parentName,$parentType);
                $output .= "<input id='".$bm->getUid()."Parent' type='hidden' value='".$parentName."'>";
                $output .= "<input id='".$bm->getUid()."Type' type='hidden' value='".$parentType."'>";
                $output .= "<input id='".$bm->getUid()."BmName' type='hidden' value='".$bm->name."'>";
                $output .= "</label></li>";
            }
        }
    }
    //Multiple Choice BM
    foreach($bonusMalusArray as $bm){
        if($bm->isMultipleChoice()){
            $output .= "<li class='listSection'>";
            $output .= "Choose <span class='betweenPlusMinus'>".getSelectedOnMulti($bm)." / ".$bm->multi_occurence."</span>";
            $output .= "</li>";
            // If all the selections are made, only print out the selected BMs
            if(getSelectedOnMulti($bm) == $bm->multi_occurence){
                foreach($bm->bonusMalusTypes as $bmMulti){
                    if($bmMulti->selected){
                        $output .= "<li><label class='bmChoiceInput'>";
                        if($bmMulti->targetForChoice == EPBonusMalus::$ON_SKILL_WITH_PREFIX){
                            $activeSkills = $_SESSION['cc']->character->ego->getActiveSkills();
                            $knowledgeSkills = $_SESSION['cc']->character->ego->getKnowledgeSkills();
                            $skill = getAtomByUid(array_merge($activeSkills,$knowledgeSkills),$bmMulti->forTargetNamed);
                            $output .= "+".$bmMulti->value." ".$skill->getPrintableName();
                        }
                        else if($bmMulti->targetForChoice == EPBonusMalus::$ON_APTITUDE){
                            $output .= "+".$bmMulti->value." on ".$bmMulti->forTargetNamed;
                        }
                        else if($bmMulti->targetForChoice == EPBonusMalus::$ON_REPUTATION){
                            $output .= "+".$bmMulti->value." on ".$bmMulti->forTargetNamed;
                        }
                        else{
                            $output .= $bmMulti->name;
                        }
                        $output .= "<span class='iconPlusMinus iconebmRemChoice' id='".$bmMulti->getUid()."' data-icon='&#x39;'></span>";
                        $output .= "</label>";
                        $output .= "<input id='".$bmMulti->getUid()."MultiName' type='hidden' value='".$bmMulti->name."'>";
                        $output .= "<input id='".$bmMulti->getUid()."ParentId' type='hidden' value='".$bm->getUid()."'>";
                        $output .= "</li>";
                    }
                }
            }
            //If there are still selections remaining
            else{
                foreach($bm->bonusMalusTypes as $bmMulti){
                    $output .= "<li>";
                    if(!$bmMulti->isChoice()){
                        $output .= "<label class='bmGranted'>".$bmMulti->name."</label>";
                        if($bmMulti->selected){
                            $output .= "<span class='iconPlusMinus iconebmRemChoice'  id='".$bmMulti->getUid()."' data-icon='&#x39;'></span>";
                        }
                        else{
                            $output .= "<span class='iconPlusMinus iconebmChoice'  id='".$bmMulti->getUid()."' data-icon='&#x3a;'></span>";
                        }
                        $output .= "<input id='".$bmMulti->getUid()."Sel' type='hidden' value='".$bmMulti->forTargetNamed."'>";
                    }
                    else{
                        $output .= "<label class='bmChoiceInput'>";
                        $output .= choosePrintOption($bmMulti,$parentName,$parentType);
                        $output .= "</label>";
                    }
                    $output .= "<input id='".$bmMulti->getUid()."MultiName' type='hidden' value='".$bmMulti->name."'>";
                    $output .= "<input id='".$bmMulti->getUid()."ParentId' type='hidden' value='".$bm->getUid()."'>";
                    $output .= "</li>";
                }
            }
            $output .= "<li>";
            $output .= "		<label class='listSectionClose'>-</label>";
            $output .= "</li>";
            $output .= "<input id='".$bm->getUid()."Case' type='hidden' value='".EPBonusMalus::$MULTIPLE."'>";
            $output .= "<input id='".$bm->getUid()."Parent' type='hidden' value='".$parentName."'>";
            $output .= "<input id='".$bm->getUid()."Type' type='hidden' value='".$parentType."'>";
            $output .= "<input id='".$bm->getUid()."BmName' type='hidden' value='".$bm->name."'>";
        }
    }
    return $output;
}

/**
 * Choose which item to print based on the BM type.
 */
function choosePrintOption($bm,$parentName,$parentType){
    $activeSkills = $_SESSION['cc']->character->ego->getActiveSkills();
    $knowledgeSkills = $_SESSION['cc']->character->ego->getKnowledgeSkills();
    if($bm->targetForChoice == EPBonusMalus::$ON_SKILL_WITH_PREFIX){
        return getSkillOptions($bm,array_merge($activeSkills,$knowledgeSkills),true);
    }
    else if($bm->targetForChoice == EPBonusMalus::$ON_SKILL_ACTIVE){
        return getSkillOptions($bm,$activeSkills);
    }
    else if($bm->targetForChoice == EPBonusMalus::$ON_SKILL_KNOWLEDGE){
        return getSkillOptions($bm,$knowledgeSkills);
    }
    else if($bm->targetForChoice == EPBonusMalus::$ON_SKILL_ACTIVE_AND_KNOWLEDGE){
        return getSkillOptions($bm,array_merge($activeSkills,$knowledgeSkills));
    }
    else if($bm->targetForChoice == EPBonusMalus::$ON_APTITUDE){
        return getAptitudeOptions($bm,$parentName,$parentType);
    }
    else if($bm->targetForChoice == EPBonusMalus::$ON_REPUTATION){
        return getReputationOptions($bm);
    }
    error_log("choosePrintOption:  $bm->targetForChoice (".$bm->targetForChoice.") is unkown!");
    return    "choosePrintOption:  $bm->targetForChoice (".$bm->targetForChoice.") is unkown!";
}

/**
 * Get the options to select/deselect a skill
 */
function getSkillOptions($bm, $skill_list, $prefix_skill=false){
    //Handle Prefix only skill selection
    if( $prefix_skill == true && !empty($bm->typeTarget)){
        $skill_list = skillsWithPrefix($skill_list,$bm->typeTarget);
    }

    $output = "";

    if($bm->forTargetNamed == null || $bm->forTargetNamed == ""){
        $output .= $bm->name;
        if(!empty($skill_list)){
            $output .= "<select class='bmChoiceSelect' id='".$bm->getUid()."Sel'>";
            foreach($skill_list as $skill){
                $output .= "<option value='".$skill->getUid()."'>".$skill->getPrintableName()."</option>";
            }
            $output .= "</select>";
            $output .= "<span class='iconPlusMinus iconebmChoice'  id='".$bm->getUid()."' data-icon='&#x3a;'></span>";
        }
        else{
            $output .= "Please create an appropriate skill.";
        }
    }else{
        //If a skill has already been selected, display the deselect option
        $skill = getAtomByUid($skill_list,$bm->forTargetNamed);
        $output .= "+".$bm->value." ".$skill->getPrintableName();
        $output .= "<span class='iconPlusMinus iconebmRemChoice'  id='".$bm->getUid()."' data-icon='&#x39;'></span>";

    }
    $output .= "<input id='".$bm->getUid()."Case' type='hidden' value='".EPBonusMalus::$ON_SKILL."'>";
    return $output;
}

/**
 * Get the options to select/deselect an aptitude
 */
function getAptitudeOptions($bm,$parentName,$parentType){
    $output = "";

    if($bm->forTargetNamed == null || $bm->forTargetNamed == ""){
        $output .= $bm->name;
        $output .= "<select id='".$bm->getUid()."Sel'>";
        if($parentType == 'morph'){
            $morph = $_SESSION['cc']->getMorphByName($parentName);
            if(!empty($morph)){
                $banedAptNameList = $_SESSION['cc']->getMorphGrantedBMApptitudesNameList($morph);
                foreach($_SESSION['cc']->getAptitudes() as $apt){
                    if(!isNameOnList($apt->name, $banedAptNameList)){
                        $output .= "<option value='".$apt->name."'>".$apt->name."</option>";
                    }
                }
            }
        }
        else{
            foreach($_SESSION['cc']->getAptitudes() as $apt){
                $output .= "<option value='".$apt->name."'>".$apt->name."</option>";
            }
        }
        $output .= "</select>";
        $output .= "<span class='iconPlusMinus iconebmChoice'  id='".$bm->getUid()."' data-icon='&#x3a;'></span>";
    }else{
        //If an aptitude has already been selected, display the deselect option
        $output .= "+".$bm->value." on ".$bm->forTargetNamed;
        $output .= "<span class='iconPlusMinus iconebmRemChoice'  id='".$bm->getUid()."' data-icon='&#x39;'></span>";

    }
    $output .= "<input id='".$bm->getUid()."Case' type='hidden' value='".EPBonusMalus::$ON_APTITUDE."'>";
    return $output;
}

/**
 * Get the options to select/deselect a reputation
 */
function getReputationOptions($bm){
    $output = "";

    if($bm->forTargetNamed == null || $bm->forTargetNamed == ""){
        $output .= $bm->name;
        $output .= "<select id='".$bm->getUid()."Sel'>";
        foreach($_SESSION['cc']->getReputations() as $apt){
            $output .= "<option value='".$apt->name."'>".$apt->name."</option>";
        }
        $output .= "</select>";
        $output .= "<span class='iconPlusMinus iconebmChoice'  id='".$bm->getUid()."' data-icon='&#x3a;'></span>";
    }
    else{
        //If a reputation has already been selected, display the deselect option
        $output .= "+".$bm->value." on ".$bm->forTargetNamed;
        $output .= "<span class='iconebmRemChoice'  id='".$bm->getUid()."' data-icon='&#x39;'></span>";
    }
    $output .= "<input id='".$bm->getUid()."Case' type='hidden' value='".EPBonusMalus::$ON_REPUTATION."'>";
    return $output;
}

function grantedExist($bmArray){
		foreach($bmArray as $bm){
			if($bm->isGranted()) return true;
		}
		return false;
}
function choiceExist($bmArray){
	foreach($bmArray as $bm){
		if($bm->isChoice()) return true;
	}
	return false;
}

//All the skills in an array that have a certain prefix
function skillsWithPrefix($skillArray,$prefix){
	$outArray = array();
	foreach($skillArray as $skill){
		if($skill->prefix == $prefix){
			array_push($outArray, $skill);
		}
	}
	return $outArray;
}

function isNameOnList($name,$list){
	foreach($list as $s){
		if($name == $s) return true;
	}
	return false;
}

function getSelectedOnMulti($bmMulti){
    $count = 0;
    foreach($bmMulti->bonusMalusTypes as $bm){
        if($bm->selected){
            $count++;
        }
    }
    return $count;
}
?>
