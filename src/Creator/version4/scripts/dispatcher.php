<?php

require_once '../../../php/EPCharacterCreator.php';
require_once '../../../php/EPListProvider.php';
require_once '../../../php/EPAptitude.php';
require_once '../../../php/EPStat.php';
require_once '../../../php/EPReputation.php';
require_once '../../../php/EPConfigFile.php';
require_once '../../../php/EPBonusMalus.php';
require_once '../../../php/EPTrait.php';
require_once '../../../php/EPBackground.php';
require_once '../../../php/EPGear.php';
require_once '../../../php/EPAi.php';
require_once '../../../php/EPPsySleight.php';
require_once '../../../php/EPCharacter.php';
require_once '../../../php/EPEgo.php';
require_once '../../../php/EPMorph.php';

session_start();

header('Content-type: application/json');


//FUNCTION-HELPERS=============================
function returnErrors(&$data,$msg=""){
    $data['error'] = true;
    $data['erType'] = "system";
    $data['msg'] = "SYSTEM ERROR : ".$msg;
}

function returnMessage(&$data,$msg=""){
    $data['error'] = true;
    $data['erType'] = "rules";
    $data['msg'] = "RULES : ".$msg;
}

function treatCreatorErrors(&$data,$creatorError){
    if(is_string($creatorError)) {
        returnErrors($data,$creatorError);
    }
	else if(strcmp($creatorError->typeError, EPCreatorErrors::$SYSTEM_ERROR) == 0){
		returnErrors($data,$creatorError->textError);
	}
	else if(strcmp($creatorError->typeError, EPCreatorErrors::$RULE_ERROR) == 0){
		returnMessage($data,$creatorError->getTextOnly());
	}
	else{
		returnErrors($data,"Unknown error ? : ".$creatorError->textError);
	}
}

//DISPATCH============================

//INIT
$return = array();
$return['error'] = false;
$_SESSION['versioningFault'] = false;
$return['versioningFault'] = false; 
$provider = new EPListProvider('../../../php/config.ini');

	//error_log(print_r($_POST,true));
	//error_log(print_r($_FILES,true));
	//error_log(print_r($_SESSION,true));

//if a file to load LOAD FILE
if (isset($_SESSION['fileToLoad'])) {
    $_SESSION['cc'] = new EPCharacterCreator("../../../php/config.ini");
    $_SESSION['cc']->back = new EPCharacterCreator("../../../php/config.ini");		

    $saveFile = json_decode($_SESSION['fileToLoad'],true);
 
    if (!empty($saveFile['versionNumber']) && floatval($saveFile['versionNumber']) >= $_SESSION['cc']->configValues->getValue('GeneralValues','versionNumberMin')){	
		$_SESSION['cc']->loadSavePack($saveFile);
        $_SESSION['cc']->back->loadSavePack($saveFile);
        $_SESSION['cc']->back->setMaxRepValue($_SESSION['cc']->configValues->getValue('RulesValues','EvoMaxRepValue'));
        $_SESSION['cc']->setMaxRepValue($_SESSION['cc']->configValues->getValue('RulesValues','EvoMaxRepValue'));
        $_SESSION['cc']->back->setMaxSkillValue($_SESSION['cc']->configValues->getValue('RulesValues','SkillEvolutionMaxPoint'));
        $_SESSION['cc']->setMaxSkillValue($_SESSION['cc']->configValues->getValue('RulesValues','SkillEvolutionMaxPoint'));
                    
        if ($_SESSION['cc']->creationMode == false){ //We are alreay in evo mode
            $_SESSION['creationMode'] = false; //We force evo mode
            $_SESSION['cc']->evoRezPoint += $_SESSION['rezPoints'];
			$_SESSION['cc']->evoRepPoint += $_SESSION['repPoints'];
			$_SESSION['cc']->evoCrePoint += $_SESSION['credPoints'];
        }else{//We are in creation mode
            if(!$_SESSION['creationMode']){//We pass in evo mode
                $_SESSION['cc']->creationMode = false;
                $_SESSION['cc']->evoCrePoint = $_SESSION['cc']->getCredit(); // we keep credits from creation mode
				$_SESSION['cc']->evoRezPoint += $_SESSION['rezPoints'];
				$_SESSION['cc']->evoRepPoint += $_SESSION['repPoints'];
				$_SESSION['cc']->evoCrePoint += $_SESSION['credPoints'];
            }else{//We stay in creation mode
                $_SESSION['cc']->creationMode = true;
            }            
        }
        
        if (!empty($_SESSION['cc']->character->morphs)){
            $_SESSION['cc']->activateMorph($_SESSION['cc']->character->morphs[0]);
        }
                
		$_SESSION['fileToLoad'] = null; 
        $_SESSION['cc']->adjustAll();            
	}else{ 
        $_SESSION['versioningFault'] = true;
        $_SESSION['fileToLoad'] = null;  
        $return['versioningFault'] = true; 
    }
}

//FIRST RUN
if (isset($_POST['firstTime'])) {
	if (!isset($_SESSION['cc'])){
     	$return['sessionExist'] = false;
     }
     else{
	    $return['sessionExist'] = true; 
     }
}

//SET CP FOR A NEW CHARACTER
if(isset($_POST['setCP'])){
	//CHARACTER CREATOR
    $_SESSION['cc'] = new EPCharacterCreator("../../../php/config.ini",$_POST['setCP']);
    $_SESSION['ccRef'] = null;
    //error_log("NEW CHAR");
}

