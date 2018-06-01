<?php
declare(strict_types=1);

require_once (__DIR__ . '/../../vendor/autoload.php');

use App\Creator\EPCharacterCreator;
use App\Creator\EPCreatorErrors;
use App\Creator\EPListProvider;
use App\Creator\Atoms\EPSkill;
use App\Creator\Atoms\EPStat;
use App\Creator\EPConfigFile;
use App\Creator\Atoms\EPBonusMalus;
use App\Creator\Atoms\EPGear;
use App\Creator\Atoms\EPAtom;

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
$configValues = new EPConfigFile(getConfigLocation());
$provider = new EPListProvider(getConfigLocation());
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
    $_SESSION['cc'] = new EPCharacterCreator(getConfigLocation());
    creator()->back = new EPCharacterCreator(getConfigLocation());

    creator()->loadSavePack($saveFile);
    creator()->back->loadSavePack($saveFile);
    creator()->back->setMaxRepValue($configValues->getValue('RulesValues','EvoMaxRepValue'));
    creator()->setMaxRepValue($configValues->getValue('RulesValues','EvoMaxRepValue'));
    creator()->back->setMaxSkillValue($configValues->getValue('RulesValues','SkillEvolutionMaxPoint'));
    creator()->setMaxSkillValue($configValues->getValue('RulesValues','SkillEvolutionMaxPoint'));

    // Save pack and user both say we are in creation mode
    if (creator()->creationMode == true && $_POST['creationMode'] == "true" ){
        creator()->creationMode = true; //We stay in creation mode
    }else{
        // Make sure it's a valid character for play
        if (creator()->checkValidation()){
            // Switch to Evo Mode
            creator()->creationMode = false;
            creator()->evoRezPoint += $_POST['rezPoints'];
            creator()->evoRepPoint += $_POST['repPoints'];
            creator()->evoCrePoint += $_POST['credPoints'];
        }else{
            // Stay in creation mode
            creator()->creationMode = true;
            //treatCreatorErrors($return,new EPCreatorErrors("File is not valid for play!  Staying in creation mode!",EPCreatorErrors::$RULE_ERROR));
        }

    }

    if (!empty(creator()->character->morphs)){
        creator()->activateMorph(creator()->character->morphs[0]);
    }
    creator()->adjustAll();
}

//FIRST RUN
if (isset($_POST['firstTime'])) {
    if(null === creator()) {
     	$return['sessionExist'] = false;
     }
     else{
	    $return['sessionExist'] = true; 
     }
}

//SET CP FOR A NEW CHARACTER
if(isset($_POST['setCP'])){
	//CHARACTER CREATOR
    $_SESSION['cc'] = new EPCharacterCreator(getConfigLocation(),$_POST['setCP']);
    //error_log("NEW CHAR");
}

//GET ORIGINE
if(isset($_POST['getBcg'])){
	if(creator()->getCurrentBackground() != null){
    	$return['currentBcg'] = creator()->getCurrentBackground()->name;
    	$_SESSION['currentOrigineName'] = creator()->getCurrentBackground()->name;
    	//$return['desc'] = creator()->getCurrentBackground()->description;
    }
    else{
	    $return['currentBcg'] = null;
    }
}
//SET ORIGINE
if (isset($_POST['origine'])) {
    if(EpDatabase()->getBackgroundByName($_POST['origine']) != null &&
       creator()->setBackground(EpDatabase()->getBackgroundByName($_POST['origine']))){
        $_SESSION['currentOrigineName'] = $_POST['origine'];
        //$return['desc'] = EpDatabase()->getBackgroundByName($_POST['origine'])->description;
    }
    else{
        treatCreatorErrors($return, creator()->getLastError());
    }
	
}
//GET FACTION
if(isset($_POST['getFac'])){
	if(creator()->getCurrentFaction() != null){
    	$return['currentFac'] = creator()->getCurrentFaction()->name;
    	$_SESSION['currentFactionName'] = creator()->getCurrentFaction()->name;
		//$return['desc'] = creator()->getCurrentFaction()->description;
    }
    else{
	    $return['currentFac'] = null;
    }
}
//SET FACTION
if(isset($_POST['faction'])){
    if(EpDatabase()->getBackgroundByName($_POST['faction']) != null &&
       creator()->setFaction(EpDatabase()->getBackgroundByName($_POST['faction']))){
       $_SESSION['currentFactionName'] = creator()->getCurrentFaction()->name;
        //$return['desc'] = EpDatabase()->getBackgroundByName($_POST['faction'])->description;
    }
    else{
        treatCreatorErrors($return, creator()->getLastError());
    }
}

