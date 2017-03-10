<?php
require_once '../../../php/EPBonusMalus.php';


/**
 * @param $parentType - Good values are (origine, faction, trait, morph, morphTrait)
 */
function getBMHtml($bonusMalusArray,$parentName,$parentType){
    //GRANTED BM
    if(grantedExist($bonusMalusArray)){
        echo "<li class='listSection' id='bm_granted'>";
        echo "Bonuses / Detriments Granted";
        echo "</li>";
        foreach($bonusMalusArray as $bm){
            if($bm->isGranted()){
                echo "<li>";
                if($bm->bonusMalusType == EPBonusMalus::$DESCRIPTIVE_ONLY){
                    echo "<label class='bmGranted'>".$bm->name."</label>";
                    echo "<label class='bmGrantedDesc'>".$bm->description."</label>";
                }
                else{
                    echo "<label class='bmGranted'>".$bm->name."</label>";
                }
                    echo "</li>";
            }
        }
    }
    //CHOICE BM
    if(choiceExist($bonusMalusArray)){
        echo "<li class='listSection'>";
        echo "Bonuses / Detriments Requiring Selection";
        echo "</li>";
        foreach($bonusMalusArray as $bm){
            if($bm->isChoice()){
                choosePrintOption($bm,$parentName,$parentType);
                echo "<input id='".$bm->getUid()."Parent' type='hidden' value='".$parentName."'>";
                echo "<input id='".$bm->getUid()."Type' type='hidden' value='".$parentType."'>";
                echo "<input id='".$bm->getUid()."BmName' type='hidden' value='".$bm->name."'>";
            }
        }
    }
    //Multiple Choice BM
    foreach($bonusMalusArray as $bm){
        if($bm->isMultipleChoice()){
            echo "<li class='listSection'>";
            echo "Choose <span class='betweenPlusMinus'>".getSelectedOnMulti($bm)." / ".$bm->multi_occurence."</span>";
            echo "</li>";
            // If all the selections are made
            if(getSelectedOnMulti($bm) == $bm->multi_occurence){
								foreach($bm->bonusMalusTypes as $bmMulti){
									if($bmMulti->selected){
										echo "<li><label class='bmChoiceInput'>";
										if($bmMulti->targetForChoice == EPBonusMalus::$ON_SKILL_WITH_PREFIX){
											echo "+".$bmMulti->value." ".$bmMulti->typeTarget." : ".$bmMulti->forTargetNamed;
										}
										else if($bmMulti->targetForChoice == EPBonusMalus::$ON_APTITUDE){
											echo "+".$bmMulti->value." on ".$bmMulti->forTargetNamed;
										}
										else if($bmMulti->targetForChoice == EPBonusMalus::$ON_REPUTATION){
											echo "+".$bmMulti->value." on ".$bmMulti->forTargetNamed;
										}
										else{
											echo $bmMulti->name;
										}
										echo "<span class='iconPlusMinus iconebmRemChoice' id='".$bmMulti->getUid()."' data-icon='&#x39;'></span>";
										echo "</label></li>";
									}
									echo "<input id='".$bmMulti->getUid()."MultiName' type='hidden' value='".$bmMulti->name."'>";
									echo "<input id='".$bmMulti->getUid()."ParentId' type='hidden' value='".$bm->getUid()."'>";
								}
            }
            //If there are still selections remaining
            else{
								foreach($bm->bonusMalusTypes as $bmMulti){
									if(!choosePrintOption($bmMulti,$parentName,$parentType)){
										echo "<li>";
										echo "<label class='bmGranted'>".$bmMulti->name."</label>";
										echo "<input id='".$bmMulti->getUid()."Sel' type='hidden' value='".$bmMulti->forTargetNamed."'>";
										if($bmMulti->selected){
											echo "<span class='iconPlusMinus iconebmRemChoice'  id='".$bmMulti->getUid()."' data-icon='&#x39;'></span>";
										}
										else{
											echo "<span class='iconPlusMinus iconebmChoice'  id='".$bmMulti->getUid()."' data-icon='&#x3a;'></span>";
										}
										echo "</li>";
										echo "<input id='".$bmMulti->getUid()."MultiName' type='hidden' value='".$bmMulti->name."'>";
									}

									echo "<input id='".$bmMulti->getUid()."ParentId' type='hidden' value='".$bm->getUid()."'>";
								}
            }
            echo "<li>";
            echo "		<label class='listSectionClose'>-</label>";
            echo "</li>";
            echo "<input id='".$bm->getUid()."Case' type='hidden' value='".EPBonusMalus::$MULTIPLE."'>";
            echo "<input id='".$bm->getUid()."Parent' type='hidden' value='".$parentName."'>";
            echo "<input id='".$bm->getUid()."Type' type='hidden' value='".$parentType."'>";
            echo "<input id='".$bm->getUid()."BmName' type='hidden' value='".$bm->name."'>";
        }
    }
}

/**
 * Choose which item to print based on the BM type.
 */