//INFOS
if(isset($_POST['infosId'])){
	if($provider->getInfosById($_POST['infosId'])!= null){
		 $return['infoData'] = $provider->getInfosById($_POST['infosId']);
	}
	else{
        treatCreatorErrors($return, $_SESSION['cc']->getLastError());
    }
}
//GET ORIGINE
if(isset($_POST['getBcg'])){
	if($_SESSION['cc']->getCurrentBackground() != null){
    	$return['currentBcg'] = $_SESSION['cc']->getCurrentBackground()->name;
    	$_SESSION['currentOrigineName'] = $_SESSION['cc']->getCurrentBackground()->name;
    	//$return['desc'] = $_SESSION['cc']->getCurrentBackground()->description;
    }
    else{
	    $return['currentBcg'] = null;
    }
}
//SET ORIGINE
if (isset($_POST['origine'])) {
    if($_SESSION['cc']->getBackgroundByName($_POST['origine']) != null &&
       $_SESSION['cc']->setBackground($_SESSION['cc']->getBackgroundByName($_POST['origine']))){
       $_SESSION['currentOrigineName'] = $_POST['origine'];
        //$return['desc'] = $_SESSION['cc']->getBackgroundByName($_POST['origine'])->description;
    }
    else{
        treatCreatorErrors($return, $_SESSION['cc']->getLastError());
    }
	
}
//GET FACTION
if(isset($_POST['getFac'])){
	if($_SESSION['cc']->getCurrentFaction() != null){
    	$return['currentFac'] = $_SESSION['cc']->getCurrentFaction()->name;
    	$_SESSION['currentFactionName'] = $_SESSION['cc']->getCurrentFaction()->name;
		//$return['desc'] = $_SESSION['cc']->getCurrentFaction()->description;
    }
    else{
	    $return['currentFac'] = null;
    }
}
//SET FACTION
if(isset($_POST['faction'])){
    if($_SESSION['cc']->getBackgroundByName($_POST['faction']) != null &&
       $_SESSION['cc']->setFaction($_SESSION['cc']->getBackgroundByName($_POST['faction']))){
       $_SESSION['currentFactionName'] = $_SESSION['cc']->getCurrentFaction()->name;
        //$return['desc'] = $_SESSION['cc']->getBackgroundByName($_POST['faction'])->description;
    }
    else{
        treatCreatorErrors($return, $_SESSION['cc']->getLastError());
    }
}

//SET POS TRAIT
if(isset($_POST['posTrait'])){
	$trait = $_SESSION['cc']->getTraitByName($_POST['posTrait']);
	if($trait != null){
		if($_SESSION['cc']->isAtomInArrayByName($trait->name, $_SESSION['cc']->getCurrentTraits())){
			if($_SESSION['cc']->removeTrait($trait)){
				$_SESSION['currentTraitName'] = $trait->name;
	        	$return['desc'] = $trait->description;
		    }
		    else{
		        treatCreatorErrors($return, $_SESSION['cc']->getLastError());
		    }
		}
	    else if($_SESSION['cc']->addTrait($trait)){
	    	$_SESSION['currentTraitName'] = $trait->name;
	        $return['desc'] = $trait->description;
	    }
	    else{
	        treatCreatorErrors($return, $_SESSION['cc']->getLastError());
	    }
    }
}

//HOVER POS TRAIT
if(isset($_POST['posTraitHover'])){
	$trait = $_SESSION['cc']->getTraitByName($_POST['posTraitHover']);
	if($trait != null){
	        $_SESSION['currentTraitName'] = $trait->name;
	}
}

//SET PSY SLEIGHT
if(isset($_POST['psyS'])){
	$psyS = $_SESSION['cc']->getPsySleightsByName($_POST['psyS']);
	if($psyS != null){
		if($_SESSION['cc']->isAtomInArrayByName($psyS->name, $_SESSION['cc']->getCurrentPsySleights())){
			if($_SESSION['cc']->removePsySleight($psyS)){
	        	$return['desc'] = $psyS->description;
	        	$_SESSION['currentPsiSName'] = $psyS->name;
		    }
		    else{
		        treatCreatorErrors($return, $_SESSION['cc']->getLastError());
		    }
		}
	    else if($_SESSION['cc']->addPsySleight($psyS)){
	        $return['desc'] = $psyS->description;
	        $_SESSION['currentPsiSName'] = $psyS->name;
	    }
	    else{
	        treatCreatorErrors($return, $_SESSION['cc']->getLastError());
	    }
    }
}
//HOVER PSY SLEIGHT
if(isset($_POST['hoverPsyS'])){
	$psyS = $_SESSION['cc']->getPsySleightsByName($_POST['hoverPsyS']);
	$_SESSION['currentPsiSName'] = $psyS->name;
}

//SET NEG TRAIT
if(isset($_POST['negTrait'])){
	$trait = $_SESSION['cc']->getTraitByName($_POST['negTrait']);
	if($trait != null){
		if($_SESSION['cc']->isAtomInArrayByName($trait->name, $_SESSION['cc']->getCurrentTraits())){
                    if($_SESSION['cc']->removeTrait($trait)){
			$_SESSION['currentTraitName'] = $trait->name;
	        	$return['desc'] = $trait->description;
		    }
		    else{
		        treatCreatorErrors($return, $_SESSION['cc']->getLastError());
		    }
		}
	    else if($_SESSION['cc']->addTrait($trait)){
	    	$_SESSION['currentTraitName'] = $trait->name;
	        $return['desc'] = $trait->description;
	    }
	    else{
	        treatCreatorErrors($return, $_SESSION['cc']->getLastError());
	    }
    }

}

//HOVER NEG TRAIT
if(isset($_POST['negTraitHover'])){
	$trait = $_SESSION['cc']->getTraitByName($_POST['negTraitHover']);
	if($trait != null){
	        $_SESSION['currentTraitName'] = $trait->name;
	}
}

//SET MOTIVATION
if(isset($_POST['newMot'])){
	if($_POST['newMot'] != ""){
	    if(!$_SESSION['cc']->addMotivation($_POST['newMot'])){
	        treatCreatorErrors($return, $_SESSION['cc']->getLastError());
	    }
	}
}
//REMOVE MOTIVATION
if(isset($_POST['remMot'])){
	if($_POST['remMot'] != ""){
	    if(!$_SESSION['cc']->removeMotivation($_POST['remMot'])){
	        treatCreatorErrors($return, $_SESSION['cc']->getLastError());
	    }
	}
}

