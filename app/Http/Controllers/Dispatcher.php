<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Creator\Atoms\EPMorph;
use App\Creator\EPCharacterCreator;
use App\Creator\EPCreatorErrors;
use App\Creator\EPListProvider;
use App\Creator\Atoms\EPSkill;
use App\Creator\Atoms\EPStat;
use App\Creator\Atoms\EPBonusMalus;
use App\Creator\Atoms\EPGear;
use App\Creator\Atoms\EPAtom;
use Illuminate\Http\Request;

class Dispatcher extends Controller
{
    /**
     * Properly format error messages for UI
     * @param string|EPCreatorErrors $creatorError
     * @return array
     */
    private static function treatCreatorErrors($creatorError){
        if(is_string($creatorError)) {
            return [
                'error'  => true,
                'erType' => 'system',
                'msg'    => "SYSTEM ERROR: $creatorError"
            ];
        }
        else if(strcmp($creatorError->typeError, EPCreatorErrors::$SYSTEM_ERROR) == 0){
            return [
                'error'  => true,
                'erType' => 'system',
                'msg'    => "SYSTEM ERROR: $creatorError->textError"
            ];
        }
        else if(strcmp($creatorError->typeError, EPCreatorErrors::$RULE_ERROR) == 0){
            return [
                'error'  => true,
                'erType' => 'system',
                'msg'    => "RULES: " . $creatorError->getTextOnly()
            ];
        }
        //Should never happen
        return [
            'error'  => true,
            'erType' => 'system',
            'msg'    => "Unknown error: $creatorError->textError"
        ];
    }

    /**
     * Process all the requests through one mega function
     * @param Request $request
     * @return array
     */
    public function process(Request $request){

//INIT
$return = array();
$return['error'] = false;
$provider = new EPListProvider();
	//error_log(print_r($_POST,true));
	//error_log(print_r($_FILES,true));
	//error_log(print_r($_SESSION,true));
//     returnErrors($return,"Forced Error!");

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
    session()->put('cc', new EPCharacterCreator($_POST['setCP']));
    //error_log("NEW CHAR");
}

//GET ORIGINE
if(isset($_POST['getBcg'])){
	if(creator()->getCurrentBackground() != null){
    	$return['currentBcg'] = creator()->getCurrentBackground()->getName();
    	//$return['desc'] = creator()->getCurrentBackground()->description;
    }
    else{
	    $return['currentBcg'] = null;
    }
}
//SET ORIGINE
if (isset($_POST['origine'])) {
    $background = EpDatabase()->getBackgroundByName($_POST['origine']);
    if(!isset($background)) {
        return static::treatCreatorErrors("Background '" . $_POST['origine'] . "' does not exist!'");
    }
    if(!creator()->setBackground($background)){return static::treatCreatorErrors(creator()->getLastError());
    }
}
//GET FACTION
if(isset($_POST['getFac'])){
	if(creator()->getCurrentFaction() != null){
    	$return['currentFac'] = creator()->getCurrentFaction()->getName();
    }
    else{
	    $return['currentFac'] = null;
    }
}
//SET FACTION
if(isset($_POST['faction'])){
    $faction = EpDatabase()->getBackgroundByName($_POST['faction']);
    if(!isset($faction)) {
        return static::treatCreatorErrors("Faction '" . $_POST['faction'] . "' does not exist!'");
    }
    if (!creator()->setFaction($faction)){
        return static::treatCreatorErrors(creator()->getLastError());
    }
}

//SET POS TRAIT
if(isset($_POST['posTrait'])){
    $trait = EpDatabase()->getTraitByName($_POST['posTrait']);
    if(!isset($trait)){
        return static::treatCreatorErrors("Trait ".$_POST['posTrait']." does not exist!");
    }

    if( $trait->isInArray( creator()->getCurrentTraits() ) ){
        if(!creator()->removeTrait($trait)){
            return static::treatCreatorErrors(creator()->getLastError());
        }
    }
    else if(!creator()->addTrait($trait)){
        return static::treatCreatorErrors(creator()->getLastError());
    }
    session()->put('currentTraitName', $trait->getName());
    $return['desc'] = $trait->getDescription();
}

//SET NEG TRAIT
if(isset($_POST['negTrait'])){
    $trait = EpDatabase()->getTraitByName($_POST['negTrait']);
    if(!isset($trait)){
        return static::treatCreatorErrors("Trait ".$_POST['negTrait']." does not exist!");
    }

    if($trait->isInArray(creator()->getCurrentTraits())){
        if(!creator()->removeTrait($trait)){
            return static::treatCreatorErrors(creator()->getLastError());
        }
    }
    else if(!creator()->addTrait($trait)){
        return static::treatCreatorErrors(creator()->getLastError());
    }
    session()->put('currentTraitName', $trait->getName());
    $return['desc'] = $trait->getDescription();
}


//HOVER POS/NEG TRAIT
if(isset($_POST['traitHover'])){
    session()->put('currentTraitName', (string) $_POST['traitHover']);
}

//SET PSY SLEIGHT
if(isset($_POST['psyS'])){
    $psyS = EpDatabase()->getPsySleightsByName($_POST['psyS']);
    if(!isset($psyS)){
        return static::treatCreatorErrors("Psy Sleight ".$_POST['psyS']." does not exist!");
    }

    if( $psyS->isInArray( creator()->getCurrentPsySleights() ) ){
        if(!creator()->removePsySleight($psyS)){
            return static::treatCreatorErrors(creator()->getLastError());
        }
    }
    else if(!creator()->addPsySleight($psyS)){
        return static::treatCreatorErrors(creator()->getLastError());
    }
    $return['desc'] = $psyS->getDescription();
    session()->put('currentPsiSName', $psyS->getName());
}
//HOVER PSY SLEIGHT
if(isset($_POST['hoverPsyS'])){
	session()->put('currentPsiSName', (string) $_POST['hoverPsyS']);
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
	        return static::treatCreatorErrors(creator()->getLastError());
	    }
	}
}

