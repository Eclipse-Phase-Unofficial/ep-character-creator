<?php
declare(strict_types=1);

require_once (__DIR__ . '/../../../../vendor/autoload.php');

use EclipsePhaseCharacterCreator\Backend\EPCharacterCreator;
use EclipsePhaseCharacterCreator\Backend\EPCreatorErrors;
use EclipsePhaseCharacterCreator\Backend\EPListProvider;
use EclipsePhaseCharacterCreator\Backend\EPSkill;
use EclipsePhaseCharacterCreator\Backend\EPStat;
use EclipsePhaseCharacterCreator\Backend\EPConfigFile;
use EclipsePhaseCharacterCreator\Backend\EPBonusMalus;
use EclipsePhaseCharacterCreator\Backend\EPGear;
use EclipsePhaseCharacterCreator\Backend\EPAtom;

session_start();
$php_dir = dirname(__FILE__) . '/../../../php/';

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

/**
 * @param $data
 * @param string| EPCreatorErrors $creatorError
 */
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
    echo json_encode($data);
    exit(1);
}

//DISPATCH============================

//INIT
$return = array();
$return['error'] = false;
$configValues = new EPConfigFile($php_dir . 'config.ini');
$provider = new EPListProvider($php_dir . 'config.ini');

	//error_log(print_r($_POST,true));
	//error_log(print_r($_FILES,true));
	//error_log(print_r($_SESSION,true));
//     returnErrors($return,"Forced Error!");

//if a file to load LOAD FILE
if (isset($_POST['load_char'])) {
    if(!isset($_SESSION['fileToLoad'])){
        treatCreatorErrors($return,new EPCreatorErrors("No File Selected!",EPCreatorErrors::$SYSTEM_ERROR));
    }
    $saveFile = json_decode($_SESSION['fileToLoad'],true);

    if (empty($saveFile['versionNumber']) || floatval($saveFile['versionNumber']) < $configValues->getValue('GeneralValues','versionNumberMin')){
        treatCreatorErrors($return,new EPCreatorErrors("Incompatible file version!",EPCreatorErrors::$SYSTEM_ERROR));
    }
    $_SESSION['cc'] = new EPCharacterCreator($php_dir . 'config.ini');
    $_SESSION['cc']->back = new EPCharacterCreator($php_dir . 'config.ini');

    $_SESSION['cc']->loadSavePack($saveFile);
    $_SESSION['cc']->back->loadSavePack($saveFile);
    $_SESSION['cc']->back->setMaxRepValue($configValues->getValue('RulesValues','EvoMaxRepValue'));
    $_SESSION['cc']->setMaxRepValue($configValues->getValue('RulesValues','EvoMaxRepValue'));
    $_SESSION['cc']->back->setMaxSkillValue($configValues->getValue('RulesValues','SkillEvolutionMaxPoint'));
    $_SESSION['cc']->setMaxSkillValue($configValues->getValue('RulesValues','SkillEvolutionMaxPoint'));

    // Save pack and user both say we are in creation mode
    if ($_SESSION['cc']->creationMode == true && $_POST['creationMode'] == "true" ){
        $_SESSION['cc']->creationMode = true; //We stay in creation mode
    }else{
        // Make sure it's a valid character for play
        if ($_SESSION['cc']->checkValidation()){
            // Switch to Evo Mode
            $_SESSION['cc']->creationMode = false;
            $_SESSION['cc']->evoRezPoint += $_POST['rezPoints'];
            $_SESSION['cc']->evoRepPoint += $_POST['repPoints'];
            $_SESSION['cc']->evoCrePoint += $_POST['credPoints'];
        }else{
            // Stay in creation mode
            $_SESSION['cc']->creationMode = true;
            //treatCreatorErrors($return,new EPCreatorErrors("File is not valid for play!  Staying in creation mode!",EPCreatorErrors::$RULE_ERROR));
        }

    }

    if (!empty($_SESSION['cc']->character->morphs)){
        $_SESSION['cc']->activateMorph($_SESSION['cc']->character->morphs[0]);
    }
    $_SESSION['cc']->adjustAll();
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
    $_SESSION['cc'] = new EPCharacterCreator($php_dir . 'config.ini',$_POST['setCP']);
    $_SESSION['ccRef'] = null;
    //error_log("NEW CHAR");
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
    if(!isset($trait)){
        treatCreatorErrors($return,"Trait ".$_POST['posTrait']." does not exist!");
    }

    if( $trait->isInArray( $_SESSION['cc']->getCurrentTraits() ) ){
        if(!$_SESSION['cc']->removeTrait($trait)){
            treatCreatorErrors($return, $_SESSION['cc']->getLastError());
        }
    }
    else if(!$_SESSION['cc']->addTrait($trait)){
        treatCreatorErrors($return, $_SESSION['cc']->getLastError());
    }
    $_SESSION['currentTraitName'] = $trait->name;
    $return['desc'] = $trait->description;
}

