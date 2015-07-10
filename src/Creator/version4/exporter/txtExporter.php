<?php

	require_once '../../../php/EPCharacterCreator.php';
    require_once '../../../php/EPFileUtility.php';
	session_start();
	
	if(isset($_SESSION['cc']))
	{
        $file_util = new EPFileUtility($_SESSION['cc']->character);
        $filename = $file_util->buildExportFilename('EPCharacter', 'txt');

        header("Content-type: text/plain");
        header('Content-Disposition: attachment; filename="'.$filename.'"');
		
		//provider for book pages
		$p = new EPListProvider('../../../php/config.ini');	
		$morphs = $_SESSION['cc']->getCurrentMorphs();

		//TXT EXPORT ================================================================
	
			//USED CONSTANT
			$carriageReturn = "\r\n";
			$spaceBlock = "     ";
			$line = "-------------------------------------------------------------------------------------";
			$tab = "\t";
			
			
//EGO ================================================================ 
				
								
				//BEGIN FILLING SHEET------------------------------
				$character = $_SESSION['cc']->character;
				
				
				//EGO TITLE
				echo $carriageReturn 
				."====================================== EGO =========================================="
				.$carriageReturn;
				//NAMES
				echo formatTitle("Name")
				.$tab
				.": "
				.formatResult($character->charName)
				.$carriageReturn
				.formatTitle("Player")
				.$tab
				.": "
				.formatResult($character->playerName)
				.$carriageReturn
				.$line.$carriageReturn;
				
				//ORIGINES
				echo formatTitle("Background")
				.$tab
				.": "
				.formatResult($_SESSION['cc']->getCurrentBackground()->name)
				.$tab
				.setBookLink($_SESSION['cc']->getCurrentBackground()->name,$p)
				.$carriageReturn
				.formatTitle("Faction")
				.$tab
				.": "
				.formatResult($_SESSION['cc']->getCurrentFaction()->name)
				.$tab
				.setBookLink($_SESSION['cc']->getCurrentFaction()->name,$p)
				.$carriageReturn
				.$line.$carriageReturn;
				
				//AGE - SEX
				$birthGender = " ";
				if($character->birthGender == 'M') $birthGender= 'male';
				else $birthGender = 'female';
				
				echo formatTitle("Birth Gender")
				.$tab
				.": "
				.formatResult($birthGender)
				.$carriageReturn
				.formatTitle("Real Age")
				.$tab
				.": "
				.formatResult($character->realAge)
				.$carriageReturn
				.$line.$carriageReturn;
				
				//CREDIT
				echo formatTitle("Credit")
				.$tab
				.": "
				.formatResult($_SESSION['cc']->getCredit())
				.$tab
				."   (EP p.137)"
				.$carriageReturn
				.$line.$carriageReturn;

						
						
				//EGO APTITUDES
				echo formatTitle("Aptitudes")
				.$tab
				."   (EP p.122)"
				.$carriageReturn
				.$carriageReturn;
				
				$aptitudes = $_SESSION['cc']->getAptitudes();
				foreach($aptitudes as $apt){
					echo formatTitle($apt->name)
					.$tab //Apt Name
					.formatResult($apt->value)//Apt Value Ego
					.$carriageReturn;
				}	
						
				echo $line.$carriageReturn;	
						
					
				//REPUTATION
				echo formatTitle("Reputations")
				.$tab
				."   (EP p.285)"
				.$carriageReturn
				.$carriageReturn;
				
				$reputations = $_SESSION['cc']->getReputations();
				foreach($reputations as $rep){
					echo formatTitle($rep->name)
					.$tab //Rep Name
					.formatResult($rep->value)//Rep Value 
					.$carriageReturn;
				}	
						
				echo $line.$carriageReturn;	

								
					
				//MOTIVATION
				echo formatTitle("Motivations")
				.$tab
				."   (EP p.120)"
				.$carriageReturn
				.$carriageReturn;
				
				$motivations = $_SESSION['cc']->getMotivations();
				foreach($motivations as $mot){
					echo formatResult($mot)
					.$carriageReturn;
				}	
						
				echo $line.$carriageReturn;	
				
				//NOTES 
				echo formatTitle("Notes")
				.$carriageReturn
				.$carriageReturn;
	
				echo formatResult($character->note)
				.$carriageReturn;
							
				echo $line.$carriageReturn;	
				
				//EGO SKILLS
				echo formatTitle("Ego Skills")
				.$tab
				."   (EP p.176)"
				.$carriageReturn
				.$carriageReturn;
				
				$skillList = $_SESSION['cc']->getSkills();
				foreach($skillList as $skill){
				
					$skillCompleteName = "";
					$prefix = $skill->prefix;
					if(!empty($prefix)) $skillCompleteName = $prefix." : ";
					$skillCompleteName .= $skill->name;
					if($skill->defaultable == EPSkill::$NO_DEFAULTABLE) $skillCompleteName .= " *";
					$skillType = "A";
					if($skill->skillType == EPSkill::$KNOWLEDGE_SKILL_TYPE) {
						$skillType = "K";

						if($skill->getValue() == 0)
							continue;
					}
					
					echo formatResult($skillType." ".$skillCompleteName)
					.$tab
					.$skill->linkedApt->abbreviation
					.$tab.$tab;
					
					if(!empty($skill->specialization)){
						echo formatResult($skill->getEgoValue()."  SPE[".$skill->specialization."]");//Skill speci
					}
					else
					{
						echo formatResult($skill->getEgoValue());
					}
					 
					echo $carriageReturn;
				}	
						
				echo $line.$carriageReturn;	
				
				
			
				//EGO NEG TRAIT
				echo formatTitle("Ego Negative traits")
				.$carriageReturn
				.$carriageReturn;
				
				$egoNegTraits = filterPosNegTrait($_SESSION['cc']->getEgoTraits(),EPTrait::$NEGATIVE_TRAIT);
				foreach($egoNegTraits as $trait){
					echo formatResult($trait->name)
					.$tab
					.setBookLink($trait->name,$p)
					.$carriageReturn;
				}	
						
				echo $line.$carriageReturn;	
				
								
				//EGO POS TRAIT
				echo formatTitle("Ego Positive traits")
				.$carriageReturn
				.$carriageReturn;
				
				$egoNegTraits = filterPosNegTrait($_SESSION['cc']->getEgoTraits(),EPTrait::$POSITIVE_TRAIT);
				foreach($egoNegTraits as $trait){
					 echo formatResult($trait->name)
					.$tab
					.setBookLink($trait->name,$p)
					.$carriageReturn;
				}	
						
				echo $line.$carriageReturn;	
				
			
				//PSI SLEIGHTS
				echo formatTitle("Psi Sleights")
				.$carriageReturn
				.$carriageReturn;
				
				$psySleights =  $_SESSION['cc']->getCurrentPsySleights();
				foreach($psySleights as $sleight){
					 $type = "(P)";
					 if($sleight->psyType == EPPsySleight::$ACTIVE_PSY) $type="(A)";
					 echo formatResult($type." ".$sleight->name)
					.$tab
					.setBookLink($sleight->name,$p)
					.$carriageReturn;
				}	
						
				echo $line.$carriageReturn;	
				
				
				
				
				//SOFT GEAR
				echo formatTitle("Soft Gear")
				.$carriageReturn
				.$carriageReturn;
				
				$softGears = $_SESSION['cc']->getEgoSoftGears();
				foreach($softGears as $gear){
					if($gear->occurence > 1) $occ = "(".$gear->occurence.") ";
					else $occ = "";
					echo formatResult($occ." ".$gear->name)
					.$tab
					.setBookLink($gear->name,$p)
					.$carriageReturn;
				}	
						
				echo $line.$carriageReturn;	
				
				
				//AI
				echo formatTitle("Ai")
				.$carriageReturn
				.$carriageReturn;
				
				$ais = $_SESSION['cc']->getEgoAi();
				foreach($ais as $ai){
					if($ai->occurence > 1) $occ = "(".$ai->occurence.") ";
					else $occ = "";
					echo formatResult($occ." ".$ai->name)
					.$tab
					.setBookLink($ai->name,$p)
					.$carriageReturn
					.$carriageReturn;
					
					$skillAptNonformated = "";
					foreach($ai->aptitudes as $aiApt){
						$skillAptNonformated .= $aiApt->abbreviation."[";
						$skillAptNonformated .= $aiApt->value."]   ".$carriageReturn;
					}
					foreach($ai->skills as $aiSkill){
						$skillCompleteName = "";
						$prefix = $aiSkill->prefix;
						if(!empty($prefix)) $skillCompleteName = $prefix." : ";
						$skillCompleteName .= $aiSkill->name;
						$skillAptNonformated .= $skillCompleteName."(";
						$skillAptNonformated .= $aiSkill->baseValue.")  ".$carriageReturn;
					}
					
					echo formatResult($skillAptNonformated)
					.$carriageReturn;
					
				}	
						
				echo $line.$carriageReturn;	
				
				
				//MEMO (all ego bonus malus descriptive only)
				echo formatTitle("Ego Memo")
				.$carriageReturn
				.$carriageReturn;
				
				$egoBonusMalus = $_SESSION['cc']->getBonusMalusEgo();
				foreach($egoBonusMalus as $bm){
					
					echo formatResult($bm->name)
					.$carriageReturn;
					
					echo formatResult($bm->description)
					.$carriageReturn
					.$carriageReturn;
				}	
						
				echo $line.$carriageReturn;
				
//MORPHS ============================================================ 
					
					//DO ONE PAGE PER MORPH
					$morphs = $_SESSION['cc']->getCurrentMorphs();
					foreach($morphs as $morph){
						//ACTIVATE THE MORPH
						$_SESSION['cc']->activateMorph($morph);
						
						echo $carriageReturn 
						."====================================== MORPH ========================================" 
						.$carriageReturn;

				
						//DETAILS DATA
						if($morph->morphType == EPMorph::$BIOMORPH) $type = "[bio]";
						if($morph->morphType == EPMorph::$SYNTHMORPH) $type = "[synth]";
						if($morph->morphType == EPMorph::$INFOMORPH) $type = "[info]";
						if($morph->morphType == EPMorph::$PODMORPH) $type = "[pod]";
						
						$morphGender = " ";
						if($character->birthGender == 'M') $morphGender= 'male';
						else if($character->birthGender == 'F') $morphGender= 'female';
						else $morphGender = 'none';
						
						//NAMES
						echo formatTitle("Morph Name")
						.$tab
						.": "
						.formatResult($morph->name." ".$type)
						.setBookLink($morph->name,$p)
						.$carriageReturn
						.formatTitle("Nickname")
						.$tab
						.": "
						.formatResult($morph->nickname)
						.$carriageReturn
						.formatTitle("Apparent Age")
						.$tab
						.": "
						.formatResult($morph->age)
						.$carriageReturn
						.formatTitle("Morph Gender")
						.$tab
						.": "
						.formatResult($morphGender)
						.$carriageReturn
						.formatTitle("Location")
						.$tab
						.": "
						.formatResult($morph->location)
						.$carriageReturn
						.formatTitle("Player")
						.$tab
						.": "
						.formatResult($character->playerName)
						.$carriageReturn
						.$line.$carriageReturn;
						
						//MORPH NEG TRAIT
						echo formatTitle("Morph Negative traits")
						.$carriageReturn
						.$carriageReturn;
						
						$morphNegTraits = filterPosNegTrait($_SESSION['cc']->getCurrentTraits($morph),EPTrait::$NEGATIVE_TRAIT);
						foreach($morphNegTraits as $trait){
							echo formatResult($trait->name)
							.$tab
							.setBookLink($trait->name,$p)
							.$carriageReturn;
						}	
								
						echo $line.$carriageReturn;	
						
										
						//MORPH POS TRAIT
						echo formatTitle("Morph Positive traits")
						.$carriageReturn
						.$carriageReturn;
						
						$morphNegTraits = filterPosNegTrait($_SESSION['cc']->getCurrentTraits($morph),EPTrait::$POSITIVE_TRAIT);
						foreach($morphNegTraits as $trait){
							 echo formatResult($trait->name)
							.$tab
							.setBookLink($trait->name,$p)
							.$carriageReturn;
						}	
								
						echo $line.$carriageReturn;		
							
						
						//MORPH APTITUDES
						echo formatTitle("Morph Aptitudes")
						.$tab
						."   (EP p.122)"
						.$carriageReturn
						.$carriageReturn;
						
						echo formatTitle("") . $tab . "BASE" . $tab . "MORPH" . $tab . "TOTAL" . $carriageReturn;

						$aptitudes = $_SESSION['cc']->getAptitudes();
						foreach($aptitudes as $apt){
							echo formatTitle($apt->name)
							.$tab //Apt Name
							.$apt->value # base
							.$tab
							.$apt->morphMod
							.$tab
							.formatResult($apt->getValue())//Apt Value Morph
							.$carriageReturn;
						}	
								
						echo $line.$carriageReturn;	
						
						# MORPH STATS
						echo formatTitle("Morp Stats")
						.$tab
						."   (EP p.121)"
						.$carriageReturn
						.$carriageReturn;

						$stats = $_SESSION['cc']->getStats();

						foreach($stats as $stat) {
							echo formatResult($stat->name)
							.$tab
							.$stat->getValue()
							.$carriageReturn;
						}

						echo $line.$carriageReturn;
						
						//EGO SKILLS
						echo formatTitle("Morph Skills")
						.$tab
						."   (EP p.176)"
						.$carriageReturn
						.$carriageReturn;

						echo formatResult("")
						. $tab
						. "APT"
						. $tab
						. "EGO"
						. $tab
						. "MORPH"
						. $carriageReturn;


						$skillList = $_SESSION['cc']->getSkills();
						foreach($skillList as $skill){
						
							$skillCompleteName = "";
							$prefix = $skill->prefix;
							if(!empty($prefix)) $skillCompleteName = $prefix." : ";
							$skillCompleteName .= $skill->name;
							if($skill->defaultable == EPSkill::$NO_DEFAULTABLE) $skillCompleteName .= " *";
							$skillType = "A";

							# ignore knowledge skills with an empty value as the player didn't pick the skill
							if($skill->skillType == EPSkill::$KNOWLEDGE_SKILL_TYPE) {
								$skillType = "K";

								if($skill->getValue() == 0)
									continue;
							}

							echo formatResult($skillType." ".$skillCompleteName)
							. $tab
							. $skill->linkedApt->abbreviation
							. $tab
							. $skill->getEgoValue()
							. $tab;
							
							if(!empty($skill->specialization)){
								echo formatResult($skill->getValue()."  SPE[".$skill->specialization."]");//Skill speci
							}
							else
							{
								echo formatResult($skill->getValue());
							}
							 
							echo $carriageReturn;
						}	
								
						echo $line.$carriageReturn;	
						
						
						$morphGear = $_SESSION['cc']->getGearForCurrentMorph();
						
						//WEAPONS	
						$weapons = filterWeaponOnly($morphGear);
						
						echo formatTitle("Weapons")
						.$carriageReturn
						.$carriageReturn;
						
						foreach($weapons as $w){
							$type = "kinetic";
							if($w->gearType == EPGear::$WEAPON_ENERGY_GEAR) $type="energy";
							if($w->gearType == EPGear::$WEAPON_EXPLOSIVE_GEAR) $type="explos.";
							if($w->gearType == EPGear::$WEAPON_SPRAY_GEAR) $type="spray";
							if($w->gearType == EPGear::$WEAPON_SEEKER_GEAR) $type="seeker";
							if($w->gearType == EPGear::$WEAPON_AMMUNITION) $type="ammo";
							if($w->gearType == EPGear::$WEAPON_MELEE_GEAR) $type="melee";
							if($w->occurence > 1) $occ = "(".$w->occurence.") ";
							else $occ = "";
							
							echo formatResultXL("[".$type."] ".$occ.$w->name."  "."DV: ".$w->degat."  "."AP : ".$w->armorPenetration)//Weapon type 
							.$tab
							.setBookLink($w->name,$p)
							.$carriageReturn;
						}
						
						echo $line.$carriageReturn;
						
						//ARMORS		
						$armor = filterArmorOnly($morphGear);
						
						echo formatTitle("Armor")
						.$carriageReturn
						.$carriageReturn;

						$protectionKinetic = 0;
						$protectionEnergy = 0;

						foreach($armor as $a){
							if($a->occurence > 1) $occ = "(".$a->occurence.") ";
							else $occ = "";
							$protec = "";
							if($a->armorKinetic == 0 && $a->armorEnergy==0){
								$protec = "see memo";//No protec, see memeo
							}
							else{
								$protec = "Kin: ". formatNumber($a->armorKinetic) . "  Ene: " . formatNumber($a->armorEnergy);

								$protectionKinetic += $a->armorKinetic;
								$protectionEnergy += $a->armorEnergy;
							}
							echo formatResult($occ.$a->name . ($a->gearType == EPGear::$IMPLANT_GEAR ? " (Implant)" : ""))//armor 
							.$tab
							.$protec
							.$tab
							.setBookLink($a->name,$p)
							.$carriageReturn;
						}
						
						# total protection
						if($protectionKinetic > 0 || $protectionEnergy > 0) {
							echo formatResult("")
							. $tab
							. "Kin: " . formatNumber($protectionKinetic) . "  Ene: " . formatNumber($protectionEnergy)
							. $carriageReturn;
						}

						echo $line.$carriageReturn;
						
						//GEARS
						
						echo formatTitle("Gears")
						.$carriageReturn
						.$carriageReturn;
						
						$gears = filterGeneralOnly($morphGear);
						foreach($gears as $g){
							if($g->occurence > 1) $occ = "(".$g->occurence.") ";
							else $occ = "";
							
							echo formatResult($occ." ".$g->name)
							.$tab
							.setBookLink($g->name,$p)
							.$carriageReturn;
						}	
								
						echo $line.$carriageReturn;	
						
						
						//IMPLANTS
						
						echo formatTitle("Implants")
						.$carriageReturn
						.$carriageReturn;
						
						$implants = filterImplantOnly($morphGear);
						foreach($implants as $i){
							if($i->occurence > 1) $occ = "(".$i->occurence.") ";
							else $occ = "";
							
							echo formatResult($occ.$i->name)
							.$tab
							.setBookLink($i->name,$p)
							.$carriageReturn;
						}	
								
						echo $line.$carriageReturn;	

						
											
						//MEMO (all ego bonus malus descriptive only)
						echo formatTitle("Morph Memo")
						.$carriageReturn
						.$carriageReturn;
						
						$morphBonusMalus = $_SESSION['cc']->getBonusMalusForMorph($morph);
						foreach($morphBonusMalus as $bm){
							
							echo formatResult($bm->name)
							.$carriageReturn;
							
							echo formatResult($bm->description)
							.$carriageReturn
							.$carriageReturn;
						}	
								
						echo $line.$carriageReturn;
				}
												
		
	} 
	//NO CHARACTER CREATOR ! ================================================
	else{
        header("Status: 500 Internal Server Error", true, 500);
        echo "Bad news, something went wrong, we can not print your character, verify your character and try again.";
        die;
	}
	
	
	
	//HELPERS ===============================================================
	
	function formatTitle($string){
		return padString($string, 12);
	}
	
	function formatResult($string){
		return padString($string, 25);
	}
	
	function formatResultLong($string){
		return padString($string, 40);
	}
	
	function formatResultXL($string){
		return padString($string, 60);
	}

	function formatNumber($string) {
		return padString($string, 3);
	}

	function padString($string, $pad) {
		$res = $string;
		if($res == null) $res = " ";
		$res = str_pad($res, $pad);
		return $res;
	}
	
	function formatItForRect($string,$length){
		$final_result = str_split(formatIt($string),$length);
		return $final_result;
	}
	
	function getImplants($objArray){
		$final = "*";
		foreach($objArray as $m){
			if($m->gearType == EPGear::$IMPLANT_GEAR){
				$final .= $m->name."*";
			}
		}
		return $final;
	}
	
	function getGear($objArray){
		$final = "*";
		foreach($objArray as $m){
			if($m->gearType =! EPGear::$IMPLANT_GEAR){
				$final .= $m->name."*";
			}
		}
		return $final;
	}
	
	function getTraits($objArray){
		$final = "*";
		foreach($objArray as $m){
			$final .= $m->name."*";
		}
		return $final;
	}
	
	function filterPosNegTrait($traits,$type){
		$result = array();
		foreach($traits as $t){
			if($t->traitPosNeg == $type){
				array_push($result, $t);
			}
		}
		return $result;
	}
	
	function getDescOnlyBM($bonusMalus){
		$result = array();
		foreach($bonusMalus as $bm){
			if($bm->bonusMalusType == EPBonusMalus::$DESCRIPTIVE_ONLY){
				array_push($result, $bm);
			}
		}
		return $result;
	}
	
	function getMorphMemoBM($bonusMalus){
		$result = array();
		foreach($bonusMalus as $bm){
			if( $bm->bonusMalusType == EPBonusMalus::$DESCRIPTIVE_ONLY ||
				$bm->bonusMalusType == EPBonusMalus::$ON_ARMOR ||
				$bm->bonusMalusType == EPBonusMalus::$ON_ENERGY_ARMOR ||
				$bm->bonusMalusType == EPBonusMalus::$ON_KINETIC_ARMOR ||
				$bm->bonusMalusType == EPBonusMalus::$ON_ENERGY_WEAPON_DAMAGE ||
				$bm->bonusMalusType == EPBonusMalus::$ON_KINETIC_WEAPON_DAMAGE ||
				$bm->bonusMalusType == EPBonusMalus::$ON_MELEE_WEAPON_DAMAGE){
				array_push($result, $bm);
			}
		}
		return $result;
	}
	
	function filterWeaponOnly($gears){
		$result = array();
		foreach($gears as $g){
			if( $g->gearType == EPGear::$WEAPON_MELEE_GEAR ||
				$g->gearType == EPGear::$WEAPON_ENERGY_GEAR ||	
				$g->gearType == EPGear::$WEAPON_KINETIC_GEAR ||
				$g->gearType == EPGear::$WEAPON_AMMUNITION ||
				$g->gearType == EPGear::$WEAPON_SEEKER_GEAR ||
				$g->gearType == EPGear::$WEAPON_SPRAY_GEAR){
					array_push($result, $g);
			}
			if( $g->gearType == EPGear::$IMPLANT_GEAR ){
				if($g->degat != "0"){
					array_push($result, $g);
				}
				
			}
		}
		return $result;
	}
	
	function filterArmorOnly($gears){
		$result = array();
		foreach($gears as $g){
			if( $g->gearType == EPGear::$ARMOR_GEAR){
					array_push($result, $g);
			}
			else if($g->gearType == EPGear::$IMPLANT_GEAR && ($g->armorKinetic >0 || $g->armorEnergy > 0)) {
				array_push($result, $g);
			}
		}
		return $result;
	}
	
	function filterImplantOnly($gears){
		$result = array();
		foreach($gears as $g){
			if( $g->gearType == EPGear::$IMPLANT_GEAR){
					array_push($result, $g);
			}
		}
		return $result;
	}

	
	function filterGeneralOnly($gears){
		$result = array();
		foreach($gears as $g){
			if( $g->gearType == EPGear::$STANDARD_GEAR ||
				$g->gearType == EPGear::$DRUG_GEAR ||	
				$g->gearType == EPGear::$CHEMICALS_GEAR ||
				$g->gearType == EPGear::$POISON_GEAR ||
				$g->gearType == EPGear::$PET_GEAR ||
				$g->gearType == EPGear::$VEHICLES_GEAR ||
				$g->gearType == EPGear::$ROBOT_GEAR ||
				$g->gearType == EPGear::$FREE_GEAR ){
					array_push($result, $g);
			}
		}
		return $result;
	}
	
	function setBookLink($atomeName,$provider){
		$bookFullName = $provider->getBookForName($atomeName);
		if($bookFullName == EPListProvider::$BOOK_ECLIPSEPHASE) $book = "EP";
		else if($bookFullName == EPListProvider::$BOOK_TRANSHUMAN) $book = "TH";
		else if($bookFullName == EPListProvider::$BOOK_GATECRASHING) $book = "GC";
		else if($bookFullName == EPListProvider::$BOOK_SUNWARD) $book = "SW";
		else if($bookFullName == EPListProvider::$BOOK_PANOPTICON) $book = "PAN";
		else if($bookFullName == EPListProvider::$BOOK_RIMWARD) $book = "RW";
		else $book = "??";
		$page = $provider->getPageForName($atomeName);
		return "   (".$book." p.".$page.")";
	}
	
	
?>