//SET APTITUDES
$aptShorts = ['cog', 'coo', 'int', 'ref', 'sav', 'som', 'wil'];
foreach ($aptShorts as $aptShort) {
    if (isset($_POST[$aptShort])) {
        $errorOnApt = false;
        if (!creator()->setAptitudeValue(strtoupper($aptShort), intval($_POST[$aptShort]))) {
            $errorOnApt = true;
            $return['aptError'] = strtoupper($aptShort);
        }
        if ($errorOnApt) {
            //error_log('ERROR :'.creator()->getLastError()->typeError);
            $return = array_merge($return, static::treatCreatorErrors(creator()->getLastError()));
        }
    }
}

//SET REPUTATION
$repShorts = [
    ['atrep', '@-Rep'],
    ['grep',  'G-Rep'],
    ['crep',  'C-Rep'],
    ['irep',  'I-Rep'],
    ['erep',  'E-Rep'],
    ['rrep',  'R-Rep'],
    ['frep',  'F-Rep']
];
foreach ($repShorts as $repShort) {
    if (isset($_POST[$repShort[0]])) {
        $errorOnRep = false;
        if (!creator()->setReputation($repShort[1], intval($_POST[$repShort[0]]))) {
            $errorOnRep = true;
            $return['repError'] = addcslashes($repShort[1], '@');
        }
        if ($errorOnRep) {
            $return = array_merge($return, static::treatCreatorErrors(creator()->getLastError()));
        }
    }
}

//GET SKILL DECRIPTION
if(isset($_POST['skill'])){
	$skill = creator()->getSkillByAtomUid($_POST['skill']);
	//error_log("Getting skill id: " . $_POST['skill']. " -> " . $skill->getPrintableName());
	$return['title'] = $skill->getPrintableName();
	if($skill->getDescription() == ""){
		$prefix = $skill->prefix;
		if($prefix != null || $prefix != ""){
			$return['desc'] = $provider->getPrefixDescription($prefix);
		}
		else{
			$return['desc'] = "No description available, sorry ...";
		}
	}
	else{
		$return['desc'] = $skill->getDescription();
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
										  return static::treatCreatorErrors(creator()->getLastError());
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
										  return static::treatCreatorErrors(creator()->getLastError());
									  }
}

//ADD NATIVE LANGUAGE SKILL
if( isset($_POST['newNatLanguageSkill']) && !empty($_POST['newNatLanguageSkill']) ){
	if(!creator()->addSkill($_POST['newNatLanguageSkill'],
									  $provider->getAptForPrefix('Language'),
									  EPSkill::$KNOWLEDGE_SKILL_TYPE,
									  EPSkill::$DEFAULTABLE,
									  "Language",
									  [],
									  true)){
										  return static::treatCreatorErrors(creator()->getLastError());
									  }
}