//HOVER POS/NEG TRAIT
if(isset($_POST['traitHover'])){
	$trait = $_SESSION['cc']->getTraitByName($_POST['traitHover']);
	if($trait != null){
	        $_SESSION['currentTraitName'] = $trait->name;
	}
}

//SET PSY SLEIGHT
if(isset($_POST['psyS'])){
    $psyS = $_SESSION['cc']->getPsySleightsByName($_POST['psyS']);
    if(!isset($psyS)){
        treatCreatorErrors($return,"Psy Sleight ".$_POST['psyS']." does not exist!");
    }

    if( $psyS->isInArray( $_SESSION['cc']->getCurrentPsySleights() ) ){
        if(!$_SESSION['cc']->removePsySleight($psyS)){
            treatCreatorErrors($return, $_SESSION['cc']->getLastError());
        }
    }
    else if(!$_SESSION['cc']->addPsySleight($psyS)){
        treatCreatorErrors($return, $_SESSION['cc']->getLastError());
    }
    $return['desc'] = $psyS->description;
    $_SESSION['currentPsiSName'] = $psyS->name;
}
//HOVER PSY SLEIGHT
if(isset($_POST['hoverPsyS'])){
	$psyS = $_SESSION['cc']->getPsySleightsByName($_POST['hoverPsyS']);
	$_SESSION['currentPsiSName'] = $psyS->name;
}

//SET NEG TRAIT
if(isset($_POST['negTrait'])){
    $trait = $_SESSION['cc']->getTraitByName($_POST['negTrait']);
    if(!isset($trait)){
        treatCreatorErrors($return,"Trait ".$_POST['negTrait']." does not exist!");
    }

    if($trait->isInArray($_SESSION['cc']->getCurrentTraits())){
        if(!$_SESSION['cc']->removeTrait($trait)){
            treatCreatorErrors($return, $_SESSION['cc']->getLastError());
        }
    }
    else if(!$_SESSION['cc']->addTrait($trait)){
        treatCreatorErrors($return, $_SESSION['cc']->getLastError());
    }
    $_SESSION['currentTraitName'] = $trait->name;
    $return['desc'] = $trait->description;
}