//SET APTITUDES
if(isset($_POST['cog']) && isset($_POST['coo']) && isset($_POST['int']) &&
   isset($_POST['ref']) && isset($_POST['sav']) && isset($_POST['som']) &&
   isset($_POST['wil'])){
   $errorOnApt = false;
    if(!$_SESSION['cc']->setAptitudeValue("COG", intval($_POST['cog']))){
	   $errorOnApt = true; 
	   $return['aptError'] = 'COG';
    }
    if(!$_SESSION['cc']->setAptitudeValue("COO", intval($_POST['coo']))){
	    $errorOnApt = true;
	    $return['aptError'] = 'COO';
    }
    if(!$_SESSION['cc']->setAptitudeValue("INT", intval($_POST['int']))){
	    $errorOnApt = true;
	    $return['aptError'] = 'INT';
    }
    if(!$_SESSION['cc']->setAptitudeValue("REF", intval($_POST['ref']))){
	    $errorOnApt = true;
	    $return['aptError'] = 'REF';
    }
    if(!$_SESSION['cc']->setAptitudeValue("SAV", intval($_POST['sav']))){
	    $errorOnApt = true;
	    $return['aptError'] = 'SAV';
    }
    if(!$_SESSION['cc']->setAptitudeValue("SOM", intval($_POST['som']))){
	    $errorOnApt = true;
	    $return['aptError'] = 'SOM';
    }
    if(!$_SESSION['cc']->setAptitudeValue("WIL", intval($_POST['wil']))){
		$errorOnApt = true;
		$return['aptError'] = 'WIL';
    }
    
    if($errorOnApt) {
        //error_log('ERROR :'.$_SESSION['cc']->getLastError()->typeError);
         treatCreatorErrors($return, $_SESSION['cc']->getLastError());
    }
}

//GET APTITUDE DESC
if(isset($_POST['apt'])){
	$aptitude = $_SESSION['cc']->getAptitudeByAbbreviation($_POST['apt']);
	if(isset($aptitude)){
		$return['desc'] = $aptitude->description;
	}
	else{
		treatCreatorErrors($return, $_SESSION['cc']->getLastError());
	}
}

//SET REPUTATION
if(isset($_POST['atrep']) && isset($_POST['grep']) && isset($_POST['crep']) &&
   isset($_POST['irep']) && isset($_POST['erep']) && isset($_POST['rrep']) &&
   isset($_POST['frep'])){
    $errorOnRep = false;
   
    if(!$_SESSION['cc']->setReputation("@-Rep", intval($_POST['atrep']))){
	    $errorOnRep = true; 
	    $return['repError'] = '@-Rep';
    } 
    if(!$_SESSION['cc']->setReputation("G-Rep", intval($_POST['grep']))){
	    $errorOnRep = true; 
	    $return['repError'] = 'G-Rep';
    } 
    if(!$_SESSION['cc']->setReputation("C-Rep", intval($_POST['crep']))){
	   $errorOnRep = true; 
	    $return['repError'] = 'C-Rep'; 
    } 
    if(!$_SESSION['cc']->setReputation("I-Rep", intval($_POST['irep']))){
	    $errorOnRep = true; 
	    $return['repError'] = 'I-Rep';
    } 
    if(!$_SESSION['cc']->setReputation("E-Rep", intval($_POST['erep']))){
	    $errorOnRep = true; 
	    $return['repError'] = 'E-Rep';
    } 
    if(!$_SESSION['cc']->setReputation("R-Rep", intval($_POST['rrep']))){
	    $errorOnRep = true; 
	    $return['repError'] = 'R-Rep';
    } 
    if(!$_SESSION['cc']->setReputation("F-Rep", intval($_POST['frep']))){
		$errorOnRep = true; 
	    $return['repError'] = 'F-Rep';
    }
    if($errorOnRep) {
         treatCreatorErrors($return, $_SESSION['cc']->getLastError());
    }
}

//GET REPUTATION DESC
if(isset($_POST['rep'])){
	$reputation = $_SESSION['cc']->getReputationByName($_POST['rep']);
	if(isset($reputation)){
		$return['desc'] = $reputation->description;
	}
	else{
		treatCreatorErrors($return, $_SESSION['cc']->getLastError());
	}
}

//GET SKILL DECRIPTION
if(isset($_POST['skill'])){
	$skill = $_SESSION['cc']->getSkillByName($_POST['skill']);
	if($skill->description == null || $skill->description == ""){
		$prefix = $skill->prefix;
		if($prefix != null || $prefix != ""){
			$return['desc'] = $provider->getPrefixDescription($prefix);
		}
		else{
			$return['desc'] = "No description available, sorry ...";
		}
	}
	else{
		$return['desc'] = $skill->description;
	}
}

//ADD TMP ACTIVE SKILL
if(isset($_POST['newTmpActSkill'])){
	if(!$_SESSION['cc']->addSkill($_POST['newTmpActSkill'], 
									  $provider->getAptForPrefix($_POST['newTmpActSkillPrefix']), 
									  EPSkill::$ACTIVE_SKILL_TYPE, 
									  EPSkill::$NO_DEFAULTABLE, 
									  $_POST['newTmpActSkillPrefix'])){
										  treatCreatorErrors($return, $_SESSION['cc']->getLastError());
									  }
}

//ADD TMP KNOWLEDGE SKILL
if(isset($_POST['newTmpKnoSkill'])){
	if(!$_SESSION['cc']->addSkill($_POST['newTmpKnoSkill']." ", 
									  $provider->getAptForPrefix($_POST['newTmpKnoSkillPrefix']), 
									  EPSkill::$KNOWLEDGE_SKILL_TYPE, 
									  EPSkill::$NO_DEFAULTABLE, 
									  $_POST['newTmpKnoSkillPrefix'])){
										  treatCreatorErrors($return, $_SESSION['cc']->getLastError());
									  }
}