//SET POS TRAIT
if(isset($_POST['posTrait'])){
    $trait = EpDatabase()->getTraitByName($_POST['posTrait']);
    if(!isset($trait)){
        treatCreatorErrors($return,"Trait ".$_POST['posTrait']." does not exist!");
    }

    if( $trait->isInArray( creator()->getCurrentTraits() ) ){
        if(!creator()->removeTrait($trait)){
            treatCreatorErrors($return, creator()->getLastError());
        }
    }
    else if(!creator()->addTrait($trait)){
        treatCreatorErrors($return, creator()->getLastError());
    }
    $_SESSION['currentTraitName'] = $trait->name;
    $return['desc'] = $trait->description;
}

//HOVER POS/NEG TRAIT
if(isset($_POST['traitHover'])){
	$trait = EpDatabase()->getTraitByName($_POST['traitHover']);
	if($trait != null){
	        $_SESSION['currentTraitName'] = $trait->name;
	}
}

//SET PSY SLEIGHT
if(isset($_POST['psyS'])){
    $psyS = EpDatabase()->getPsySleightsByName($_POST['psyS']);
    if(!isset($psyS)){
        treatCreatorErrors($return,"Psy Sleight ".$_POST['psyS']." does not exist!");
    }

    if( $psyS->isInArray( creator()->getCurrentPsySleights() ) ){
        if(!creator()->removePsySleight($psyS)){
            treatCreatorErrors($return, creator()->getLastError());
        }
    }
    else if(!creator()->addPsySleight($psyS)){
        treatCreatorErrors($return, creator()->getLastError());
    }
    $return['desc'] = $psyS->description;
    $_SESSION['currentPsiSName'] = $psyS->name;
}
//HOVER PSY SLEIGHT
if(isset($_POST['hoverPsyS'])){
	$psyS = EpDatabase()->getPsySleightsByName($_POST['hoverPsyS']);
	$_SESSION['currentPsiSName'] = $psyS->name;
}

//SET NEG TRAIT
if(isset($_POST['negTrait'])){
    $trait = EpDatabase()->getTraitByName($_POST['negTrait']);
    if(!isset($trait)){
        treatCreatorErrors($return,"Trait ".$_POST['negTrait']." does not exist!");
    }

    if($trait->isInArray(creator()->getCurrentTraits())){
        if(!creator()->removeTrait($trait)){
            treatCreatorErrors($return, creator()->getLastError());
        }
    }
    else if(!creator()->addTrait($trait)){
        treatCreatorErrors($return, creator()->getLastError());
    }
    $_SESSION['currentTraitName'] = $trait->name;
    $return['desc'] = $trait->description;
}

//SET MOTIVATION
if(isset($_POST['newMot'])){
	if($_POST['newMot'] != ""){
        creator()->addMotivation($_POST['newMot']);
	}
}
//REMOVE MOTIVATION
if(isset($_POST['remMot'])){
	if($_POST['remMot'] != ""){
	    if(!creator()->removeMotivation($_POST['remMot'])){
	        treatCreatorErrors($return, creator()->getLastError());
	    }
	}
}