//REMOVE TMP SKILL
if(isset($_POST['remSkill'])){
	$skill = creator()->getSkillByAtomUid($_POST['remSkill']);
	//error_log("removing skill id: " . $_POST['remSkill']. " -> " . $skill->name);
	if($skill != null){
		if(!creator()->removeSkill($skill)){
			return static::treatCreatorErrors(creator()->getLastError());
		}
	}
	else{
		return static::treatCreatorErrors(creator()->getLastError());
	}
}

//CHANGE SKILL VALUE
if(isset($_POST['changeSkill'])){
	if(!creator()->setSkillValue($_POST['changeSkill'], intval($_POST['changeSkillValue']))){
		return static::treatCreatorErrors(creator()->getLastError());
	}
}

//ADD SKILL SPECIALIZATION
if(isset($_POST['addSpe'])){
	$skill = creator()->getSkillByAtomUid($_POST['addSpeSkill']);
	//error_log("adding skill specialization id: " . $_POST['addSpeSkill']. " -> " . $skill->name);
	if($skill == null){
		return static::treatCreatorErrors(creator()->getLastError());
	}
	else if(!creator()->addSpecialization($_POST['addSpe'],$skill)){
		return static::treatCreatorErrors(creator()->getLastError());
	}
}

//REMOVE SKILL SPECIALIZATION
if(isset($_POST['remSpeSkill'])){
	$skill = creator()->getSkillByAtomUid($_POST['remSpeSkill']);
	//error_log("removing skill specialization id: " . $_POST['remSpeSkill']. " -> " . $skill->name);
	if($skill == null){
		return static::treatCreatorErrors(creator()->getLastError());
	}
	else if(!creator()->removeSpecialization($skill)){
		return static::treatCreatorErrors(creator()->getLastError());
	}
}

//HOVER MORPH
if (isset($_POST['morphHover'])) {
	   $morph = EPAtom::getAtomByName(EpDatabase()->getMorphs(),$_POST['morphHover']);
       $return['title'] = $morph->getName();
	   $return['desc'] = $morph->getDescription();
}

//ADD / REMOVE MORPH
if (isset($_POST['addRemMorph'])) {
    $morph = EPAtom::getAtomByName(EpDatabase()->getMorphs(),$_POST['addRemMorph']);
    if(!isset($morph)){
        return static::treatCreatorErrors("Morph does not exist (".session('currentMorph').")");
    }

    if ( $morph->isInArray(creator()->getCurrentMorphs()) ){
        if (!creator()->removeMorph($morph)){
            return static::treatCreatorErrors(creator()->getLastError());
        }
    }else{
        if (creator()->addMorph($morph)){
            session()->put('currentMorph',  $_POST['addRemMorph']);
        }else{
            return static::treatCreatorErrors(creator()->getLastError());
        }
    }
    $return['title'] = $morph->getName();
    $return['desc'] = $morph->getDescription();
}

//GET MORPH SETTINGS
if (isset($_POST['morphSettings'])) {
    $morph = EPAtom::getAtomByName(creator()->character->morphs,$_POST['morphSettings']);
    if(!isset($morph)){
        return static::treatCreatorErrors("Morph does not exist (".session('currentMorph').")");
    }
    session()->put('currentMorph',  $_POST['morphSettings']);
    $return['morphName'] = $morph->getName();
    $return['nickname'] = $morph->nickname;
    $return['location'] = $morph->location;
    $return['age'] = $morph->age;
    $return['gender'] = $morph->gender;
    $return['morphDur'] = $morph->durability;
    $return['morphMaxApt'] = $morph->maxApptitude;
}

//MORPH SELECTED ON GUI
if (isset($_POST['currentMorphUsed'])) {
	 session()->put('currentMorph',  $_POST['currentMorphUsed']);
}


//SET MORPH SETTINGS
if (isset($_POST['morphSettingsChange'])) {
    $morph = creator()->getCurrentMorphsByName($_POST['morphSettingsChange']);
    if(!isset($morph)){
        return static::treatCreatorErrors("Morph does not exist (".$_POST['morphSettingsChange'].")");
    }
    session()->put('currentMorph',  $_POST['morphSettingsChange']);
    $morph->nickname = $_POST['morphNickname'];
    $morph->location = $_POST['morphLocation'];
    $morph->age = $_POST['morphAge'];
    $morph->gender = $_POST['morphGender'];
}