//SET MOTIVATION
if(isset($_POST['newMot'])){
	if($_POST['newMot'] != ""){
        $_SESSION['cc']->addMotivation($_POST['newMot']);
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

//GET SKILL DECRIPTION
if(isset($_POST['skill'])){
	$skill = $_SESSION['cc']->getSkillByAtomUid($_POST['skill']);
	//error_log("Getting skill id: " . $_POST['skill']. " -> " . $skill->getPrintableName());
	$return['title'] = $skill->getPrintableName();
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
if( isset($_POST['newTmpActSkill']) && !empty($_POST['newTmpActSkill']) ){
	//error_log("adding active skill \"" . $_POST['newTmpSkillPrefix'] . "\": \"" . $_POST['newTmpActSkill']."\"");
	if(!$_SESSION['cc']->addSkill($_POST['newTmpActSkill'], 
									  $provider->getAptForPrefix($_POST['newTmpSkillPrefix']),
									  EPSkill::$ACTIVE_SKILL_TYPE, 
									  EPSkill::$NO_DEFAULTABLE,
									  $_POST['newTmpSkillPrefix'])){
										  treatCreatorErrors($return, $_SESSION['cc']->getLastError());
									  }
}

//ADD TMP KNOWLEDGE SKILL
if( isset($_POST['newTmpKnoSkill']) && !empty($_POST['newTmpKnoSkill']) ){
	//error_log("adding knowledge skill  \"" . $_POST['newTmpSkillPrefix'] . "\": \"" . $_POST['newTmpKnoSkill']."\"");
	if(!$_SESSION['cc']->addSkill($_POST['newTmpKnoSkill']." ", 
									  $provider->getAptForPrefix($_POST['newTmpSkillPrefix']),
									  EPSkill::$KNOWLEDGE_SKILL_TYPE, 
									  EPSkill::$NO_DEFAULTABLE, 
									  $_POST['newTmpSkillPrefix'])){
										  treatCreatorErrors($return, $_SESSION['cc']->getLastError());
									  }
}

//ADD NATIVE LANGUAGE SKILL
if( isset($_POST['newNatLanguageSkill']) && !empty($_POST['newNatLanguageSkill']) ){
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
	$skill = $_SESSION['cc']->getSkillByAtomUid($_POST['remSkill']);
	//error_log("removing skill id: " . $_POST['remSkill']. " -> " . $skill->name);
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
if(isset($_POST['changeSkill'])){
	if(!$_SESSION['cc']->setSkillValue($_POST['changeSkill'], intval($_POST['changeSkillValue']))){
		treatCreatorErrors($return, $_SESSION['cc']->getLastError());
	}
}

//ADD SKILL SPECIALIZATION
if(isset($_POST['addSpe'])){
	$skill = $_SESSION['cc']->getSkillByAtomUid($_POST['addSpeSkill']);
	//error_log("adding skill specialization id: " . $_POST['addSpeSkill']. " -> " . $skill->name);
	if($skill == null){
		treatCreatorErrors($return, $_SESSION['cc']->getLastError());
	}
	else if(!$_SESSION['cc']->addSpecialization($_POST['addSpe'],$skill)){
		treatCreatorErrors($return, $_SESSION['cc']->getLastError());
	}
}

//REMOVE SKILL SPECIALIZATION
if(isset($_POST['remSpeSkill'])){
	$skill = $_SESSION['cc']->getSkillByAtomUid($_POST['remSpeSkill']);
	//error_log("removing skill specialization id: " . $_POST['remSpeSkill']. " -> " . $skill->name);
	if($skill == null){
		treatCreatorErrors($return, $_SESSION['cc']->getLastError());
	}
	else if(!$_SESSION['cc']->removeSpecialization($skill)){
		treatCreatorErrors($return, $_SESSION['cc']->getLastError());
	}
}

//HOVER MORPH
if (isset($_POST['morphHover'])) {
	   $morph = EPAtom::getAtomByName($_SESSION['cc']->getMorphs(),$_POST['morphHover']);
       $return['title'] = $morph->name;
	   $return['desc'] = $morph->description;
}

//ADD / REMOVE MORPH
if (isset($_POST['addRemMorph'])) {
    $morph = EPAtom::getAtomByName($_SESSION['cc']->getMorphs(),$_POST['addRemMorph']);
    if(!isset($morph)){
        treatCreatorErrors($return, "Morph does not exist (".$_SESSION['currentMorph'].")");
    }

    if ( $morph->isInArray($_SESSION['cc']->getCurrentMorphs()) ){
        if (!$_SESSION['cc']->removeMorph($morph)){
            treatCreatorErrors($return, $_SESSION['cc']->getLastError());
        }
    }else{
        if ($_SESSION['cc']->addMorph($morph)){
            $_SESSION['currentMorph'] =  $_POST['addRemMorph'];
        }else{
            treatCreatorErrors($return, $_SESSION['cc']->getLastError());
        }
    }
    $return['title'] = $morph->name;
    $return['desc'] = $morph->description;
}

//GET MORPH SETTINGS
if (isset($_POST['morphSettings'])) {
    $morph = EPAtom::getAtomByName($_SESSION['cc']->character->morphs,$_POST['morphSettings']);
    if(!isset($morph)){
        treatCreatorErrors($return, "Morph does not exist (".$_SESSION['currentMorph'].")");
    }
    $_SESSION['currentMorph'] =  $_POST['morphSettings'];
    $return['morphName'] = $morph->name;
    $return['nickname'] = $morph->nickname;
    $return['location'] = $morph->location;
    $return['age'] = $morph->age;
    $return['gender'] = $morph->gender;
    $return['morphDur'] = $morph->durability;
    $return['morphMaxApt'] = $morph->maxApptitude;
}

//MORPH SELECTED ON GUI
if (isset($_POST['currentMorphUsed'])) {
	 $_SESSION['currentMorph'] =  $_POST['currentMorphUsed']; 
}


//SET MORPH SETTINGS
if (isset($_POST['morphSettingsChange'])) {
    $morph = EPAtom::getAtomByName($_SESSION['cc']->getMorphs(),$_POST['morphSettingsChange']);
    if(!isset($morph)){
        treatCreatorErrors($return, "Morph does not exist (".$_SESSION['currentMorph'].")");
    }
    $_SESSION['currentMorph'] =  $_POST['morphSettingsChange'];
    $morph->nickname = $_POST['morphNickname'];
    $morph->location = $_POST['morphLocation'];
    $morph->age = $_POST['morphAge'];
    $morph->gender = $_POST['morphGender'];
}

//SET REMOVE MORPH POS TRAIT
if(isset($_POST['morphPosTrait'])){
    $morph = $_SESSION['cc']->getCurrentMorphsByName($_SESSION['currentMorph']);
    $trait = $_SESSION['cc']->getTraitByName($_POST['morphPosTrait']);

    if (!isset($morph)){
        treatCreatorErrors($return, "Morph does not exist (".$_SESSION['currentMorph'].")");
    }
    if (!isset($trait)){
        treatCreatorErrors($return, "Trait does not exist (".$_POST['morphPosTrait'].")");
    }
    if ($_SESSION['cc']->haveTraitOnMorph($trait,$morph)){
        if (!$_SESSION['cc']->removeTrait($trait,$morph)){
            treatCreatorErrors($return, $_SESSION['cc']->getLastError());
        }
    }else{
        if (!$_SESSION['cc']->addTrait($trait,$morph)){
            treatCreatorErrors($return, $_SESSION['cc']->getLastError());
        }
    }
    $return['desc'] = $trait->description;
    $_SESSION['currentMorphTraitName'] = $trait->name;
}

//SET REMOVE MORPH NEG TRAIT
if(isset($_POST['morphNegTrait'])){
    $morph = $_SESSION['cc']->getCurrentMorphsByName($_SESSION['currentMorph']);
    $trait = $_SESSION['cc']->getTraitByName($_POST['morphNegTrait']);

    if (!isset($morph)){
        treatCreatorErrors($return, "Morph does not exist (".$_SESSION['currentMorph'].")");
    }
    if (!isset($trait)){
        treatCreatorErrors($return, "Trait does not exist (".$_POST['morphPosTrait'].")");
    }
    if ($_SESSION['cc']->haveTraitOnMorph($trait,$morph)){
        if (!$_SESSION['cc']->removeTrait($trait,$morph)){
            treatCreatorErrors($return, $_SESSION['cc']->getLastError());
        }
    }else{
        if (!$_SESSION['cc']->addTrait($trait,$morph)){
            treatCreatorErrors($return, $_SESSION['cc']->getLastError());
        }
    }
    $return['desc'] = $trait->description;
    $_SESSION['currentMorphTraitName'] = $trait->name;
}

//HOVER MORPH NEG-POS TRAIT
if(isset($_POST['morphTraitHover'])){
    $_SESSION['currentMorphTraitName'] = $_POST['morphTraitHover'];
}

//SET REMOVE MORPH IMPLANTS
if(isset($_POST['morphImplant'])){
    $morph = $_SESSION['cc']->getCurrentMorphsByName($_SESSION['currentMorph']);
    $gear = $_SESSION['cc']->getGearByName($_POST['morphImplant']);

    if (!isset($morph)){
        treatCreatorErrors($return, "Morph does not exist (".$_SESSION['currentMorph'].")");
    }
    if (!isset($gear)){
        treatCreatorErrors($return, "Implant does not exist (".$_POST['morphImplant'].")");
    }
    if ($_SESSION['cc']->haveAdditionalGear($gear,$morph)){
        if (!$_SESSION['cc']->removeGear($gear,$morph)){
            treatCreatorErrors($return, $_SESSION['cc']->getLastError());
        }
    }else{
        if (!$_SESSION['cc']->haveGearOnMorph($gear,$morph)){
            if (!$_SESSION['cc']->addGear($gear,$morph)){
                treatCreatorErrors($return, $_SESSION['cc']->getLastError());
            }
        }else{
            treatCreatorErrors($return, "Can not remove permanent Implants!");
        }
    }
    $return['desc'] = $gear->description;
    $_SESSION['currentMorphGearName'] = $gear->name;
}

//SET REMOVE MORPH GEAR
if(isset($_POST['morphGear'])){
    $morph = $_SESSION['cc']->getCurrentMorphsByName($_SESSION['currentMorph']);
    $gear = $_SESSION['cc']->getGearByName($_POST['morphGear']);

    if (!isset($morph)){
        treatCreatorErrors($return, "Morph does not exist (".$_SESSION['currentMorph'].")");
    }
    if (!isset($gear)){
        treatCreatorErrors($return, "Gear does not exist (".$_POST['morphGear'].")");
    }
    if ($_SESSION['cc']->haveAdditionalGear($gear,$morph)){
        if (!$_SESSION['cc']->removeGear($gear,$morph)){
            treatCreatorErrors($return, $_SESSION['cc']->getLastError());
        }
    }else{
        if (!$_SESSION['cc']->haveGearOnMorph($gear,$morph)){
            if (!$_SESSION['cc']->addGear($gear,$morph)){
                treatCreatorErrors($return, $_SESSION['cc']->getLastError());
            }
        }else{
            treatCreatorErrors($return, "Can not remove permanent Gear!");
        }
    }
    $return['desc'] = $gear->description;
    $_SESSION['currentMorphGearName'] = $gear->name;
}

//SET REMOVE FREE MORPH GEAR
if(isset($_POST['morphFreeGear'])){
    //In case someone hits enter/clicks the '+' icon without putting a gear name in
    if(!empty($_POST['morphFreeGear'])){
        $morph = $_SESSION['cc']->getCurrentMorphsByName($_SESSION['currentMorph']);
        $gear = new EPGear($_POST['morphFreeGear'],'Added by the player',EPGear::$FREE_GEAR,intval($_POST['morphFreePrice']));

        if (!isset($morph)){
            treatCreatorErrors($return, "Morph does not exist (".$_SESSION['currentMorph'].")");
        }
        if (!isset($gear)){
            treatCreatorErrors($return, "Gear does not exist (".$_POST['morphFreeGear'].")");
        }
        //error_log(print_r($gear,true));

        if ($_SESSION['cc']->haveAdditionalGear($gear,$morph)){
            if (!$_SESSION['cc']->removeGear($gear,$morph)){
                treatCreatorErrors($return, $_SESSION['cc']->getLastError());
            }
        }else{
            if (!$_SESSION['cc']->haveGearOnMorph($gear,$morph)){
                $_SESSION['cc']->addFreeGear($gear,$morph);
            }else{
                treatCreatorErrors($return, "Can not remove permanent Gear!");
            }
        }
        $return['desc'] = $gear->description;
        $_SESSION['currentMorphGearName'] = $gear->name;
    }
}

//SET REMOVE FREE EGO GEAR
if(isset($_POST['egoFreeGear'])){
    //In case someone hits enter/clicks the '+' icon without putting a gear name in
    if(!empty($_POST['egoFreeGear'])){
        $soft = new EPGear($_POST['egoFreeGear'],'Added by the player',EPGear::$FREE_GEAR,intval($_POST['egoFreePrice']));

        if (!isset($soft)){
            treatCreatorErrors($return, "Gear does not exist (".$_POST['egoFreeGear'].")");
        }

        if ($_SESSION['cc']->haveSoftGear($soft)){
            if (!$_SESSION['cc']->removeSoftGear($soft)){
                treatCreatorErrors($return, $_SESSION['cc']->getLastError());
            }
        }else{
            if (!$_SESSION['cc']->addSoftGear($soft)){
                treatCreatorErrors($return, $_SESSION['cc']->getLastError());
            }
        }
        $return['desc'] = $soft->description;
        $_SESSION['currentSoftName'] = $soft->name;
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

    if (!isset($ai)){
        treatCreatorErrors($return, "Ai does not exist (".$_SESSION['ai'].")");
    }
    if ($_SESSION['cc']->haveAi($ai)){
        if (!$_SESSION['cc']->removeAI($ai)){
            treatCreatorErrors($return, $_SESSION['cc']->getLastError());
        }
    }else{
        if (!$_SESSION['cc']->addAI($ai)){
            treatCreatorErrors($return, $_SESSION['cc']->getLastError());
        }
    }
    $return['desc'] = $ai->description;
    $_SESSION['currentAiName'] = $ai->name;
}

//HOVER AI
if(isset($_POST['hoverAi'])){
    $ai = $_SESSION['cc']->getAisByName($_POST['hoverAi']);
    $_SESSION['currentAiName'] = $ai->name;
}


//SET REMOVE SOFT GEAR
if(isset($_POST['softg'])){
//     error_log(print_r($_POST,true));
    $soft = $_SESSION['cc']->getGearByName($_POST['softg']);

    if (!isset($soft)){
        treatCreatorErrors($return, "Soft gear does not exist (".$_POST['softg'].")");
    }
    if ($_SESSION['cc']->haveSoftGear($soft)){
        if (!$_SESSION['cc']->removeSoftGear($soft)){
            treatCreatorErrors($return, $_SESSION['cc']->getLastError());
        }
    }else{
        if (!$_SESSION['cc']->addSoftGear($soft)){
            treatCreatorErrors($return, $_SESSION['cc']->getLastError());
        }
    }
    $return['desc'] = $soft->description;
    $_SESSION['currentSoftName'] = $soft->name;
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
    if(!isset($moxie)){
        treatCreatorErrors($return, $_SESSION['cc']->getLastError());
    }
    $return['desc'] = $moxie->description;
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
    if(!isset($_POST['targetVal'])){
        treatCreatorErrors($return,new EPCreatorErrors("Must select an item!",EPCreatorErrors::$SYSTEM_ERROR));
    }

	if($_POST['parentType'] == "origine"){
		$currentBck = $_SESSION['cc']->getBackgroundByName($_POST['parentName']);
		$bonusMalusArray = $currentBck->bonusMalus;
	}
	else if($_POST['parentType'] == "faction"){
		$currentBck = $_SESSION['cc']->getBackgroundByName($_POST['parentName']);
		$bonusMalusArray = $currentBck->bonusMalus;
	}
	else if($_POST['parentType'] == "trait"){
		$currentTrait = EPAtom::getAtomByName($_SESSION['cc']->getCurrentTraits(),$_POST['parentName']);
		if(!isset($currentTrait)){
			$currentTrait = $_SESSION['cc']->getTraitByName($_POST['parentName']);
		}
		$bonusMalusArray = $currentTrait->bonusMalus;
	}
	else if($_POST['parentType'] == "morph"){
		$currentMorph = EPAtom::getAtomByName($_SESSION['cc']->getCurrentMorphs(),$_POST['parentName']);
		if(!isset($currentMorph)){
			$currentMorph = $_SESSION['cc']->getMorphByName($_POST['parentName']);
		}
		$bonusMalusArray = $currentMorph->bonusMalus;
	}
	else if($_POST['parentType'] == "morphTrait"){
		$currentMorph = EPAtom::getAtomByName($_SESSION['cc']->getCurrentMorphs(),$_SESSION['currentMorph']);
        $traits = $_SESSION['cc']->getCurrentMorphTraits($currentMorph->name);
		if (!empty($traits)){
                    $currentMorphTrait = EPAtom::getAtomByName($traits,$_POST['parentName']);
                    $bonusMalusArray = $currentMorphTrait->bonusMalus;          
                }                
	}
	else{
		treatCreatorErrors($return,new EPCreatorErrors("Unknown parent type",EPCreatorErrors::$SYSTEM_ERROR));
	}
	
	if($_POST['bMcase'] == EPBonusMalus::$MULTIPLE){
		$candidatParent = EPAtom::getAtomByUid($bonusMalusArray,$_POST['parentBmId']);
        if(!isset($candidatParent)){
			treatCreatorErrors($return,new EPCreatorErrors("Can not add Bonus Malus: Unkown Parent!",EPCreatorErrors::$SYSTEM_ERROR));
		}
		$candidat = EPAtom::getAtomByUid($candidatParent->bonusMalusTypes,$_POST['bmId']);
        if(!isset($candidat)){
			treatCreatorErrors($return,new EPCreatorErrors("Can not add Bonus Malus: Could not find Bonus Malus",EPCreatorErrors::$SYSTEM_ERROR));
		}
		if($candidat->bonusMalusType == EPBonusMalus::$ON_SKILL){
			$skill = $_SESSION['cc']->getSkillByAtomUid($_POST['targetVal']);
            // Database skills (non user selectable) use name/prefix instead of Uid
            if(!isset($skill)){
                $skill = EPSkill::getSkill($_SESSION['cc']->character->ego->skills,$candidat->forTargetNamed,$candidat->typeTarget);
            }
            if(!isset($skill)){
				treatCreatorErrors($return,new EPCreatorErrors("Bonus Malus Unknown skill",EPCreatorErrors::$SYSTEM_ERROR));
			}
			$candidat->typeTarget = $skill->prefix;
		}
		$candidat->forTargetNamed = $_POST['targetVal'];
		$candidat->selected = true;
	}
	else{
        $candidat = EPAtom::getAtomByUid($bonusMalusArray,$_POST['bmId']);

        if(!isset($candidat)){
            treatCreatorErrors($return,new EPCreatorErrors("Could not find bmId: ".$_POST['bmId']." for parentType: ".$_POST['parentType'],EPCreatorErrors::$SYSTEM_ERROR));
        }

        $candidat->forTargetNamed = $_POST['targetVal'];
        if($candidat->bonusMalusType == EPBonusMalus::$ON_SKILL){
            $skill = $_SESSION['cc']->getSkillByAtomUid($_POST['targetVal']);
            // Database skills (non user selectable) use name/prefix instead of Uid
            if(!isset($skill)){
                $skill = EPSkill::getSkill($_SESSION['cc']->character->ego->skills,$candidat->forTargetNamed,$candidat->typeTarget);
            }
            if(!isset($skill)){
                treatCreatorErrors($return,new EPCreatorErrors("Bonus Malus Unknown skill",EPCreatorErrors::$SYSTEM_ERROR));
            }
            $candidat->typeTarget = $skill->prefix;
        }
    }
    $_SESSION['cc']->adjustAll();
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
		$currentMorph = EPAtom::getAtomByName($_SESSION['cc']->getCurrentMorphs(),$_SESSION['currentMorph']);
                $traits = $_SESSION['cc']->getCurrentMorphTraits($currentMorph->name);
                if (!empty($traits)){
                    $currentMorphTrait = EPAtom::getAtomByName($traits,$_POST['parentName']);
                    $bonusMalusArray = $currentMorphTrait->bonusMalus;                    
                }
	}
	else{
		treatCreatorErrors($return,new EPCreatorErrors("Unknown parent type",EPCreatorErrors::$SYSTEM_ERROR));
	}
	if($_POST['bMcase'] == EPBonusMalus::$MULTIPLE){
		$candidatParent = EPAtom::getAtomByUid($bonusMalusArray,$_POST['parentBmId']);
        if(!isset($candidatParent)){
             treatCreatorErrors($return,new EPCreatorErrors("Could not find parentBmId: ".$_POST['parentBmId']." for parentType: ".$_POST['parentType'],EPCreatorErrors::$SYSTEM_ERROR));
        }
        $candidat = EPAtom::getAtomByUid($candidatParent->bonusMalusTypes,$_POST['bmId']);
        if(!isset($candidat)){
            treatCreatorErrors($return,new EPCreatorErrors("Could not find bmId: ".$_POST['bmId']." for parentBmId: ".$_POST['parentBmId'],EPCreatorErrors::$SYSTEM_ERROR));
        }
        if(!empty($candidat->targetForChoice)){
            $candidat->forTargetNamed = "";
        }
        $candidat->selected = false;
	}
	else{
		$candidat = EPAtom::getAtomByUid($bonusMalusArray,$_POST['bmId']);
        if(!isset($candidat)){
            treatCreatorErrors($return,new EPCreatorErrors("Could not find bmId: ".$_POST['bmId']." for parentType: ".$_POST['parentType'],EPCreatorErrors::$SYSTEM_ERROR));
        }
        $candidat->forTargetNamed = "";
	}
    $_SESSION['cc']->adjustAll();
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
		$currentOccu = EPAtom::getAtomByName($_SESSION['cc']->getEgoSoftGears(),$_SESSION['currentSoftName'])->occurence;
		if(!$_SESSION['cc']->setOccurenceGear($_SESSION['currentSoftName'],$currentOccu+1)){
			treatCreatorErrors($return, $_SESSION['cc']->getLastError());
		}
	}
	
	if($_POST['addOccurence'] == "MORPH"){
		$currentOccu = EPAtom::getAtomByName($_SESSION['cc']->getGearForMorphName($_SESSION['currentMorph']),$_SESSION['currentMorphGearName'])->occurence;
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
		$currentOccu = EPAtom::getAtomByName($_SESSION['cc']->getEgoSoftGears(),$_SESSION['currentSoftName'])->occurence;
		if(!$_SESSION['cc']->setOccurenceGear($_SESSION['currentSoftName'],$currentOccu-1)){
			treatCreatorErrors($return, $_SESSION['cc']->getLastError());
		}
	}
	
	if($_POST['remOccurence'] == "MORPH"){
		$currentOccu = EPAtom::getAtomByName($_SESSION['cc']->getGearForMorphName($_SESSION['currentMorph']),$_SESSION['currentMorphGearName'])->occurence;
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