//ADD NATIVE LANGUAGE SKILL
if(isset($_POST['newNatLanguageSkill'])){
	if(!$_SESSION['cc']->addSkill($_POST['newNatLanguageSkill'], 
									  $provider->getAptForPrefix('Language'), 
									  EPSkill::$KNOWLEDGE_SKILL_TYPE, 
									  EPSkill::$DEFAULTABLE, 
									  "Language",
									  null,
									  true)){
										  treatCreatorErrors($return, $_SESSION['cc']->getLastError());
									  }
}



//REMOVE TMP SKILL
if(isset($_POST['remSkill'])){
	$skill = $_SESSION['cc']->getSkillByName($_POST['remSkill']);
	if($skill != null){
		if(!$_SESSION['cc']->removeSkill($skill)){
			treatCreatorErrors($return, $_SESSION['cc']->getLastError());
		}
	}
	else{
		treatCreatorErrors($return, $_SESSION['cc']->getLastError());
	}
}

//CHANGE SKILL VALUE
if(isset($_POST['changeSkillName'])){
	if(!$_SESSION['cc']->setSkillValue($_POST['changeSkillName'], intval($_POST['changeSkillValue']))){
		treatCreatorErrors($return, $_SESSION['cc']->getLastError());
	}
}

//ADD SKILL SPECIALIZATION
if(isset($_POST['addSpe'])){
	$skill = $_SESSION['cc']->getSkillByName($_POST['addSpeSkillName']);
	if($skill == null){
		treatCreatorErrors($return, $_SESSION['cc']->getLastError());
	}
	else if(!$_SESSION['cc']->addSpecialization($_POST['addSpe'],$skill)){
		treatCreatorErrors($return, $_SESSION['cc']->getLastError());
	}
}

//REMOVE SKILL SPECIALIZATION
if(isset($_POST['remSpeSkillName'])){
	$skill = $_SESSION['cc']->getSkillByName($_POST['remSpeSkillName']);
	if($skill == null){
		treatCreatorErrors($return, $_SESSION['cc']->getLastError());
	}
	else if(!$_SESSION['cc']->removeSpecialization($skill)){
		treatCreatorErrors($return, $_SESSION['cc']->getLastError());
	}
}

//SET MORPH
if (isset($_POST['addMorph'])) {
	   $morph = $provider->getAtomByName($_SESSION['cc']->getMorphs(),$_POST['addMorph']);
	   if($_SESSION['cc']->addMorph($morph)){
                $_SESSION['currentMorph'] =  $_POST['addMorph'];
                $return['title'] = $morph->name;
		        $return['desc'] = $morph->description;
	   }
	   else{
			treatCreatorErrors($return, $_SESSION['cc']->getLastError());
	}
}

//HOVER MORPH
if (isset($_POST['morphHover'])) {
	   $morph = $provider->getAtomByName($_SESSION['cc']->getMorphs(),$_POST['morphHover']);
       $return['title'] = $morph->name;
	   $return['desc'] = $morph->description;
}



//REMOVE MORPH
if (isset($_POST['remMorph'])) {
	   $morph = $provider->getAtomByName($_SESSION['cc']->getMorphs(),$_POST['remMorph']);
	   if($_SESSION['cc']->removeMorph($morph)){
                $return['desc'] = $morph->description;
	   }
	   else{
			treatCreatorErrors($return, $_SESSION['cc']->getLastError());
	}
}

//GET MORPH SETTINGS
if (isset($_POST['morphSettings'])) {
	   $morph = $provider->getAtomByName($_SESSION['cc']->character->morphs,$_POST['morphSettings']);
	   if($morph != null){
	   		 $_SESSION['currentMorph'] =  $_POST['morphSettings'];
	   		 $return['morphName'] = $morph->name;
		     $return['nickname'] = $morph->nickname;
		     $return['location'] = $morph->location;
		     $return['age'] = $morph->age;
		     $return['gender'] = $morph->gender;
		     $return['morphDur'] = $morph->durability;
		     $return['morphMaxApt'] = $morph->maxApptitude;
	   }
	   else{
			treatCreatorErrors($return, $_SESSION['cc']->getLastError());
	}
}

//MORPH SELECTED ON GUI
if (isset($_POST['currentMorphUsed'])) {
	 $_SESSION['currentMorph'] =  $_POST['currentMorphUsed']; 
}


//SET MORPH SETTINGS
if (isset($_POST['morphSettingsChange'])) {
	   $morph = $provider->getAtomByName($_SESSION['cc']->getMorphs(),$_POST['morphSettingsChange']);
	   if($morph != null){
	   		 $_SESSION['currentMorph'] =  $_POST['morphSettingsChange'];
		     $morph->nickname = $_POST['morphNickname'];
		     $morph->location = $_POST['morphLocation'];
		     $morph->age = $_POST['morphAge'];
		     $morph->gender = $_POST['morphGender'];
	   }
	   else{
			treatCreatorErrors($return, $_SESSION['cc']->getLastError());
	}
}

//SET REMOVE MORPH POS TRAIT
if(isset($_POST['morphPosTrait'])){
    $morph = $_SESSION['cc']->getCurrentMorphsByName($_SESSION['currentMorph']);
    $trait = $_SESSION['cc']->getTraitByName($_POST['morphPosTrait']);
    
    if (isset($morph)){
        if (isset($trait)){
           if ($_SESSION['cc']->haveTraitOnMorph($trait->name,$morph)){
                if ($_SESSION['cc']->removeTrait($trait,$morph)){
                    $return['desc'] = $trait->description;
                    $_SESSION['currentMorphTraitName'] = $trait->name;
                    
                }else{
                    treatCreatorErrors($return, $_SESSION['cc']->getLastError());
                }        
            }else{
                if ($_SESSION['cc']->addTrait($trait,$morph)){
                    $return['desc'] = $trait->description;
                    $_SESSION['currentMorphTraitName'] = $trait->name;
                }else{
                    treatCreatorErrors($return, $_SESSION['cc']->getLastError());
                }      
            }
        }else{    
            treatCreatorErrors($return, "Trait does not exist (".$_POST['morphPosTrait'].")");            
        }        
    }else{       
        treatCreatorErrors($return, "Morph does not exist (".$_SESSION['currentMorph'].")"); 
    }
}