//SET REMOVE MORPH POS TRAIT
if(isset($_POST['morphPosTrait'])){
    $morph = creator()->getCurrentMorphsByName(session('currentMorph'));
    $trait = EpDatabase()->getTraitByName($_POST['morphPosTrait']);

    if (!isset($morph)){
        return static::treatCreatorErrors("Morph does not exist (".session('currentMorph').")");
    }
    if (!isset($trait)){
        return static::treatCreatorErrors("Trait does not exist (".$_POST['morphPosTrait'].")");
    }
    if (creator()->haveTraitOnMorph($trait,$morph)){
        if (!creator()->removeTrait($trait,$morph)){
            return static::treatCreatorErrors(creator()->getLastError());
        }
    }else{
        if (!creator()->addTrait($trait,$morph)){
            return static::treatCreatorErrors(creator()->getLastError());
        }
    }
    $return['desc'] = $trait->getDescription();
    session()->put('currentMorphTraitName', $trait->getName());
}

//SET REMOVE MORPH NEG TRAIT
if(isset($_POST['morphNegTrait'])){
    $morph = creator()->getCurrentMorphsByName(session('currentMorph'));
    $trait = EpDatabase()->getTraitByName($_POST['morphNegTrait']);

    if (!isset($morph)){
        return static::treatCreatorErrors("Morph does not exist (".session('currentMorph').")");
    }
    if (!isset($trait)){
        return static::treatCreatorErrors("Trait does not exist (".$_POST['morphPosTrait'].")");
    }
    if (creator()->haveTraitOnMorph($trait,$morph)){
        if (!creator()->removeTrait($trait,$morph)){
            return static::treatCreatorErrors(creator()->getLastError());
        }
    }else{
        if (!creator()->addTrait($trait,$morph)){
            return static::treatCreatorErrors(creator()->getLastError());
        }
    }
    $return['desc'] = $trait->getDescription();
    session()->put('currentMorphTraitName', $trait->getName());
}

//HOVER MORPH NEG-POS TRAIT
if(isset($_POST['morphTraitHover'])){
    session()->put('currentMorphTraitName', $_POST['morphTraitHover']);
}

//SET REMOVE MORPH IMPLANTS
if(isset($_POST['morphImplant'])){
    $morph = creator()->getCurrentMorphsByName(session('currentMorph'));
    $gear = EpDatabase()->getGearByName($_POST['morphImplant']);

    if (!isset($morph)){
        return static::treatCreatorErrors("Morph does not exist (".session('currentMorph').")");
    }
    if (!isset($gear)){
        return static::treatCreatorErrors("Implant does not exist (".$_POST['morphImplant'].")");
    }
    if (creator()->haveAdditionalGear($gear,$morph)){
        if (!creator()->removeGear($gear,$morph)){
            return static::treatCreatorErrors(creator()->getLastError());
        }
    }else{
        if (!creator()->haveGearOnMorph($gear,$morph)){
            if (!creator()->addGear($gear,$morph)){
                return static::treatCreatorErrors(creator()->getLastError());
            }
        }else{
            return static::treatCreatorErrors("Can not remove permanent Implants!");
        }
    }
    $return['desc'] = $gear->getDescription();
    session()->put('currentMorphGearName', $gear->getName());
}

//SET REMOVE MORPH GEAR
if(isset($_POST['morphGear'])){
    $morph = creator()->getCurrentMorphsByName(session('currentMorph'));
    $gear = EpDatabase()->getGearByName($_POST['morphGear']);

    if (!isset($morph)){
        return static::treatCreatorErrors("Morph does not exist (".session('currentMorph').")");
    }
    if (!isset($gear)){
        return static::treatCreatorErrors("Gear does not exist (".$_POST['morphGear'].")");
    }
    if (creator()->haveAdditionalGear($gear,$morph)){
        if (!creator()->removeGear($gear,$morph)){
            return static::treatCreatorErrors(creator()->getLastError());
        }
    }else{
        if (!creator()->haveGearOnMorph($gear,$morph)){
            if (!creator()->addGear($gear,$morph)){
                return static::treatCreatorErrors(creator()->getLastError());
            }
        }else{
            return static::treatCreatorErrors("Can not remove permanent Gear!");
        }
    }
    $return['desc'] = $gear->getDescription();
    session()->put('currentMorphGearName', $gear->getName());
}