//SET APTITUDES
if(isset($_POST['cog']) && isset($_POST['coo']) && isset($_POST['int']) &&
   isset($_POST['ref']) && isset($_POST['sav']) && isset($_POST['som']) &&
   isset($_POST['wil'])){
   $errorOnApt = false;
    if(!creator()->setAptitudeValue("COG", intval($_POST['cog']))){
	   $errorOnApt = true; 
	   $return['aptError'] = 'COG';
    }
    if(!creator()->setAptitudeValue("COO", intval($_POST['coo']))){
	    $errorOnApt = true;
	    $return['aptError'] = 'COO';
    }
    if(!creator()->setAptitudeValue("INT", intval($_POST['int']))){
	    $errorOnApt = true;
	    $return['aptError'] = 'INT';
    }
    if(!creator()->setAptitudeValue("REF", intval($_POST['ref']))){
	    $errorOnApt = true;
	    $return['aptError'] = 'REF';
    }
    if(!creator()->setAptitudeValue("SAV", intval($_POST['sav']))){
	    $errorOnApt = true;
	    $return['aptError'] = 'SAV';
    }
    if(!creator()->setAptitudeValue("SOM", intval($_POST['som']))){
	    $errorOnApt = true;
	    $return['aptError'] = 'SOM';
    }
    if(!creator()->setAptitudeValue("WIL", intval($_POST['wil']))){
		$errorOnApt = true;
		$return['aptError'] = 'WIL';
    }

    if($errorOnApt) {
        //error_log('ERROR :'.creator()->getLastError()->typeError);
         treatCreatorErrors($return, creator()->getLastError());
    }
}

//SET REPUTATION
if(isset($_POST['atrep']) && isset($_POST['grep']) && isset($_POST['crep']) &&
   isset($_POST['irep']) && isset($_POST['erep']) && isset($_POST['rrep']) &&
   isset($_POST['frep'])){
    $errorOnRep = false;
   
    if(!creator()->setReputation("@-Rep", intval($_POST['atrep']))){
	    $errorOnRep = true; 
	    $return['repError'] = '@-Rep';
    } 
    if(!creator()->setReputation("G-Rep", intval($_POST['grep']))){
	    $errorOnRep = true; 
	    $return['repError'] = 'G-Rep';
    } 
    if(!creator()->setReputation("C-Rep", intval($_POST['crep']))){
	   $errorOnRep = true; 
	    $return['repError'] = 'C-Rep'; 
    } 
    if(!creator()->setReputation("I-Rep", intval($_POST['irep']))){
	    $errorOnRep = true; 
	    $return['repError'] = 'I-Rep';
    } 
    if(!creator()->setReputation("E-Rep", intval($_POST['erep']))){
	    $errorOnRep = true; 
	    $return['repError'] = 'E-Rep';
    } 
    if(!creator()->setReputation("R-Rep", intval($_POST['rrep']))){
	    $errorOnRep = true; 
	    $return['repError'] = 'R-Rep';
    } 
    if(!creator()->setReputation("F-Rep", intval($_POST['frep']))){
		$errorOnRep = true; 
	    $return['repError'] = 'F-Rep';
    }
    if($errorOnRep) {
         treatCreatorErrors($return, creator()->getLastError());
    }
}

//GET SKILL DECRIPTION
if(isset($_POST['skill'])){
	$skill = creator()->getSkillByAtomUid($_POST['skill']);
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
	if(!creator()->addSkill($_POST['newTmpActSkill'], 
									  $provider->getAptForPrefix($_POST['newTmpSkillPrefix']),
									  EPSkill::$ACTIVE_SKILL_TYPE, 
									  EPSkill::$NO_DEFAULTABLE,
									  $_POST['newTmpSkillPrefix'])){
										  treatCreatorErrors($return, creator()->getLastError());
									  }
}