//SET REMOVE MORPH NEG TRAIT
if(isset($_POST['morphNegTrait'])){
    $morph = $_SESSION['cc']->getCurrentMorphsByName($_SESSION['currentMorph']);
    $trait = $_SESSION['cc']->getTraitByName($_POST['morphNegTrait']);
    
    if (isset($morph)){
        if (isset($trait)){
           if ($_SESSION['cc']->haveTraitOnMorph($trait->name,$morph)){
                if ($_SESSION['cc']->removeTrait($trait,$morph)){
                    $return['desc'] = $trait->description;
                    $_SESSION['currentMorphTraitName'] = $trait->name;
                }else{
                    treatCreatorErrors($return, $_SESSION['cc']->getLastError());
                }        
            }else{
                if ($_SESSION['cc']->addTrait($trait,$morph)){
                    $return['desc'] = $trait->description;
                    $_SESSION['currentMorphTraitName'] = $trait->name;
                }else{
                    treatCreatorErrors($return, $_SESSION['cc']->getLastError());
                }      
            }
        }else{    
            treatCreatorErrors($return, "Trait does not exist (".$_POST['morphNegTrait'].")");            
        }        
    }else{       
        treatCreatorErrors($return, "Morph does not exist (".$_SESSION['currentMorph'].")"); 
    }
}

//HOVER MORPH NEG-POS TRAIT
if(isset($_POST['morphTraitHover'])){
    $_SESSION['currentMorphTraitName'] = $_POST['morphTraitHover'];
}

//SET REMOVE MORPH IMPLANTS
if(isset($_POST['morphImplant'])){
    $morph = $_SESSION['cc']->getCurrentMorphsByName($_SESSION['currentMorph']);
    $gear = $_SESSION['cc']->getGearByName($_POST['morphImplant']);
    
    if (isset($morph)){
        if (isset($gear)){
           if ($_SESSION['cc']->haveAdditionalGear($gear,$morph)){
               if ($_SESSION['cc']->removeGear($gear,$morph)){
                   $return['desc'] = $gear->description;
                   $_SESSION['currentMorphGearName'] = $gear->name;
               }else{
                   treatCreatorErrors($return, $_SESSION['cc']->getLastError());
               }        
            }else{
                if (!$_SESSION['cc']->haveGearOnMorph($gear,$morph)){
                    if ($_SESSION['cc']->addGear($gear,$morph)){
                        $return['desc'] = $gear->description;
                        $_SESSION['currentMorphGearName'] = $gear->name;
                    }else{
                        treatCreatorErrors($return, $_SESSION['cc']->getLastError());
                    }                      
                }
                else{
	                   $return['desc'] = $gear->description;
                        $_SESSION['currentMorphGearName'] = $gear->name;
	            } 
    
            }
        }else{    
            treatCreatorErrors($return, "Implant does not exist (".$_POST['morphImplant'].")");            
        }        
    }else{       
        treatCreatorErrors($return, "Morph does not exist (".$_SESSION['currentMorph'].")"); 
    }
}

//SET REMOVE MORPH GEAR
if(isset($_POST['morphGear'])){
    $morph = $_SESSION['cc']->getCurrentMorphsByName($_SESSION['currentMorph']);
    $gear = $_SESSION['cc']->getGearByName($_POST['morphGear']);
    
    if (isset($morph)){
        if (isset($gear)){
           if ($_SESSION['cc']->haveAdditionalGear($gear,$morph)){
               if ($_SESSION['cc']->removeGear($gear,$morph)){
                   $return['desc'] = $gear->description;
                   $_SESSION['currentMorphGearName'] = $gear->name;
               }else{
                   treatCreatorErrors($return, $_SESSION['cc']->getLastError());
               }        
            }else{
                if (!$_SESSION['cc']->haveGearOnMorph($gear,$morph)){
                    if ($_SESSION['cc']->addGear($gear,$morph)){
                        $return['desc'] = $gear->description;
                        $_SESSION['currentMorphGearName'] = $gear->name;
                    }else{
                        treatCreatorErrors($return, $_SESSION['cc']->getLastError());
                    }                      
                }
                else{
	                    treatCreatorErrors($return, $_SESSION['cc']->getLastError());
	            } 
    
            }
        }else{    
            treatCreatorErrors($return, "Gear does not exist (".$_POST['morphGear'].")");            
        }        
    }else{       
        treatCreatorErrors($return, "Morph does not exist (".$_SESSION['currentMorph'].")"); 
    }
}

//SET REMOVE FREE MORPH GEAR
if(isset($_POST['morphFreeGear'])){
	if(!empty($_POST['morphFreeGear'])){
	    $morph = $_SESSION['cc']->getCurrentMorphsByName($_SESSION['currentMorph']);
	    $gear = new EPGear($_POST['morphFreeGear'],'Added by the player',EPGear::$FREE_GEAR,intval($_POST['morphFreePrice']));
	   // error_log(print_r($gear,true));
	    if (isset($morph)){
	        if (isset($gear)){
	           if ($_SESSION['cc']->haveAdditionalGear($gear,$morph)){
	               if ($_SESSION['cc']->removeGear($gear,$morph)){
	                   $return['desc'] = $gear->description;
	                   $_SESSION['currentMorphGearName'] = $gear->name;
	               }else{
	                   treatCreatorErrors($return, $_SESSION['cc']->getLastError());
	               }        
	            }else{
	                if (!$_SESSION['cc']->haveGearOnMorph($gear,$morph)){
	                    if ($_SESSION['cc']->addFreeGear($gear,$morph)){
	                        $return['desc'] = $gear->description;
	                        $_SESSION['currentMorphGearName'] = $gear->name;
	                    }else{
	                        treatCreatorErrors($return, $_SESSION['cc']->getLastError());
	                    }                      
	                }
	                else{
		                    treatCreatorErrors($return, $_SESSION['cc']->getLastError());
		            } 
	    
	            }
	        }else{    
	            treatCreatorErrors($return, "Gear not set (".$_POST['morphFreeGear'].")");            
	        }        
	    }else{       
	        treatCreatorErrors($return, "Morph does not exist (".$_SESSION['currentMorph'].")"); 
	    }
    }
}