//SET REMOVE FREE MORPH GEAR
if(isset($_POST['morphFreeGear'])){
    //In case someone hits enter/clicks the '+' icon without putting a gear name in
    if(!empty($_POST['morphFreeGear'])){
        $morph = creator()->getCurrentMorphsByName(session('currentMorph'));
        $gear = new EPGear($_POST['morphFreeGear'],'Added by the player',EPGear::$FREE_GEAR,intval($_POST['morphFreePrice']));

        if (!isset($morph)){
            return static::treatCreatorErrors("Morph does not exist (".session('currentMorph').")");
        }
        if (!isset($gear)){
            return static::treatCreatorErrors("Gear does not exist (".$_POST['morphFreeGear'].")");
        }
        //error_log(print_r($gear,true));

        if (creator()->haveAdditionalGear($gear,$morph)){
            if (!creator()->removeGear($gear,$morph)){
                return static::treatCreatorErrors(creator()->getLastError());
            }
        }else{
            if (!creator()->haveGearOnMorph($gear,$morph)){
                creator()->addFreeGear($gear,$morph);
            }else{
                return static::treatCreatorErrors("Can not remove permanent Gear!");
            }
        }
        $return['desc'] = $gear->getDescription();
        session()->put('currentMorphGearName', $gear->getName());
    }
}

//SET REMOVE FREE EGO GEAR
if(isset($_POST['egoFreeGear'])){
    //In case someone hits enter/clicks the '+' icon without putting a gear name in
    if(!empty($_POST['egoFreeGear'])){
        $soft = new EPGear($_POST['egoFreeGear'],'Added by the player',EPGear::$FREE_GEAR,intval($_POST['egoFreePrice']));

        if (!isset($soft)){
            return static::treatCreatorErrors("Gear does not exist (".$_POST['egoFreeGear'].")");
        }

        if (creator()->haveSoftGear($soft)){
            if (!creator()->removeSoftGear($soft)){
                return static::treatCreatorErrors(creator()->getLastError());
            }
        }else{
            if (!creator()->addSoftGear($soft)){
                return static::treatCreatorErrors(creator()->getLastError());
            }
        }
        $return['desc'] = $soft->getDescription();
        session()->put('currentSoftName', $soft->getName());
    }
}



//HOVER ON MORPH GEAR OR IMPLANT
if(isset($_POST['morphImplantGearHover'])){
    session()->put('currentMorphGearName', $_POST['morphImplantGearHover']);
}



//ADD CREDITS
if(isset($_POST['addCredit'])){
	if(!creator()->purchaseCredit(1)){
		 return static::treatCreatorErrors(creator()->getLastError());
	}
}

//REMOVE CREDITS
if(isset($_POST['remCredit'])){
	if(!creator()->saleCredit(1)){
		return static::treatCreatorErrors(creator()->getLastError());
	}
}

//SET REMOVE AI
if(isset($_POST['ai'])){
    $ai = EpDatabase()->getAiByName($_POST['ai']);

    if (!isset($ai)){
        return static::treatCreatorErrors("Ai does not exist (".session('ai').")");
    }
    if (creator()->haveAi($ai)){
        if (!creator()->removeAI($ai)){
            return static::treatCreatorErrors(creator()->getLastError());
        }
    }else{
        if (!creator()->addAI($ai)){
            return static::treatCreatorErrors(creator()->getLastError());
        }
    }
    $return['desc'] = $ai->getDescription();
    session()->put('currentAiName', $ai->getName());
}

//HOVER AI
if(isset($_POST['hoverAi'])){
    session()->put('currentAiName', (string) $_POST['hoverAi']);
}


//SET REMOVE SOFT GEAR
if(isset($_POST['softg'])){
//     error_log(print_r($_POST,true));
    $soft = EpDatabase()->getGearByName($_POST['softg']);

    if (!isset($soft)){
        return static::treatCreatorErrors("Soft gear does not exist (".$_POST['softg'].")");
    }
    if (creator()->haveSoftGear($soft)){
        if (!creator()->removeSoftGear($soft)){
            return static::treatCreatorErrors(creator()->getLastError());
        }
    }else{
        if (!creator()->addSoftGear($soft)){
            return static::treatCreatorErrors(creator()->getLastError());
        }
    }
    $return['desc'] = $soft->getDescription();
    session()->put('currentSoftName', $soft->getName());
}

//HOVER ON SOFT GEAR
if(isset($_POST['hoverSoftg'])){
    session()->put('currentSoftName', (string) $_POST['hoverSoftg']);
}

//ADD MOXIE
if(isset($_POST['addMoxie'])){
	$currentMox = creator()->getStatByAbbreviation(EPStat::$MOXIE)->value;
	$currentMox = $currentMox +1;
	if(!creator()->setStat(EPStat::$MOXIE,$currentMox)){
		 return static::treatCreatorErrors(creator()->getLastError());
	}
}