//ADD TMP KNOWLEDGE SKILL
if( isset($_POST['newTmpKnoSkill']) && !empty($_POST['newTmpKnoSkill']) ){
	//error_log("adding knowledge skill  \"" . $_POST['newTmpSkillPrefix'] . "\": \"" . $_POST['newTmpKnoSkill']."\"");
	if(!creator()->addSkill($_POST['newTmpKnoSkill']." ", 
									  $provider->getAptForPrefix($_POST['newTmpSkillPrefix']),
									  EPSkill::$KNOWLEDGE_SKILL_TYPE, 
									  EPSkill::$NO_DEFAULTABLE, 
									  $_POST['newTmpSkillPrefix'])){
										  treatCreatorErrors($return, creator()->getLastError());
									  }
}

//ADD NATIVE LANGUAGE SKILL
if( isset($_POST['newNatLanguageSkill']) && !empty($_POST['newNatLanguageSkill']) ){
	if(!creator()->addSkill($_POST['newNatLanguageSkill'], 
									  $provider->getAptForPrefix('Language'),
									  EPSkill::$KNOWLEDGE_SKILL_TYPE, 
									  EPSkill::$DEFAULTABLE, 
									  "Language",
									  null,
									  true)){
										  treatCreatorErrors($return, creator()->getLastError());
									  }
}



//REMOVE TMP SKILL
if(isset($_POST['remSkill'])){
	$skill = creator()->getSkillByAtomUid($_POST['remSkill']);
	//error_log("removing skill id: " . $_POST['remSkill']. " -> " . $skill->name);
	if($skill != null){
		if(!creator()->removeSkill($skill)){
			treatCreatorErrors($return, creator()->getLastError());
		}
	}
	else{
		treatCreatorErrors($return, creator()->getLastError());
	}
}

//CHANGE SKILL VALUE
if(isset($_POST['changeSkill'])){
	if(!creator()->setSkillValue($_POST['changeSkill'], intval($_POST['changeSkillValue']))){
		treatCreatorErrors($return, creator()->getLastError());
	}
}

//ADD SKILL SPECIALIZATION
if(isset($_POST['addSpe'])){
	$skill = creator()->getSkillByAtomUid($_POST['addSpeSkill']);
	//error_log("adding skill specialization id: " . $_POST['addSpeSkill']. " -> " . $skill->name);
	if($skill == null){
		treatCreatorErrors($return, creator()->getLastError());
	}
	else if(!creator()->addSpecialization($_POST['addSpe'],$skill)){
		treatCreatorErrors($return, creator()->getLastError());
	}
}

//REMOVE SKILL SPECIALIZATION
if(isset($_POST['remSpeSkill'])){
	$skill = creator()->getSkillByAtomUid($_POST['remSpeSkill']);
	//error_log("removing skill specialization id: " . $_POST['remSpeSkill']. " -> " . $skill->name);
	if($skill == null){
		treatCreatorErrors($return, creator()->getLastError());
	}
	else if(!creator()->removeSpecialization($skill)){
		treatCreatorErrors($return, creator()->getLastError());
	}
}

//HOVER MORPH
if (isset($_POST['morphHover'])) {
	   $morph = EPAtom::getAtomByName(EpDatabase()->getMorphs(),$_POST['morphHover']);
       $return['title'] = $morph->name;
	   $return['desc'] = $morph->description;
}

//ADD / REMOVE MORPH
if (isset($_POST['addRemMorph'])) {
    $morph = EPAtom::getAtomByName(EpDatabase()->getMorphs(),$_POST['addRemMorph']);
    if(!isset($morph)){
        treatCreatorErrors($return, "Morph does not exist (".$_SESSION['currentMorph'].")");
    }

    if ( $morph->isInArray(creator()->getCurrentMorphs()) ){
        if (!creator()->removeMorph($morph)){
            treatCreatorErrors($return, creator()->getLastError());
        }
    }else{
        if (creator()->addMorph($morph)){
            $_SESSION['currentMorph'] =  $_POST['addRemMorph'];
        }else{
            treatCreatorErrors($return, creator()->getLastError());
        }
    }
    $return['title'] = $morph->name;
    $return['desc'] = $morph->description;
}