//SET REMOVE FREE EGO GEAR
if(isset($_POST['egoFreeGear'])){    
    if(!empty($_POST['egoFreeGear'])){
	    $soft = new EPGear($_POST['egoFreeGear'],'Added by the player',EPGear::$FREE_GEAR,intval($_POST['egoFreePrice']));
    
	    if (isset($soft)){
	       if ($_SESSION['cc']->haveSoftGear($soft)){
	           if ($_SESSION['cc']->removeSoftGear($soft)){
	               $return['desc'] = $soft->description;
	               $_SESSION['currentSoftName'] = $soft->name;
	           }else{
	               treatCreatorErrors($return, $_SESSION['cc']->getLastError());
	           }        
	        }else{
	            if (!$_SESSION['cc']->haveSoftGear($soft)){
	                if ($_SESSION['cc']->addSoftGear($soft)){
	                    $return['desc'] = $soft->description;
	                    $_SESSION['currentSoftName'] = $soft->name;
	                }else{
	                    treatCreatorErrors($return, $_SESSION['cc']->getLastError());
	                }                      
	            }
	            else{
	                    treatCreatorErrors($return, $_SESSION['cc']->getLastError());
	            } 
	    
	       }       
	    }else{       
	        treatCreatorErrors($return, "Soft gear not instancied (".$_POST['egoFreeGear'].")"); 
	    }
	}
}



//HOVER ON MORPH GEAR OR IMPLANT
if(isset($_POST['morphImplantGearHover'])){
    $_SESSION['currentMorphGearName'] = $_POST['morphImplantGearHover'];     
}



//ADD CREDITS
if(isset($_POST['addCredit'])){
	if(!$_SESSION['cc']->purchaseCredit(1)){
		 treatCreatorErrors($return, $_SESSION['cc']->getLastError());
	}
}

//REMOVE CREDITS
if(isset($_POST['remCredit'])){
	if(!$_SESSION['cc']->saleCredit(1)){
		treatCreatorErrors($return, $_SESSION['cc']->getLastError());
	}
}

//SET REMOVE AI
if(isset($_POST['ai'])){
    $ai = $_SESSION['cc']->getAisByName($_POST['ai']);
    
    if (isset($ai)){
       if ($_SESSION['cc']->haveAi($ai)){
           if ($_SESSION['cc']->removeAI($ai)){
               $return['desc'] = $ai->description;
               $_SESSION['currentAiName'] = $ai->name;
           }else{
               treatCreatorErrors($return, $_SESSION['cc']->getLastError());
           }        
        }else{
            if (!$_SESSION['cc']->haveAi($ai)){
                if ($_SESSION['cc']->addAI($ai)){
                    $return['desc'] = $ai->description;
                    $_SESSION['currentAiName'] = $ai->name;
                }else{
                    treatCreatorErrors($return, $_SESSION['cc']->getLastError());
                }                      
            }
            else{
                    treatCreatorErrors($return, $_SESSION['cc']->getLastError());
            } 
    
       }       
    }else{       
        treatCreatorErrors($return, "Ai does not exist (".$_SESSION['ai'].")"); 
    }
}

//HOVER AI
if(isset($_POST['hoverAi'])){
    $ai = $_SESSION['cc']->getAisByName($_POST['hoverAi']);
    $_SESSION['currentAiName'] = $ai->name;
}


//SET REMOVE SOFT GEAR
if(isset($_POST['softg'])){
	//error_log(print_r($_POST,true));
    $soft = $_SESSION['cc']->getGearByName($_POST['softg']);
    
    if (isset($soft)){
       if ($_SESSION['cc']->haveSoftGear($soft)){
           if ($_SESSION['cc']->removeSoftGear($soft)){
               $return['desc'] = $soft->description;
               $_SESSION['currentSoftName'] = $soft->name;
           }else{
               treatCreatorErrors($return, $_SESSION['cc']->getLastError());
           }        
        }else{
            if (!$_SESSION['cc']->haveSoftGear($soft)){
                if ($_SESSION['cc']->addSoftGear($soft)){
                    $return['desc'] = $soft->description;
                    $_SESSION['currentSoftName'] = $soft->name;
                }else{
                    treatCreatorErrors($return, $_SESSION['cc']->getLastError());
                }                      
            }
            else{
                    treatCreatorErrors($return, $_SESSION['cc']->getLastError());
            } 
    
       }       
    }else{       
        treatCreatorErrors($return, "Soft gear does not exist (".$_SESSION['softg'].")"); 
    }
}

//HOVER ON SOFT GEAR
if(isset($_POST['hoverSoftg'])){
    $soft = $_SESSION['cc']->getGearByName($_POST['hoverSoftg']);
    $_SESSION['currentSoftName'] = $soft->name;
}

//ADD MOXIE
if(isset($_POST['addMoxie'])){
	$currentMox = $_SESSION['cc']->getStatByAbbreviation(EPStat::$MOXIE)->value;
	$currentMox = $currentMox +1;
	if(!$_SESSION['cc']->setStat(EPStat::$MOXIE,$currentMox)){
		 treatCreatorErrors($return, $_SESSION['cc']->getLastError());
	}
}

//REMOVE MOXIE
if(isset($_POST['remMoxie'])){
	$currentMox = $_SESSION['cc']->getStatByAbbreviation(EPStat::$MOXIE)->value;
	$currentMox = $currentMox -1;
	if(!$_SESSION['cc']->setStat(EPStat::$MOXIE,$currentMox)){
		 treatCreatorErrors($return, $_SESSION['cc']->getLastError());
	}
}