//REMOVE MOXIE
if(isset($_POST['remMoxie'])){
	$currentMox = creator()->getStatByAbbreviation(EPStat::$MOXIE)->value;
	$currentMox = $currentMox -1;
	if(!creator()->setStat(EPStat::$MOXIE,$currentMox)){
		 return static::treatCreatorErrors(creator()->getLastError());
	}
}

//DESC MOXIE
if(isset($_POST['mox'])){
    $moxie = creator()->getStatByAbbreviation(EPStat::$MOXIE);
    if(!isset($moxie)){
        return static::treatCreatorErrors(creator()->getLastError());
    }
    $return['desc'] = $moxie->getDescription();
}

//SET LAST DETAILS
if (isset($_POST['lastDetailsChange'])) {
	  creator()->character->playerName		= $_POST['playerName'];
	  creator()->character->charName			= $_POST['characterName'];
	  creator()->character->realAge			= $_POST['realAge'];
	  creator()->character->birthGender		= $_POST['birthGender'];
	  creator()->character->note				= $_POST['noteDetails'];
}


//Add a BONUS MALUS to a target
if(isset($_POST['addTargetTo'])){
    //error_log(print_r($_POST,true));
    if(!isset($_POST['targetVal'])){
        return static::treatCreatorErrors(new EPCreatorErrors("Must select an item!",EPCreatorErrors::$SYSTEM_ERROR));
    }
    $parentName      = $_POST['parentName'];
    $bonusMalusArray = [];

    switch ($_POST['parentType']) {
        case "origine":
            $background = creator()->getCurrentBackground();
            if ($background->getName() !== $parentName) {
                return static::treatCreatorErrors(new EPCreatorErrors("May only set Bonus/Malus for your current background!",
                    EPCreatorErrors::$SYSTEM_ERROR));
            }
            $bonusMalusArray = $background->bonusMalus;
            break;
        case "faction":
            $faction = creator()->getCurrentFaction();
            if ($faction->getName() !== $parentName) {
                return static::treatCreatorErrors(new EPCreatorErrors("May only set Bonus/Malus for your current faction!",
                    EPCreatorErrors::$SYSTEM_ERROR));
            }
            $bonusMalusArray = $faction->bonusMalus;
            break;
        case "trait":
            $currentTrait = EPAtom::getAtomByName(creator()->getCurrentTraits(), $parentName);
            if (!isset($currentTrait)) {
                return static::treatCreatorErrors(new EPCreatorErrors("May only set Bonus/Malus for traits you possess!",
                    EPCreatorErrors::$SYSTEM_ERROR));
            }
            $bonusMalusArray = $currentTrait->bonusMalus;
            break;
        case "morph":
            $currentMorph = EPAtom::getAtomByName(creator()->getCurrentMorphs(), $parentName);
            if (!isset($currentMorph)) {
                return static::treatCreatorErrors(new EPCreatorErrors("May only set Bonus/Malus for morphs you possess!",
                    EPCreatorErrors::$SYSTEM_ERROR));
            }
            $bonusMalusArray = $currentMorph->bonusMalus;
            break;
        case "morphTrait":
            if (session('currentMorph') != $parentName) {
                error_log("Setting Bonus/Malus for morph '$parentName', when the current Morph is '" . session('currentMorph') . "''");
            }
            /** @var EPMorph $currentMorph */
            $currentMorph = EPAtom::getAtomByName(creator()->getCurrentMorphs(), (string)session('currentMorph'));
            $traits       = $currentMorph->getTraits();
            if (!empty($traits)) {
                $currentMorphTrait = EPAtom::getAtomByName($traits, $_POST['parentName']);
                $bonusMalusArray   = $currentMorphTrait->bonusMalus;
            }
            break;
        default:
            return static::treatCreatorErrors(new EPCreatorErrors("Unknown parent type",
                EPCreatorErrors::$SYSTEM_ERROR));
    }

	if($_POST['bMcase'] == EPBonusMalus::$MULTIPLE){
		$candidatParent = EPAtom::getAtomByUid($bonusMalusArray,$_POST['parentBmId']);
        if(!isset($candidatParent)){
			return static::treatCreatorErrors(new EPCreatorErrors("Can not add Bonus Malus: Unkown Parent!",EPCreatorErrors::$SYSTEM_ERROR));
		}
		$candidat = EPAtom::getAtomByUid($candidatParent->bonusMalusTypes,$_POST['bmId']);
        if(!isset($candidat)){
			return static::treatCreatorErrors(new EPCreatorErrors("Can not add Bonus Malus: Could not find Bonus Malus",EPCreatorErrors::$SYSTEM_ERROR));
		}
		if($candidat->bonusMalusType == EPBonusMalus::$ON_SKILL){
			$skill = creator()->getSkillByAtomUid($_POST['targetVal']);
            // Database skills (non user selectable) use name/prefix instead of Uid
            if(!isset($skill)){
                $skill = EPSkill::getSkill(creator()->character->ego->skills,$candidat->forTargetNamed,$candidat->typeTarget);
            }
            if(!isset($skill)){
				return static::treatCreatorErrors(new EPCreatorErrors("Bonus Malus Unknown skill",EPCreatorErrors::$SYSTEM_ERROR));
			}
			$candidat->typeTarget = $skill->prefix;
		}
		$candidat->forTargetNamed = $_POST['targetVal'];
		$candidat->selected = true;
	}
	else{
        $candidat = EPAtom::getAtomByUid($bonusMalusArray,$_POST['bmId']);

        if(!isset($candidat)){
            return static::treatCreatorErrors(new EPCreatorErrors("Could not find bmId: ".$_POST['bmId']." for parentType: ".$_POST['parentType'],EPCreatorErrors::$SYSTEM_ERROR));
        }

        $candidat->forTargetNamed = $_POST['targetVal'];
        if($candidat->bonusMalusType == EPBonusMalus::$ON_SKILL){
            $skill = creator()->getSkillByAtomUid($_POST['targetVal']);
            // Database skills (non user selectable) use name/prefix instead of Uid
            if(!isset($skill)){
                $skill = EPSkill::getSkill(creator()->character->ego->skills,$candidat->forTargetNamed,$candidat->typeTarget);
            }
            if(!isset($skill)){
                return static::treatCreatorErrors(new EPCreatorErrors("Bonus Malus Unknown skill",EPCreatorErrors::$SYSTEM_ERROR));
            }
            $candidat->typeTarget = $skill->prefix;
        }
    }
    creator()->adjustAll();
}