//GET MORPH SETTINGS
if (isset($_POST['morphSettings'])) {
    $morph = EPAtom::getAtomByName(creator()->character->morphs,$_POST['morphSettings']);
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
    $morph = EPAtom::getAtomByName(EpDatabase()->getMorphs(),$_POST['morphSettingsChange']);
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
    $morph = creator()->getCurrentMorphsByName($_SESSION['currentMorph']);
    $trait = EpDatabase()->getTraitByName($_POST['morphPosTrait']);

    if (!isset($morph)){
        treatCreatorErrors($return, "Morph does not exist (".$_SESSION['currentMorph'].")");
    }
    if (!isset($trait)){
        treatCreatorErrors($return, "Trait does not exist (".$_POST['morphPosTrait'].")");
    }
    if (creator()->haveTraitOnMorph($trait,$morph)){
        if (!creator()->removeTrait($trait,$morph)){
            treatCreatorErrors($return, creator()->getLastError());
        }
    }else{
        if (!creator()->addTrait($trait,$morph)){
            treatCreatorErrors($return, creator()->getLastError());
        }
    }
    $return['desc'] = $trait->description;
    $_SESSION['currentMorphTraitName'] = $trait->name;
}

//SET REMOVE MORPH NEG TRAIT
if(isset($_POST['morphNegTrait'])){
    $morph = creator()->getCurrentMorphsByName($_SESSION['currentMorph']);
    $trait = EpDatabase()->getTraitByName($_POST['morphNegTrait']);

    if (!isset($morph)){
        treatCreatorErrors($return, "Morph does not exist (".$_SESSION['currentMorph'].")");
    }
    if (!isset($trait)){
        treatCreatorErrors($return, "Trait does not exist (".$_POST['morphPosTrait'].")");
    }
    if (creator()->haveTraitOnMorph($trait,$morph)){
        if (!creator()->removeTrait($trait,$morph)){
            treatCreatorErrors($return, creator()->getLastError());
        }
    }else{
        if (!creator()->addTrait($trait,$morph)){
            treatCreatorErrors($return, creator()->getLastError());
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
    $morph = creator()->getCurrentMorphsByName($_SESSION['currentMorph']);
    $gear = EpDatabase()->getGearByName($_POST['morphImplant']);

    if (!isset($morph)){
        treatCreatorErrors($return, "Morph does not exist (".$_SESSION['currentMorph'].")");
    }
    if (!isset($gear)){
        treatCreatorErrors($return, "Implant does not exist (".$_POST['morphImplant'].")");
    }
    if (creator()->haveAdditionalGear($gear,$morph)){
        if (!creator()->removeGear($gear,$morph)){
            treatCreatorErrors($return, creator()->getLastError());
        }
    }else{
        if (!creator()->haveGearOnMorph($gear,$morph)){
            if (!creator()->addGear($gear,$morph)){
                treatCreatorErrors($return, creator()->getLastError());
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
    $morph = creator()->getCurrentMorphsByName($_SESSION['currentMorph']);
    $gear = EpDatabase()->getGearByName($_POST['morphGear']);

    if (!isset($morph)){
        treatCreatorErrors($return, "Morph does not exist (".$_SESSION['currentMorph'].")");
    }
    if (!isset($gear)){
        treatCreatorErrors($return, "Gear does not exist (".$_POST['morphGear'].")");
    }
    if (creator()->haveAdditionalGear($gear,$morph)){
        if (!creator()->removeGear($gear,$morph)){
            treatCreatorErrors($return, creator()->getLastError());
        }
    }else{
        if (!creator()->haveGearOnMorph($gear,$morph)){
            if (!creator()->addGear($gear,$morph)){
                treatCreatorErrors($return, creator()->getLastError());
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
        $morph = creator()->getCurrentMorphsByName($_SESSION['currentMorph']);
        $gear = new EPGear($_POST['morphFreeGear'],'Added by the player',EPGear::$FREE_GEAR,intval($_POST['morphFreePrice']));

        if (!isset($morph)){
            treatCreatorErrors($return, "Morph does not exist (".$_SESSION['currentMorph'].")");
        }
        if (!isset($gear)){
            treatCreatorErrors($return, "Gear does not exist (".$_POST['morphFreeGear'].")");
        }
        //error_log(print_r($gear,true));

        if (creator()->haveAdditionalGear($gear,$morph)){
            if (!creator()->removeGear($gear,$morph)){
                treatCreatorErrors($return, creator()->getLastError());
            }
        }else{
            if (!creator()->haveGearOnMorph($gear,$morph)){
                creator()->addFreeGear($gear,$morph);
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

        if (creator()->haveSoftGear($soft)){
            if (!creator()->removeSoftGear($soft)){
                treatCreatorErrors($return, creator()->getLastError());
            }
        }else{
            if (!creator()->addSoftGear($soft)){
                treatCreatorErrors($return, creator()->getLastError());
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
	if(!creator()->purchaseCredit(1)){
		 treatCreatorErrors($return, creator()->getLastError());
	}
}

//REMOVE CREDITS
if(isset($_POST['remCredit'])){
	if(!creator()->saleCredit(1)){
		treatCreatorErrors($return, creator()->getLastError());
	}
}

//SET REMOVE AI
if(isset($_POST['ai'])){
    $ai = EpDatabase()->getAiByName($_POST['ai']);

    if (!isset($ai)){
        treatCreatorErrors($return, "Ai does not exist (".$_SESSION['ai'].")");
    }
    if (creator()->haveAi($ai)){
        if (!creator()->removeAI($ai)){
            treatCreatorErrors($return, creator()->getLastError());
        }
    }else{
        if (!creator()->addAI($ai)){
            treatCreatorErrors($return, creator()->getLastError());
        }
    }
    $return['desc'] = $ai->description;
    $_SESSION['currentAiName'] = $ai->name;
}

//HOVER AI
if(isset($_POST['hoverAi'])){
    $ai = EpDatabase()->getAiByName($_POST['hoverAi']);
    $_SESSION['currentAiName'] = $ai->name;
}


//SET REMOVE SOFT GEAR
if(isset($_POST['softg'])){
//     error_log(print_r($_POST,true));
    $soft = EpDatabase()->getGearByName($_POST['softg']);

    if (!isset($soft)){
        treatCreatorErrors($return, "Soft gear does not exist (".$_POST['softg'].")");
    }
    if (creator()->haveSoftGear($soft)){
        if (!creator()->removeSoftGear($soft)){
            treatCreatorErrors($return, creator()->getLastError());
        }
    }else{
        if (!creator()->addSoftGear($soft)){
            treatCreatorErrors($return, creator()->getLastError());
        }
    }
    $return['desc'] = $soft->description;
    $_SESSION['currentSoftName'] = $soft->name;
}

//HOVER ON SOFT GEAR
if(isset($_POST['hoverSoftg'])){
    $soft = EpDatabase()->getGearByName($_POST['hoverSoftg']);
    $_SESSION['currentSoftName'] = $soft->name;
}

//ADD MOXIE
if(isset($_POST['addMoxie'])){
	$currentMox = creator()->getStatByAbbreviation(EPStat::$MOXIE)->value;
	$currentMox = $currentMox +1;
	if(!creator()->setStat(EPStat::$MOXIE,$currentMox)){
		 treatCreatorErrors($return, creator()->getLastError());
	}
}

//REMOVE MOXIE
if(isset($_POST['remMoxie'])){
	$currentMox = creator()->getStatByAbbreviation(EPStat::$MOXIE)->value;
	$currentMox = $currentMox -1;
	if(!creator()->setStat(EPStat::$MOXIE,$currentMox)){
		 treatCreatorErrors($return, creator()->getLastError());
	}
}

//DESC MOXIE
if(isset($_POST['mox'])){
    $moxie = creator()->getStatByAbbreviation(EPStat::$MOXIE);
    if(!isset($moxie)){
        treatCreatorErrors($return, creator()->getLastError());
    }
    $return['desc'] = $moxie->description;
}

//SET LAST DETAILS
if (isset($_POST['lastDetailsChange'])) {   
	  creator()->character->playerName		= $_POST['playerName'];
	  creator()->character->charName			= $_POST['characterName'];
	  creator()->character->realAge			= $_POST['realAge'];
	  creator()->character->birthGender		= $_POST['birthGender'];
	  creator()->character->note				= $_POST['noteDetails'];
}


//BONUS MALUS MANAGEMENT
if(isset($_POST['addTargetTo'])){
    //error_log(print_r($_POST,true));
    if(!isset($_POST['targetVal'])){
        treatCreatorErrors($return,new EPCreatorErrors("Must select an item!",EPCreatorErrors::$SYSTEM_ERROR));
    }

	if($_POST['parentType'] == "origine"){
		$currentBck = EpDatabase()->getBackgroundByName($_POST['parentName']);
		$bonusMalusArray = $currentBck->bonusMalus;
	}
	else if($_POST['parentType'] == "faction"){
		$currentBck = EpDatabase()->getBackgroundByName($_POST['parentName']);
		$bonusMalusArray = $currentBck->bonusMalus;
	}
	else if($_POST['parentType'] == "trait"){
		$currentTrait = EPAtom::getAtomByName(creator()->getCurrentTraits(),$_POST['parentName']);
		if(!isset($currentTrait)){
			$currentTrait = EpDatabase()->getTraitByName($_POST['parentName']);
		}
		$bonusMalusArray = $currentTrait->bonusMalus;
	}
	else if($_POST['parentType'] == "morph"){
		$currentMorph = EPAtom::getAtomByName(creator()->getCurrentMorphs(),$_POST['parentName']);
		if(!isset($currentMorph)){
			$currentMorph = EpDatabase()->getMorphByName($_POST['parentName']);
		}
		$bonusMalusArray = $currentMorph->bonusMalus;
	}
	else if($_POST['parentType'] == "morphTrait"){
		$currentMorph = EPAtom::getAtomByName(creator()->getCurrentMorphs(),$_SESSION['currentMorph']);
        $traits = creator()->getCurrentMorphTraits($currentMorph->name);
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
			$skill = creator()->getSkillByAtomUid($_POST['targetVal']);
            // Database skills (non user selectable) use name/prefix instead of Uid
            if(!isset($skill)){
                $skill = EPSkill::getSkill(creator()->character->ego->skills,$candidat->forTargetNamed,$candidat->typeTarget);
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
            $skill = creator()->getSkillByAtomUid($_POST['targetVal']);
            // Database skills (non user selectable) use name/prefix instead of Uid
            if(!isset($skill)){
                $skill = EPSkill::getSkill(creator()->character->ego->skills,$candidat->forTargetNamed,$candidat->typeTarget);
            }
            if(!isset($skill)){
                treatCreatorErrors($return,new EPCreatorErrors("Bonus Malus Unknown skill",EPCreatorErrors::$SYSTEM_ERROR));
            }
            $candidat->typeTarget = $skill->prefix;
        }
    }
    creator()->adjustAll();
}

if(isset($_POST['removeTargetFrom'])){
	//error_log(print_r($_POST,true));
	if($_POST['parentType'] == "origine"){
		$currentBck = EpDatabase()->getBackgroundByName($_POST['parentName']);
		$bonusMalusArray = $currentBck->bonusMalus;
	}
	else if($_POST['parentType'] == "faction"){
		$currentBck = EpDatabase()->getBackgroundByName($_POST['parentName']);
		$bonusMalusArray = $currentBck->bonusMalus;
	}
	else if($_POST['parentType'] == "trait"){
		$currentTrait = EpDatabase()->getTraitByName($_POST['parentName']);
		$bonusMalusArray = $currentTrait->bonusMalus;
	}
	else if($_POST['parentType'] == "morph"){
		$currentMorph = EpDatabase()->getMorphByName($_POST['parentName']);
		$bonusMalusArray = $currentMorph->bonusMalus;
	}
	else if($_POST['parentType'] == "morphTrait"){
		$currentMorph = EPAtom::getAtomByName(creator()->getCurrentMorphs(),$_SESSION['currentMorph']);
                $traits = creator()->getCurrentMorphTraits($currentMorph->name);
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
    creator()->adjustAll();
}

//ADD OCCURENCE 
if(isset($_POST['addOccurence'])){
	
	if($_POST['addOccurence'] == "AI"){
		$currentOccu = EpDatabase()->getAiByName($_SESSION['currentAiName'])->occurence;
		if(!creator()->setOccurenceIA($_SESSION['currentAiName'],$currentOccu+1)){
			treatCreatorErrors($return, creator()->getLastError());
		}
	}
	
	if($_POST['addOccurence'] == "SOFT"){
		$currentOccu = EPAtom::getAtomByName(creator()->getEgoSoftGears(),$_SESSION['currentSoftName'])->occurence;
		if(!creator()->setOccurenceGear($_SESSION['currentSoftName'],$currentOccu+1)){
			treatCreatorErrors($return, creator()->getLastError());
		}
	}
	
	if($_POST['addOccurence'] == "MORPH"){
		$currentOccu = EPAtom::getAtomByName(EpDatabase()->getGearForMorphName($_SESSION['currentMorph']),$_SESSION['currentMorphGearName'])->occurence;
		if(!creator()->setOccurenceGear($_SESSION['currentMorphGearName'],$currentOccu+1,$_SESSION['currentMorph'])){
			treatCreatorErrors($return, creator()->getLastError());
		}
	}

}


//REMOVE OCCURENCE
if(isset($_POST['remOccurence'])){
	
	if($_POST['remOccurence'] == "AI"){
		$currentOccu = EpDatabase()->getAiByName($_SESSION['currentAiName'])->occurence;
		if(!creator()->setOccurenceIA($_SESSION['currentAiName'],$currentOccu-1)){
			treatCreatorErrors($return, creator()->getLastError());
		}
	}
	
	if($_POST['remOccurence'] == "SOFT"){
		$currentOccu = EPAtom::getAtomByName(creator()->getEgoSoftGears(),$_SESSION['currentSoftName'])->occurence;
		if(!creator()->setOccurenceGear($_SESSION['currentSoftName'],$currentOccu-1)){
			treatCreatorErrors($return, creator()->getLastError());
		}
	}
	
	if($_POST['remOccurence'] == "MORPH"){
		$currentOccu = EPAtom::getAtomByName(EpDatabase()->getGearForMorphName($_SESSION['currentMorph']),$_SESSION['currentMorphGearName'])->occurence;
		if(!creator()->setOccurenceGear($_SESSION['currentMorphGearName'],$currentOccu-1,$_SESSION['currentMorph'])){
			treatCreatorErrors($return, creator()->getLastError());
		}
	}

}


//GET CREATION POINTS -- MUST STAY LAST !!
if(isset($_POST['getCrePoint']) && null !== creator()) {
	$return['creation_remain'] = creator()->getCreationPoint();
	$return['credit_remain'] = creator()->getCredit();
	$return['aptitude_remain'] = creator()->getAptitudePoint();
	$return['reputation_remain'] = creator()->getReputationPoints();
    $return['rez_remain'] = creator()->getRezPoints();
    $return['asr_remain'] = creator()->getActiveRestNeed();
    $return['ksr_remain'] = creator()->getKnowledgeRestNeed();
}

//error_log(print_r($return,true));

echo json_encode($return);
