<?php
require_once '../../../php/EPAtom.php';

function getBMHtml($bonusMalusArray,$parentName,$parentType){
		$provider = new EPListProvider('../../../php/config.ini');
		$prefixList =  $provider->getListPrefix();
		$activeSkillList = $_SESSION['cc']->getActiveSkills();
		$knowledgeSkillList = $_SESSION['cc']->getKnowledgeSkills();
		$totalSkills = array_merge($activeSkillList,$knowledgeSkillList);
		//GRANTED BM
		if(grantedExist($bonusMalusArray)){
			echo "<li>";
			echo "		<label class='listSection'>Granted</label>";
			echo "</li>";
			foreach($bonusMalusArray as $bm){
					if($bm->targetForChoice == ""){
						if($bm->bonusMalusType == EPBonusMalus::$DESCRIPTIVE_ONLY){
							echo "<li>";
							echo "		<label class='bmGranted'>".$bm->name."</label>";
							echo "		<label class='bmGrantedDesc'>".$bm->description."</label>";
							echo "</li>";

						}
						else{
								echo "<li>";
								echo "		<label class='bmGranted'>".$bm->name."</label>";
								echo "</li>";
						}
					}
			}
		}
		if(choiceExist($bonusMalusArray)){
			//CHOICE BM
			echo "<li>";
			echo "		<label class='listSection'>Define</label>";
			echo "</li>";
			foreach($bonusMalusArray as $bm){
					if($bm->targetForChoice != ""){
						if($bm->targetForChoice == EPBonusMalus::$ON_SKILL_WITH_PREFIX){
							printSkillOptions($bm,$totalSkills,true);
						}
						else if($bm->targetForChoice == EPBonusMalus::$ON_SKILL_ACTIVE){
							printSkillOptions($bm,$activeSkillList);
						}
						else if($bm->targetForChoice == EPBonusMalus::$ON_SKILL_KNOWLEDGE){
							printSkillOptions($bm,$knowledgeSkillList);
						}
						else if($bm->targetForChoice == EPBonusMalus::$ON_SKILL_ACTIVE_AND_KNOWLEDGE){
							printSkillOptions($bm,$totalSkills);
						}
						else if($bm->targetForChoice == EPBonusMalus::$ON_APTITUDE){
							printAptitudeOptions($bm,$parentName,$parentType);
						}
						else if($bm->targetForChoice == EPBonusMalus::$ON_REPUTATION){
							printReputationOptions($bm);
						}
						else if($bm->targetForChoice == EPBonusMalus::$MULTIPLE){
							echo "<li>";
							echo "		<label class='listSection'>Choose <span class='costInfo'>".$_SESSION['cc']->getSelectedOnMulti($bm)." / ".$bm->multi_occurence."</span></label>";
							echo "</li>";
							if($_SESSION['cc']->getSelectedOnMulti($bm) == $bm->multi_occurence){
								foreach($bm->bonusMalusTypes as $bmMulti){
									if($bmMulti->selected){
										echo "<li>";
										if($bmMulti->targetForChoice == EPBonusMalus::$ON_SKILL_WITH_PREFIX){
											echo "		<label class='bmChoiceInput''> +".$bmMulti->value." ".$bmMulti->typeTarget." : ".$bmMulti->forTargetNamed."</label>";
											echo "		<span class='iconPlusMinus iconebmRemChoice'  id='".$bmMulti->getUid()."' data-icon='&#x39;'></span>";
										}
										else if($bmMulti->targetForChoice == EPBonusMalus::$ON_APTITUDE){
											echo "		<label class='bmChoiceInput''> +".$bmMulti->value." on ".$bmMulti->forTargetNamed."</label>";
											echo "		<span class='iconPlusMinus iconebmRemChoice'  id='".$bmMulti->getUid()."' data-icon='&#x39;'></span>";
										}
										else if($bmMulti->targetForChoice == EPBonusMalus::$ON_REPUTATION){
											echo "		<label class='bmChoiceInput''> +".$bmMulti->value." on ".$bmMulti->forTargetNamed."</label>";
											echo "		<span class='iconPlusMinus iconebmRemChoice'  id='".$bmMulti->getUid()."' data-icon='&#x39;'></span>";
										}
										else{
											echo "		<label class='bmGranted' id='".$bmMulti->getUid()."'>".$bmMulti->name."</label>";
											echo "		<span class='iconPlusMinus iconebmRemChoice' id='".$bmMulti->getUid()."' data-icon='&#x39;'></span>";
										}
										echo "</li>";
									}
									echo "<input id='".$bmMulti->getUid()."MultiName' type='hidden' value='".$bmMulti->name."'>";
									echo "<input id='".$bmMulti->getUid()."ParentId' type='hidden' value='".$bm->getUid()."'>";
								}
							}
							else{
								foreach($bm->bonusMalusTypes as $bmMulti){
									if($bmMulti->targetForChoice == EPBonusMalus::$ON_SKILL_WITH_PREFIX){
										printSkillOptions($bmMulti,$totalSkills,true);
									}
									else if($bmMulti->targetForChoice == EPBonusMalus::$ON_SKILL_ACTIVE){
										printSkillOptions($bmMulti,$activeSkillList);
									}
									else if($bmMulti->targetForChoice == EPBonusMalus::$ON_SKILL_KNOWLEDGE){
										printSkillOptions($bmMulti,$knowledgeSkillList);
									}
									else if($bmMulti->targetForChoice == EPBonusMalus::$ON_SKILL_ACTIVE_AND_KNOWLEDGE){
										printSkillOptions($bmMulti,$totalSkills);
									}
									else if($bmMulti->targetForChoice == EPBonusMalus::$ON_APTITUDE){
										printAptitudeOptions($bmMulti,$parentName,$parentType);
									}
									else if($bmMulti->targetForChoice == EPBonusMalus::$ON_REPUTATION){
										printReputationOptions($bmMulti);
									}
									else{
										echo "<li>";
										echo "<label class='bmGranted'>".$bmMulti->name."</label>";
										echo "<input id='".$bmMulti->getUid()."Sel' type='hidden' value='".$bmMulti->forTargetNamed."'>";
										if($bmMulti->selected){
											echo "<span class='iconPlusMinus iconebmChoice'  id='".$bmMulti->getUid()."' data-icon='&#x39;'></span>";
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
						}
						echo "<input id='".$bm->getUid()."Parent' type='hidden' value='".$parentName."'>";
						echo "<input id='".$bm->getUid()."Type' type='hidden' value='".$parentType."'>";
						echo "<input id='".$bm->getUid()."BmName' type='hidden' value='".$bm->name."'>";

					}
			}

		}
}

// Print out the options to select/deselect a skill
// Use this instead of repeating the same thing multiple times
function printSkillOptions($bm, $skill_list, $prefix_skill=false){
	//Handle Prefix only skill selection
	if( $prefix_skill == true && !empty($bm->typeTarget)){
		$skill_list = skillsWithPrefix($skill_list,$bm->typeTarget);
	}

	echo "<li><label class='bmChoiceInput'>";
	if($bm->forTargetNamed == null || $bm->forTargetNamed == ""){
		echo $bm->name;
		if(!empty($skill_list)){
			echo "	 <select class='bmChoiceSelect' id='".$bm->getUid()."Sel'>";
			foreach($skill_list as $skill){
				echo "	 <option value='".$skill->getUid()."'>".$skill->getPrintableName()."</option>";
			}
			echo "</select>";
			echo "<span class='iconPlusMinus iconebmChoice'  id='".$bm->getUid()."' data-icon='&#x3a;'></span>";
		}
		else{
			echo "Create an appropriate skill (skills menus)";
		}
	}
	else{
		$skill = getAtomByUid($skill_list,$bm->forTargetNamed);
		echo "+".$bm->value." ".$skill->getPrintableName();
		echo "<span class='iconPlusMinus iconebmRemChoice'  id='".$bm->getUid()."' data-icon='&#x39;'></span>";

	}
	echo "</label></li>";
	echo "<input id='".$bm->getUid()."Case' type='hidden' value='".EPBonusMalus::$ON_SKILL."'>";
}

// Print out the options to select/deselect an aptitude
// Use this instead of repeating the same thing multiple times
function printAptitudeOptions($bm,$parentName,$parentType){
	echo "<li>";
	if($bm->forTargetNamed == null || $bm->forTargetNamed == ""){
		echo "		<label class='bmChoiceInput'>".$bm->name;
		echo "		<select id='".$bm->getUid()."Sel'>";
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
		echo "		</select></label>";
		echo "		<span class='iconPlusMinus iconebmChoice'  id='".$bm->getUid()."' data-icon='&#x3a;'></span>";
	}
	else{
		echo "		<label class='bmChoiceInput''> +".$bm->value." on ".$bm->forTargetNamed."</label>";
		echo "		<span class='iconPlusMinus iconebmRemChoice'  id='".$bm->getUid()."' data-icon='&#x39;'></span>";

	}
	echo "</li>";
	echo "<input id='".$bm->getUid()."Case' type='hidden' value='".EPBonusMalus::$ON_APTITUDE."'>";
}

// Print out the options to select/deselect a reputation
// Use this instead of repeating the same thing multiple times
function printReputationOptions($bm){
	echo "<li>";
	if($bm->forTargetNamed == null || $bm->forTargetNamed == ""){
		echo "		<label class='bmChoiceInput'>".$bm->name;
		echo "		<select id='".$bm->getUid()."Sel'>";
		foreach($_SESSION['cc']->getReputations() as $apt){
			echo "<option value='".$apt->name."'>".$apt->name."</option>";
		}
		echo "		</select></label>";
		echo "		<span class='iconPlusMinus iconebmChoice'  id='".$bm->getUid()."' data-icon='&#x3a;'></span>";
	}
	else{
		echo "		<label class='bmChoiceInput''> +".$bm->value." on ".$bm->forTargetNamed."</label>";
		echo "		<span class='iconebmRemChoice'  id='".$bm->getUid()."' data-icon='&#x39;'></span>";
	}
	echo "</li>";
	echo "<input id='".$bm->getUid()."Case' type='hidden' value='".EPBonusMalus::$ON_REPUTATION."'>";
}

function grantedExist($bmArray){
		foreach($bmArray as $bm){
			if($bm->targetForChoice == "") return true;
		}
		return false;
}
function choiceExist($bmArray){
	foreach($bmArray as $bm){
		if($bm->targetForChoice != "") return true;
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
?>