//Remove a BONUS MALUS from a target
if(isset($_POST['removeTargetFrom'])){
	//error_log(print_r($_POST,true));
    $parentName      = $_POST['parentName'];
    $bonusMalusArray = [];

    switch ($_POST['parentType']) {
        case "origine":
            $background = creator()->getCurrentBackground();
            if ($background->getName() !== $parentName) {
                return static::treatCreatorErrors(new EPCreatorErrors("May only set Bonus/Malus for your current background!",
                    EPCreatorErrors::$SYSTEM_ERROR));
            }
            $bonusMalusArray = $background->bonusMalus;
            break;
        case "faction":
            $faction = creator()->getCurrentFaction();
            if ($faction->getName() !== $parentName) {
                return static::treatCreatorErrors(new EPCreatorErrors("May only set Bonus/Malus for your current faction!",
                    EPCreatorErrors::$SYSTEM_ERROR));
            }
            $bonusMalusArray = $faction->bonusMalus;
            break;
        case "trait":
            $currentTrait = EPAtom::getAtomByName(creator()->getCurrentTraits(), $parentName);
            if (!isset($currentTrait)) {
                return static::treatCreatorErrors(new EPCreatorErrors("May only set Bonus/Malus for traits you possess!",
                    EPCreatorErrors::$SYSTEM_ERROR));
            }
            $bonusMalusArray = $currentTrait->bonusMalus;
            break;
        case "morph":
            $currentMorph = EPAtom::getAtomByName(creator()->getCurrentMorphs(), $parentName);
            if (!isset($currentMorph)) {
                return static::treatCreatorErrors(new EPCreatorErrors("May only set Bonus/Malus for morphs you possess!",
                    EPCreatorErrors::$SYSTEM_ERROR));
            }
            $bonusMalusArray = $currentMorph->bonusMalus;
            break;
        case "morphTrait":
            if (session('currentMorph') != $parentName) {
                error_log("Setting Bonus/Malus for morph '$parentName', when the current Morph is '" . session('currentMorph') . "''");
            }
            /** @var EPMorph $currentMorph */
            $currentMorph = EPAtom::getAtomByName(creator()->getCurrentMorphs(), (string)session('currentMorph'));
            $traits       = $currentMorph->getTraits();
            if (!empty($traits)) {
                $currentMorphTrait = EPAtom::getAtomByName($traits, $_POST['parentName']);
                $bonusMalusArray   = $currentMorphTrait->bonusMalus;
            }
            break;
        default:
            return static::treatCreatorErrors(new EPCreatorErrors("Unknown parent type",
                EPCreatorErrors::$SYSTEM_ERROR));
    }

	if($_POST['bMcase'] == EPBonusMalus::$MULTIPLE){
		$candidatParent = EPAtom::getAtomByUid($bonusMalusArray,$_POST['parentBmId']);
        if(!isset($candidatParent)){
             return static::treatCreatorErrors(new EPCreatorErrors("Could not find parentBmId: ".$_POST['parentBmId']." for parentType: ".$_POST['parentType'],EPCreatorErrors::$SYSTEM_ERROR));
        }
        $candidat = EPAtom::getAtomByUid($candidatParent->bonusMalusTypes,$_POST['bmId']);
        if(!isset($candidat)){
            return static::treatCreatorErrors(new EPCreatorErrors("Could not find bmId: ".$_POST['bmId']." for parentBmId: ".$_POST['parentBmId'],EPCreatorErrors::$SYSTEM_ERROR));
        }
        if(!empty($candidat->targetForChoice)){
            $candidat->forTargetNamed = "";
        }
        $candidat->selected = false;
	}
	else{
		$candidat = EPAtom::getAtomByUid($bonusMalusArray,$_POST['bmId']);
        if(!isset($candidat)){
            return static::treatCreatorErrors(new EPCreatorErrors("Could not find bmId: ".$_POST['bmId']." for parentType: ".$_POST['parentType'],EPCreatorErrors::$SYSTEM_ERROR));
        }
        $candidat->forTargetNamed = "";
	}
    creator()->adjustAll();
}