//DESC MOXIE
if(isset($_POST['mox'])){
	$moxie = $_SESSION['cc']->getStatByAbbreviation(EPStat::$MOXIE);
	if(isset($moxie)){
		$return['desc'] = $moxie->description;
	}
	else{
		treatCreatorErrors($return, $_SESSION['cc']->getLastError());
	}
}

//GET STAT DESC
if(isset($_POST['stat'])){
	$stat = $_SESSION['cc']->getStatByAbbreviation($_POST['stat']);
	if(isset($stat)){
		$return['desc'] = $stat->description;
	}
	else{
		treatCreatorErrors($return, $_SESSION['cc']->getLastError());
	}
}


//SET LAST DETAILS
if (isset($_POST['lastDetailsChange'])) {   
	  $_SESSION['cc']->character->playerName		= $_POST['playerName'];
	  $_SESSION['cc']->character->charName			= $_POST['characterName'];
	  $_SESSION['cc']->character->realAge			= $_POST['realAge'];
	  $_SESSION['cc']->character->birthGender		= $_POST['birthGender'];
	  $_SESSION['cc']->character->note				= $_POST['noteDetails'];
}


//BONUS MALUS MANAGEMENT
if(isset($_POST['addTargetTo'])){
	//error_log(print_r($_POST,true));
	if($_POST['parentType'] == "origine"){
		$currentBck = $_SESSION['cc']->getBackgroundByName($_POST['parentName']);
		$bonusMalusArray = $currentBck->bonusMalus;
	}
	else if($_POST['parentType'] == "faction"){
		$currentBck = $_SESSION['cc']->getBackgroundByName($_POST['parentName']);
		$bonusMalusArray = $currentBck->bonusMalus;
	}
	else if($_POST['parentType'] == "trait"){
		$currentTrait = $_SESSION['cc']->getAtomByName($_SESSION['cc']->getCurrentTraits(),$_POST['parentName']);
		if(!isset($currentTrait)){
			$currentTrait = $_SESSION['cc']->getTraitByName($_POST['parentName']);
		}
		$bonusMalusArray = $currentTrait->bonusMalus;
	}
	else if($_POST['parentType'] == "morph"){
		$currentMorph = $_SESSION['cc']->getAtomByName($_SESSION['cc']->getCurrentMorphs(),$_POST['parentName']);
		if(!isset($currentMorph)){
			$currentMorph = $_SESSION['cc']->getMorphByName($_POST['parentName']);
		}
		$bonusMalusArray = $currentMorph->bonusMalus;
	}
	else if($_POST['parentType'] == "morphTrait"){
		$currentMorph = $_SESSION['cc']->getAtomByName($_SESSION['cc']->getCurrentMorphs(),$_SESSION['currentMorph']);
        $traits = $_SESSION['cc']->getCurrentMorphTraits($currentMorph->name);
		if (!empty($traits)){
                    $currentMorphTrait = $_SESSION['cc']->getAtomByName($traits,$_POST['parentName']);
                    $bonusMalusArray = $currentMorphTrait->bonusMalus;          
                }                
	}
	else{
		treatCreatorErrors($return,new EPCreatorErrors("Unknown parent type",EPCreatorErrors::$SYSTEM_ERROR));
	}
	
	if($_POST['bMcase'] == EPBonusMalus::$MULTIPLE){
		$candidatParent = $_SESSION['cc']->getBonusMalusByAtomeId($bonusMalusArray,$_POST['parentBmId']);
		if($candidatParent != null){
			$candidat = $_SESSION['cc']->getBonusMalusByAtomeId($candidatParent->bonusMalusTypes,$_POST['bmId']);
			if($candidat != null){
				if($candidat->bonusMalusType == EPBonusMalus::$ON_SKILL){

                                    
					$skill = $_SESSION['cc']->getSkillByName($_POST['targetVal']);
					if($skill != null) $candidat->typeTarget = $skill->prefix;	
                                        
				}
				$candidat->forTargetNamed = $_POST['targetVal'];
				$candidat->selected = true;
				$_SESSION['cc']->adjustAll();
			}
			else{
				treatCreatorErrors($return,new EPCreatorErrors("Bonus malus Multi Unknow",EPCreatorErrors::$SYSTEM_ERROR));
			}
		}
		else{
			treatCreatorErrors($return,new EPCreatorErrors("Bonus malus Unknow (1)",EPCreatorErrors::$SYSTEM_ERROR));
		}
	}
	else{
            $candidat = null;
            if (!empty($bonusMalusArray)){
                $candidat = $_SESSION['cc']->getBonusMalusByAtomeId($bonusMalusArray,$_POST['bmId']);   
            }
		
            if($candidat != null){
                    $candidat->forTargetNamed = $_POST['targetVal'];
                    if($candidat->bonusMalusType == EPBonusMalus::$ON_SKILL){
                                    $skill = $_SESSION['cc']->getSkillByName($_POST['targetVal']);
                                    //error_log($_POST['targetVal']);
                                    if($skill != null) $candidat->typeTarget = $skill->prefix;	
                    }
                    $_SESSION['cc']->adjustAll();
            }
            else{
                    treatCreatorErrors($return,new EPCreatorErrors("Bonus malus Unknow (2)",EPCreatorErrors::$SYSTEM_ERROR));
            }
	}			
}
if(isset($_POST['removeTargetFrom'])){
	//error_log(print_r($_POST,true));
	if($_POST['parentType'] == "origine"){
		$currentBck = $_SESSION['cc']->getBackgroundByName($_POST['parentName']);
		$bonusMalusArray = $currentBck->bonusMalus;
	}
	else if($_POST['parentType'] == "faction"){
		$currentBck = $_SESSION['cc']->getBackgroundByName($_POST['parentName']);
		$bonusMalusArray = $currentBck->bonusMalus;
	}
	else if($_POST['parentType'] == "trait"){
		$currentTrait = $_SESSION['cc']->getTraitByName($_POST['parentName']);
		$bonusMalusArray = $currentTrait->bonusMalus;
	}
	else if($_POST['parentType'] == "morph"){
		$currentMorph = $_SESSION['cc']->getMorphByName($_POST['parentName']);
		$bonusMalusArray = $currentMorph->bonusMalus;
	}
	else if($_POST['parentType'] == "morphTrait"){
		$currentMorph = $_SESSION['cc']->getAtomByName($_SESSION['cc']->getCurrentMorphs(),$_SESSION['currentMorph']);
                $traits = $_SESSION['cc']->getCurrentMorphTraits($currentMorph->name);
                if (!empty($traits)){
                    $currentMorphTrait = $_SESSION['cc']->getAtomByName($traits,$_POST['parentName']);
                    $bonusMalusArray = $currentMorphTrait->bonusMalus;                    
                }
	}
	else{
		treatCreatorErrors($return,new EPCreatorErrors("Unknown parent type",EPCreatorErrors::$SYSTEM_ERROR));
	}
	if($_POST['bMcase'] == EPBonusMalus::$MULTIPLE){
		$candidatParent = $_SESSION['cc']->getBonusMalusByAtomeId($bonusMalusArray,$_POST['parentBmId']);
		if($candidatParent != null){
			$candidat = $_SESSION['cc']->getBonusMalusByAtomeId($candidatParent->bonusMalusTypes,$_POST['bmId']);
			if($candidat != null){
				if(!empty($candidat->targetForChoice)){
					$candidat->forTargetNamed = "";
				}
				$candidat->selected = false;
				$_SESSION['cc']->adjustAll();
			}
			else{
				treatCreatorErrors($return,new EPCreatorErrors("Bonus malus Multi Unknow",EPCreatorErrors::$SYSTEM_ERROR));
			}
		}
		else{
			treatCreatorErrors($return,new EPCreatorErrors("Bonus malus Unknow (3)",EPCreatorErrors::$SYSTEM_ERROR));
		}
	}
	else{
		$candidat = $_SESSION['cc']->getBonusMalusByAtomeId($bonusMalusArray,$_POST['bmId']);
		if($candidat != null){
			$candidat->forTargetNamed = "";
			$_SESSION['cc']->adjustAll();
		}
		else{
			treatCreatorErrors($return,new EPCreatorErrors("Bonus malus Unknow (4)",EPCreatorErrors::$SYSTEM_ERROR));
		}
	}
}