function choosePrintOption($bm,$parentName,$parentType){
    if($bm->targetForChoice == EPBonusMalus::$ON_SKILL_WITH_PREFIX){
        $activeSkills = $_SESSION['cc']->character->ego->getActiveSkills();
        $knowledgeSkills = $_SESSION['cc']->character->ego->getKnowledgeSkills();
        printSkillOptions($bm,array_merge($activeSkills,$knowledgeSkills),true);
        return true;
    }
    else if($bm->targetForChoice == EPBonusMalus::$ON_SKILL_ACTIVE){
        printSkillOptions($bm,$_SESSION['cc']->character->ego->getActiveSkills());
        return true;
    }
    else if($bm->targetForChoice == EPBonusMalus::$ON_SKILL_KNOWLEDGE){
        printSkillOptions($bm,$_SESSION['cc']->character->ego->getKnowledgeSkills());
        return true;
    }
    else if($bm->targetForChoice == EPBonusMalus::$ON_SKILL_ACTIVE_AND_KNOWLEDGE){
        $activeSkills = $_SESSION['cc']->character->ego->getActiveSkills();
        $knowledgeSkills = $_SESSION['cc']->character->ego->getKnowledgeSkills();
        printSkillOptions($bm,array_merge($activeSkills,$knowledgeSkills));
        return true;
    }
    else if($bm->targetForChoice == EPBonusMalus::$ON_APTITUDE){
        printAptitudeOptions($bm,$parentName,$parentType);
        return true;
    }
    else if($bm->targetForChoice == EPBonusMalus::$ON_REPUTATION){
        printReputationOptions($bm);
        return true;
    }
    return false;
}

/**
 * Print out the options to select/deselect a skill
 */
function printSkillOptions($bm, $skill_list, $prefix_skill=false){
	//Handle Prefix only skill selection
	if( $prefix_skill == true && !empty($bm->typeTarget)){
		$skill_list = skillsWithPrefix($skill_list,$bm->typeTarget);
	}

	echo "<li><label class='bmChoiceInput'>";
	if($bm->forTargetNamed == null || $bm->forTargetNamed == ""){
		echo $bm->name;
		if(!empty($skill_list)){
			echo "<select class='bmChoiceSelect' id='".$bm->getUid()."Sel'>";
			foreach($skill_list as $skill){
				echo "<option value='".$skill->getUid()."'>".$skill->getPrintableName()."</option>";
			}
			echo "</select>";
			echo "<span class='iconPlusMinus iconebmChoice'  id='".$bm->getUid()."' data-icon='&#x3a;'></span>";
		}
		else{
			echo "Please create an appropriate skill.";
		}
	}else{
        //If a skill has already been selected, display the deselect option
		$skill = getAtomByUid($skill_list,$bm->forTargetNamed);
		echo "+".$bm->value." ".$skill->getPrintableName();
		echo "<span class='iconPlusMinus iconebmRemChoice'  id='".$bm->getUid()."' data-icon='&#x39;'></span>";

	}
	echo "</label></li>";
	echo "<input id='".$bm->getUid()."Case' type='hidden' value='".EPBonusMalus::$ON_SKILL."'>";
}

/**
 * Print out the options to select/deselect an aptitude
 */
function printAptitudeOptions($bm,$parentName,$parentType){
	echo "<li><label class='bmChoiceInput'>";
	if($bm->forTargetNamed == null || $bm->forTargetNamed == ""){
		echo $bm->name;
		echo "<select id='".$bm->getUid()."Sel'>";
		if($parentType == 'morph'){
			$morph = $_SESSION['cc']->getMorphByName($parentName);
			if(!empty($morph)){
				$banedAptNameList = $_SESSION['cc']->getMorphGrantedBMApptitudesNameList($morph);
				foreach($_SESSION['cc']->getAptitudes() as $apt){
					if(!isNameOnList($apt->name, $banedAptNameList)){
						echo "<option value='".$apt->name."'>".$apt->name."</option>";
					}
				}
			}
		}
		else{
			foreach($_SESSION['cc']->getAptitudes() as $apt){
				echo "<option value='".$apt->name."'>".$apt->name."</option>";
			}
		}
		echo "</select>";
		echo "<span class='iconPlusMinus iconebmChoice'  id='".$bm->getUid()."' data-icon='&#x3a;'></span>";
	}else{
        //If an aptitude has already been selected, display the deselect option
		echo "+".$bm->value." on ".$bm->forTargetNamed;
		echo "<span class='iconPlusMinus iconebmRemChoice'  id='".$bm->getUid()."' data-icon='&#x39;'></span>";

	}
	echo "</label></li>";
	echo "<input id='".$bm->getUid()."Case' type='hidden' value='".EPBonusMalus::$ON_APTITUDE."'>";
}

/**
 * Print out the options to select/deselect a reputation
 */
function printReputationOptions($bm){
	echo "<li><label class='bmChoiceInput'>";
	if($bm->forTargetNamed == null || $bm->forTargetNamed == ""){
		echo $bm->name;
		echo "<select id='".$bm->getUid()."Sel'>";
		foreach($_SESSION['cc']->getReputations() as $apt){
			echo "<option value='".$apt->name."'>".$apt->name."</option>";
		}
		echo "</select>";
		echo "<span class='iconPlusMinus iconebmChoice'  id='".$bm->getUid()."' data-icon='&#x3a;'></span>";
	}
	else{
        //If a reputation has already been selected, display the deselect option
		echo "+".$bm->value." on ".$bm->forTargetNamed;
		echo "<span class='iconebmRemChoice'  id='".$bm->getUid()."' data-icon='&#x39;'></span>";
	}
	echo "</label></li>";
	echo "<input id='".$bm->getUid()."Case' type='hidden' value='".EPBonusMalus::$ON_REPUTATION."'>";
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