//ADD OCCURENCE
if(isset($_POST['addOccurence'])){
    if($_POST['addOccurence'] == "SOFT"){
        $currentSoftGearName = (string) session('currentSoftName');
        /** @var EPGear|null $gear */
        $gear = EPAtom::getAtomByName(creator()->getEgoSoftGears(),$currentSoftGearName);
        if (!isset($gear)){
            return static::treatCreatorErrors("Gear does not exist (".$currentSoftGearName.")");
        }
		if(!creator()->setOccurrenceGear((string) session('currentSoftName'),$gear->getOccurrence()+1)){
			return static::treatCreatorErrors(creator()->getLastError());
		}
	}

	if($_POST['addOccurence'] == "MORPH"){
        $currentMorphGearName = (string) session('currentMorphGearName');
        $morph = creator()->getCurrentMorphsByName(session('currentMorph'));
        if (!isset($morph)){
            return static::treatCreatorErrors("Morph does not exist (".session('currentMorph').")");
        }
        /** @var EPGear|null $gear */
	    $gear = EPAtom::getAtomByName($morph->getGear(), $currentMorphGearName);
        if (!isset($gear)){
            return static::treatCreatorErrors("Gear does not exist (".$currentMorphGearName.")");
        }
		if(!creator()->setOccurrenceGear($currentMorphGearName,$gear->getOccurrence()+1, $morph)){
			return static::treatCreatorErrors(creator()->getLastError());
		}
	}

}


//REMOVE OCCURENCE
if(isset($_POST['remOccurence'])){
	if($_POST['remOccurence'] == "SOFT"){
        $currentSoftGearName = (string) session('currentSoftName');
        /** @var EPGear|null $gear */
        $gear = EPAtom::getAtomByName(creator()->getEgoSoftGears(),$currentSoftGearName);
        if (!isset($gear)){
            return static::treatCreatorErrors("Gear does not exist (".$currentSoftGearName.")");
        }
        if(!creator()->setOccurrenceGear((string) session('currentSoftName'),$gear->getOccurrence()-1)){
            return static::treatCreatorErrors(creator()->getLastError());
        }
	}

	if($_POST['remOccurence'] == "MORPH"){
        $currentMorphGearName = (string) session('currentMorphGearName');
        $morph = creator()->getCurrentMorphsByName(session('currentMorph'));
        if (!isset($morph)){
            return static::treatCreatorErrors("Morph does not exist (".session('currentMorph').")");
        }
        /** @var EPGear|null $gear */
        $gear = EPAtom::getAtomByName($morph->getGear(), $currentMorphGearName);
        if (!isset($gear)){
            return static::treatCreatorErrors("Gear does not exist (".$currentMorphGearName.")");
        }
		if(!creator()->setOccurrenceGear($currentMorphGearName,$gear->getOccurrence()-1, $morph)){
			return static::treatCreatorErrors(creator()->getLastError());
		}
	}

}

//error_log(print_r($return,true));

return $return;
}
}