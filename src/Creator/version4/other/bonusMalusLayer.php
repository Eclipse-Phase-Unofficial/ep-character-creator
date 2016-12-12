<?php
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
							if($bm->forTargetNamed == null || $bm->forTargetNamed == ""){
								echo "<li>";
								echo "	 <label class='bmChoiceInput'>".$bm->name;
								if(skillWithPrefixExist($totalSkills,$bm->typeTarget)){
									echo "	 <select class='bmChoiceSelect' id='".$bm->atomUid."Sel'>";
									foreach($totalSkills as $acSkill){
												if($acSkill->prefix == $bm->typeTarget){
													echo "	 <option value='".$acSkill->name."'>".$bm->typeTarget." : ".$acSkill->name."</option>";
												}
									}
									echo "	 </select></label>";
									echo "	 <span class='iconebmChoice'  id='".$bm->atomUid."' data-icon='&#x3a;'></span>";
								}
								else{
									echo "		<label class='bmGrantedDesc'>Create an appropriate skill (skills menus)</label></label>";
								}
								echo "</li>";							 	}
							else{
								echo "<li>";
								echo "		<label class='bmChoiceInput''> +".$bm->value." ".$bm->typeTarget." : ".$bm->forTargetNamed."</label>";
								echo "		<span class='iconebmRemChoice'  id='".$bm->atomUid."' data-icon='&#x39;'></span>";
								echo "</li>";

							}
							echo "<input id='".$bm->atomUid."Case' type='hidden' value='".EPBonusMalus::$ON_SKILL."'>";
						}
						else if($bm->targetForChoice == EPBonusMalus::$ON_SKILL_ACTIVE){
							if($bm->forTargetNamed == null || $bm->forTargetNamed == ""){
								echo "<li>";
								echo "	 <label class='bmChoiceInput'>".$bm->name;
								echo "	 <select class='bmChoiceSelect' id='".$bm->atomUid."Sel'>";
								foreach($activeSkillList as $acSkill){
											if(!empty($acSkill->prefix)) $sk_prefix = $acSkill->prefix." : ";
											else $sk_prefix = "";
											echo "	 <option value='".$acSkill->name."'>".$sk_prefix.$acSkill->name."</option>";
								}
								echo "	 </select></label>";
								echo "	 <span class='iconebmChoice'  id='".$bm->atomUid."' data-icon='&#x3a;'></span>";
								echo "</li>";
							}
							else{
								echo "<li>";
								echo "		<label class='bmChoiceInput''> +".$bm->value." ".$bm->typeTarget." : ".$bm->forTargetNamed."</label>";
								echo "		<span class='iconebmRemChoice'  id='".$bm->atomUid."' data-icon='&#x39;'></span>";
								echo "</li>";

							}
							echo "<input id='".$bm->atomUid."Case' type='hidden' value='".EPBonusMalus::$ON_SKILL."'>";
						}
						else if($bm->targetForChoice == EPBonusMalus::$ON_SKILL_KNOWLEDGE){
							if($bm->forTargetNamed == null || $bm->forTargetNamed == ""){
								echo "<li>";
								echo "	 <label class='bmChoiceInput'>".$bm->name;
								if(!empty($knowledgeSkillList)){
									echo "	 <select class='bmChoiceSelect' id='".$bm->atomUid."Sel'>";
									foreach($knowledgeSkillList as $knSkill){
										if(!empty($knSkill->prefix)){
											$sk_prefix = $knSkill->prefix." : ";
										}
										else{
											$sk_prefix = "";
										}
										echo "	 <option value='".$knSkill->name."'>".$sk_prefix.$knSkill->name."</option>";
									}
									echo "	 </select></label>";
								}
								else{
									echo "		<label class='bmGrantedDesc'>Create an appropriate skill (skills menus)</label></label>";
								}
								echo "	 <span class='iconebmChoice'  id='".$bm->atomUid."' data-icon='&#x3a;'></span>";
								echo "</li>";
							}
							else{
								echo "<li>";
								echo "		<label class='bmChoiceInput''> +".$bm->value." ".$bm->typeTarget." : ".$bm->forTargetNamed."</label>";
								echo "		<span class='iconebmRemChoice'  id='".$bm->atomUid."' data-icon='&#x39;'></span>";
								echo "</li>";

							}
							echo "<input id='".$bm->atomUid."Case' type='hidden' value='".EPBonusMalus::$ON_SKILL."'>";
						}
						else if($bm->targetForChoice == EPBonusMalus::$ON_SKILL_ACTIVE_AND_KNOWLEDGE){
							if($bm->forTargetNamed == null || $bm->forTargetNamed == ""){
								echo "<li>";
								echo "	 <label class='bmChoiceInput'>".$bm->name;
								echo "	 <select class='bmChoiceSelect' id='".$bm->atomUid."Sel'>";
								foreach($totalSkills as $knSkill){
									if(!empty($knSkill->prefix)){
										$sk_prefix = $knSkill->prefix." : ";
									}
									else{
										$sk_prefix = "";
									}
									echo "	 <option value='".$knSkill->name."'>".$sk_prefix.$knSkill->name."</option>";
								}
								echo "	 </select></label>";
								echo "	 <span class='iconebmChoice'  id='".$bm->atomUid."' data-icon='&#x3a;'></span>";
								echo "</li>";
							}
							else{
								echo "<li>";
								echo "		<label class='bmChoiceInput''> +".$bm->value." ".$bm->typeTarget." : ".$bm->forTargetNamed."</label>";
								echo "		<span class='iconebmRemChoice'  id='".$bm->atomUid."' data-icon='&#x39;'></span>";
								echo "</li>";

							}
							echo "<input id='".$bm->atomUid."Case' type='hidden' value='".EPBonusMalus::$ON_SKILL."'>";
						}
						else if($bm->targetForChoice == EPBonusMalus::$ON_APTITUDE){
							if($bm->forTargetNamed == null || $bm->forTargetNamed == ""){
								echo "<li>";
								echo "		<label class='bmChoiceInput'>".$bm->name;
								echo "		<select id='".$bm->atomUid."Sel'>";
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
								echo "		<span class='iconebmChoice'  id='".$bm->atomUid."' data-icon='&#x3a;'></span>";
								echo "</li>";
							}
							else{
								echo "<li>";
								echo "		<label class='bmChoiceInput''> +".$bm->value." on ".$bm->forTargetNamed."</label>";
								echo "		<span class='iconebmRemChoice'  id='".$bm->atomUid."' data-icon='&#x39;'></span>";
								echo "</li>";

							}
							echo "<input id='".$bm->atomUid."Case' type='hidden' value='".EPBonusMalus::$ON_APTITUDE."'>";
						}
						else if($bm->targetForChoice == EPBonusMalus::$ON_REPUTATION){
							if($bm->forTargetNamed == null || $bm->forTargetNamed == ""){
								echo "<li>";
								echo "		<label class='bmChoiceInput'>".$bm->name;
								echo "		<select id='".$bm->atomUid."Sel'>";
								foreach($_SESSION['cc']->getReputations() as $apt){
									echo "<option value='".$apt->name."'>".$apt->name."</option>";
								}
								echo "		</select></label>";
								echo "		<span class='iconebmChoice'  id='".$bm->atomUid."' data-icon='&#x3a;'></span>";
								echo "</li>";
							}
							else{
								echo "<li>";
								echo "		<label class='bmChoiceInput''> +".$bm->value." on ".$bm->forTargetNamed."</label>";
								echo "		<span class='iconebmRemChoice'  id='".$bm->atomUid."' data-icon='&#x39;'></span>";
								echo "</li>";

							}
							echo "<input id='".$bm->atomUid."Case' type='hidden' value='".EPBonusMalus::$ON_REPUTATION."'>";
						}
						else if($bm->targetForChoice == EPBonusMalus::$MULTIPLE){
							echo "<li>";
							echo "		<label class='listSection'>Choose <span class='costInfo'>".$_SESSION['cc']->getSelectedOnMulti($bm)." / ".$bm->multi_occurence."</span></label>";
							echo "</li>";
							if($_SESSION['cc']->getSelectedOnMulti($bm) == $bm->multi_occurence){
								foreach($bm->bonusMalusTypes as $bmMulti){
									if($bmMulti->selected){
										if($bmMulti->targetForChoice == EPBonusMalus::$ON_SKILL_WITH_PREFIX){
											echo "<li>";
											echo "		<label class='bmChoiceInput''> +".$bmMulti->value." ".$bmMulti->typeTarget." : ".$bmMulti->forTargetNamed."</label>";
											echo "		<span class='iconebmRemChoice'  id='".$bmMulti->atomUid."' data-icon='&#x39;'></span>";
											echo "</li>";
										}
										else if($bmMulti->targetForChoice == EPBonusMalus::$ON_APTITUDE){
											echo "<li>";
											echo "		<label class='bmChoiceInput''> +".$bmMulti->value." on ".$bmMulti->forTargetNamed."</label>";
											echo "		<span class='iconebmRemChoice'  id='".$bmMulti->atomUid."' data-icon='&#x39;'></span>";
											echo "</li>";
										}
										else if($bmMulti->targetForChoice == EPBonusMalus::$ON_REPUTATION){
											echo "<li>";
											echo "		<label class='bmChoiceInput''> +".$bmMulti->value." on ".$bmMulti->forTargetNamed."</label>";
											echo "		<span class='iconebmRemChoice'  id='".$bmMulti->atomUid."' data-icon='&#x39;'></span>";
											echo "</li>";
										}
										else{
											echo "<li>";
											echo "		<label class='bmGranted' id='".$bmMulti->atomUid."'>".$bmMulti->name."</label>";
											echo "		<span class='iconebmSimpleRemChoice' id='".$bmMulti->atomUid."' data-icon='&#x39;'></span>";
											echo "</li>";
										}
									}
									echo "<input id='".$bmMulti->atomUid."MultiName' type='hidden' value='".$bmMulti->name."'>";
									echo "<input id='".$bmMulti->atomUid."ParentId' type='hidden' value='".$bm->atomUid."'>";
								}
							}
							else{
								foreach($bm->bonusMalusTypes as $bmMulti){
									if($bmMulti->targetForChoice == EPBonusMalus::$ON_SKILL_WITH_PREFIX){
										if($bmMulti->forTargetNamed == null || $bmMulti->forTargetNamed == ""){
											echo "<li>";
											echo "	 <label class='bmChoiceInput'>".$bmMulti->name;
											if(skillWithPrefixExist($totalSkills,$bmMulti->typeTarget)){
												echo "	 <select class='bmChoiceSelect' id='".$bmMulti->atomUid."Sel'>";
												foreach($totalSkills as $acSkill){
													if($acSkill->prefix == $bmMulti->typeTarget){
														echo "	 <option value='".$acSkill->name."'>".$acSkill->prefix." : ".$acSkill->name."</option>";
													}
												}
												echo "	 </select></label>";
												echo "	 <span class='iconebmChoice'  id='".$bmMulti->atomUid."' data-icon='&#x3a;'></span>";
											}
											else{
												echo "		<label class='bmGrantedDesc'>Create an appropriate skill (skills menus)</label>";
											}
											echo "</li>";
										}
										else{
											echo "<li>";
											echo "		<label class='bmChoiceInput''> +".$bmMulti->value." ".$bmMulti->typeTarget." : ".$bmMulti->forTargetNamed."</label>";
											echo "		<span class='iconebmRemChoice'  id='".$bmMulti->atomUid."' data-icon='&#x39;'></span>";
											echo "</li>";

										}
										echo "<input id='".$bmMulti->atomUid."MultiName' type='hidden' value='".$bmMulti->name."'>";
									}
									else if($bmMulti->targetForChoice == EPBonusMalus::$ON_SKILL_ACTIVE){
										if($bmMulti->forTargetNamed == null || $bmMulti->forTargetNamed == ""){
											echo "<li>";
											echo "	 <label class='bmChoiceInput'>".$bmMulti->name;
											echo "	 <select class='bmChoiceSelect' id='".$bmMulti->atomUid."Sel'>";
											foreach($activeSkillList as $acSkill){
												if(!empty($acSkill->prefix)) $sk_prefix = $acSkill->prefix." : ";
													else $sk_prefix = "";
													echo "	 <option value='".$acSkill->name."'>".$sk_prefix.$acSkill->name."</option>";
											}
											echo "	 </select></label>";
											echo "	 <span class='iconebmChoice'  id='".$bmMulti->atomUid."' data-icon='&#x3a;'></span>";
											echo "</li>";
										}
										else{
											echo "<li>";
											echo "		<label class='bmChoiceInput''> +".$bmMulti->value." ".$bmMulti->typeTarget." : ".$bmMulti->forTargetNamed."</label>";
											echo "		<span class='iconebmRemChoice'  id='".$bmMulti->atomUid."' data-icon='&#x39;'></span>";
											echo "</li>";

										}
										echo "<input id='".$bm->atomUid."Case' type='hidden' value='".EPBonusMalus::$ON_SKILL."'>";
									}
									else if($bmMulti->targetForChoice == EPBonusMalus::$ON_SKILL_KNOWLEDGE){
										if($bmMulti->forTargetNamed == null || $bmMulti->forTargetNamed == ""){
											echo "<li>";
											echo "	 <label class='bmChoiceInput'>".$bmMulti->name;
											echo "	 <select class='bmChoiceSelect' id='".$bmMulti->atomUid."Sel'>";
											foreach($knowledgeSkillList as $knSkill){
												if(!empty($knSkill->prefix)){
													$sk_prefix = $knSkill->prefix." : ";
												}
												else{
													$sk_prefix = "";
												}
												echo "	 <option value='".$knSkill->name."'>".$sk_prefix.$knSkill->name."</option>";
											}
											echo "	 </select></label>";
											echo "	 <span class='iconebmChoice'  id='".$bmMulti->atomUid."' data-icon='&#x3a;'></span>";
											echo "</li>";
										}
										else{
											echo "<li>";
											echo "		<label class='bmChoiceInput''> +".$bmMulti->value." ".$bmMulti->typeTarget." : ".$bmMulti->forTargetNamed."</label>";
											echo "		<span class='iconebmRemChoice'  id='".$bmMulti->atomUid."' data-icon='&#x39;'></span>";
											echo "</li>";

										}
										echo "<input id='".$bm->atomUid."Case' type='hidden' value='".EPBonusMalus::$ON_SKILL."'>";
									}
									else if($bmMulti->targetForChoice == EPBonusMalus::$ON_SKILL_ACTIVE_AND_KNOWLEDGE){
										if($bmMulti->forTargetNamed == null || $bmMulti->forTargetNamed == ""){
											echo "<li>";
											echo "	 <label class='bmChoiceInput'>".$bmMulti->name;
											echo "	 <select class='bmChoiceSelect' id='".$bmMulti->atomUid."Sel'>";
											foreach($totalSkills as $knSkill){
												if(!empty($knSkill->prefix)){
													$sk_prefix = $knSkill->prefix." : ";
												}
												else{
													$sk_prefix = "";
												}
												echo "	 <option value='".$knSkill->name."'>".$sk_prefix.$knSkill->name."</option>";
											}
											echo "	 </select></label>";
											echo "	 <span class='iconebmChoice'  id='".$bmMulti->atomUid."' data-icon='&#x3a;'></span>";
											echo "</li>";
										}
										else{
											echo "<li>";
											echo "		<label class='bmChoiceInput''> +".$bmMulti->value." ".$bmMulti->typeTarget." : ".$bmMulti->forTargetNamed."</label>";
											echo "		<span class='iconebmRemChoice'  id='".$bmMulti->atomUid."' data-icon='&#x39;'></span>";
											echo "</li>";

										}
										echo "<input id='".$bm->atomUid."Case' type='hidden' value='".EPBonusMalus::$ON_SKILL."'>";
								}

									else if($bmMulti->targetForChoice == EPBonusMalus::$ON_APTITUDE){
										if($bmMulti->forTargetNamed == null || $bmMulti->forTargetNamed == ""){
											echo "<li>";
											echo "		<label class='bmChoiceInput'>".$bmMulti->name;
											echo "		<select id='".$bmMulti->atomUid."Sel'>";
											foreach($_SESSION['cc']->getAptitudes() as $apt){
												echo "<option value='".$apt->name."'>".$apt->name."</option>";
											}
											echo "		</select></label>";
											echo "		<span class='iconebmChoice'  id='".$bmMulti->atomUid."' data-icon='&#x3a;'></span>";
											echo "</li>";
										}
										else{
											echo "<li>";
											echo "		<label class='bmChoiceInput''> +".$bmMulti->value." on ".$bmMulti->forTargetNamed."</label>";
											echo "		<span class='iconebmRemChoice'  id='".$bmMulti->atomUid."' data-icon='&#x39;'></span>";
											echo "</li>";

										}
										echo "<input id='".$bmMulti->atomUid."MultiName' type='hidden' value='".$bmMulti->name."'>";
									}
									else if($bmMulti->targetForChoice == EPBonusMalus::$ON_REPUTATION){
										if($bmMulti->forTargetNamed == null || $bmMulti->forTargetNamed == ""){
											echo "<li>";
											echo "		<label class='bmChoiceInput'>".$bmMulti->name;
											echo "		<select id='".$bmMulti->atomUid."Sel'>";
											foreach($_SESSION['cc']->getReputations() as $apt){
												echo "<option value='".$apt->name."'>".$apt->name."</option>";
											}
											echo "		</select></label>";
											echo "		<span class='iconebmChoice'  id='".$bmMulti->atomUid."' data-icon='&#x3a;'></span>";
											echo "</li>";
										}
										else{
											echo "<li>";
											echo "		<label class='bmChoiceInput''> +".$bmMulti->value." on ".$bmMulti->forTargetNamed."</label>";
											echo "		<span class='iconebmRemChoice'  id='".$bmMulti->atomUid."' data-icon='&#x39;'></span>";
											echo "</li>";

										}
										echo "<input id='".$bmMulti->atomUid."MultiName' type='hidden' value='".$bmMulti->name."'>";
									}
									else{
										if($bmMulti->selected){
											echo "<li>";
											echo "		<label class='bmGranted'>".$bmMulti->name."</label>";
											echo "		<input id='".$bmMulti->atomUid."Sel' type='hidden' value='".$bmMulti->forTargetNamed."'>";
											echo "		<span class='iconebmSimpleRemChoice'  id='".$bmMulti->atomUid."' data-icon='&#x39;'></span>";
											echo "</li>";
										}
										else{
											echo "<li>";
											echo "		<label class='bmGranted'>".$bmMulti->name."</label>";
											echo "		<input id='".$bmMulti->atomUid."Sel' type='hidden' value='".$bmMulti->forTargetNamed."'>";
											echo "		<span class='iconebmSimpleChoice'  id='".$bmMulti->atomUid."' data-icon='&#x3a;'></span>";
											echo "</li>";
										}
										echo "<input id='".$bmMulti->atomUid."MultiName' type='hidden' value='".$bmMulti->name."'>";
									}

									echo "<input id='".$bmMulti->atomUid."ParentId' type='hidden' value='".$bm->atomUid."'>";
								}
							}
							echo "<li>";
							echo "		<label class='listSectionClose'>-</label>";
							echo "</li>";
							echo "<input id='".$bm->atomUid."Case' type='hidden' value='".EPBonusMalus::$MULTIPLE."'>";
						}
						echo "<input id='".$bm->atomUid."Parent' type='hidden' value='".$parentName."'>";
						echo "<input id='".$bm->atomUid."Type' type='hidden' value='".$parentType."'>";
						echo "<input id='".$bm->atomUid."BmName' type='hidden' value='".$bm->name."'>";

					}
			}

		}
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
function skillWithPrefixExist($skillArray,$prefix){
	foreach($skillArray as $skill){
		if($skill->prefix == $prefix) return true;
	}
	return false;
}

function isNameOnList($name,$list){
	foreach($list as $s){
		if($name == $s) return true;
	}
	return false;
}
?>