//ADD OCCURENCE 
if(isset($_POST['addOccurence'])){
	
	if($_POST['addOccurence'] == "AI"){
		$currentOccu = $_SESSION['cc']->getAisByName($_SESSION['currentAiName'])->occurence;
		if(!$_SESSION['cc']->setOccurenceIA($_SESSION['currentAiName'],$currentOccu+1)){
			treatCreatorErrors($return, $_SESSION['cc']->getLastError());
		}
	}
	
	if($_POST['addOccurence'] == "SOFT"){
		$currentOccu = $_SESSION['cc']->getAtomByName($_SESSION['cc']->getEgoSoftGears(),$_SESSION['currentSoftName'])->occurence;
		if(!$_SESSION['cc']->setOccurenceGear($_SESSION['currentSoftName'],$currentOccu+1)){
			treatCreatorErrors($return, $_SESSION['cc']->getLastError());
		}
	}
	
	if($_POST['addOccurence'] == "MORPH"){
		$currentOccu = $_SESSION['cc']->getAtomByName($_SESSION['cc']->getGearForMorphName($_SESSION['currentMorph']),$_SESSION['currentMorphGearName'])->occurence;
		if(!$_SESSION['cc']->setOccurenceGear($_SESSION['currentMorphGearName'],$currentOccu+1,$_SESSION['currentMorph'])){
			treatCreatorErrors($return, $_SESSION['cc']->getLastError());
		}
	}

}


//REMOVE OCCURENCE
if(isset($_POST['remOccurence'])){
	
	if($_POST['remOccurence'] == "AI"){
		$currentOccu = $_SESSION['cc']->getAisByName($_SESSION['currentAiName'])->occurence;
		if(!$_SESSION['cc']->setOccurenceIA($_SESSION['currentAiName'],$currentOccu-1)){
			treatCreatorErrors($return, $_SESSION['cc']->getLastError());
		}
	}
	
	if($_POST['remOccurence'] == "SOFT"){
		$currentOccu = $_SESSION['cc']->getAtomByName($_SESSION['cc']->getEgoSoftGears(),$_SESSION['currentSoftName'])->occurence;
		if(!$_SESSION['cc']->setOccurenceGear($_SESSION['currentSoftName'],$currentOccu-1)){
			treatCreatorErrors($return, $_SESSION['cc']->getLastError());
		}
	}
	
	if($_POST['remOccurence'] == "MORPH"){
		$currentOccu = $_SESSION['cc']->getAtomByName($_SESSION['cc']->getGearForMorphName($_SESSION['currentMorph']),$_SESSION['currentMorphGearName'])->occurence;
		if(!$_SESSION['cc']->setOccurenceGear($_SESSION['currentMorphGearName'],$currentOccu-1,$_SESSION['currentMorph'])){
			treatCreatorErrors($return, $_SESSION['cc']->getLastError());
		}
	}

}


//GET CREATION POINTS -- MUST STAY LAST !!
if(isset($_POST['getCrePoint']) && isset($_SESSION['cc'])){
	$return['creation_remain'] = $_SESSION['cc']->getCreationPoint();
	$return['credit_remain'] = $_SESSION['cc']->getCredit();
	$return['aptitude_remain'] = $_SESSION['cc']->getAptitudePoint();
	$return['reputation_remain'] = $_SESSION['cc']->getReputationPoints();
    $return['rez_remain'] = $_SESSION['cc']->getRezPoints();
    $return['asr_remain'] = $_SESSION['cc']->getActiveRestNeed();
    $return['ksr_remain'] = $_SESSION['cc']->getKnowledgeRestNeed();
}

//error_log(print_r($return,true));

echo json_encode($return);
