<?php
require_once 'EPAtom.php';
/**
 * All the plus and minuses, buffs and debuffs applied to many different things.
 *
 * @author reinhardt
 */
class EPBonusMalus extends EPAtom{
    
    static $ON_APTITUDE = 'OA';
    static $ON_APTITUDE_MORPH_MAX = 'AMM';
    static $ON_APTITUDE_EGO_MAX = 'AEM';
    static $ON_APTITUDE_MORPH_MIN = 'AMI';
    static $ON_APTITUDE_EGO_MIN = 'AEI';  
    static $ON_SKILL = 'OS';
    static $ON_SKILL_MAX = 'OSM';
    static $ON_SKILL_PREFIX = 'OSP'; //on all skill with the prefix
    static $ON_SKILL_TYPE = 'OST'; //on active or knowledge
    static $ON_ARMOR = 'OAR';
    static $ON_ENERGY_ARMOR = 'OEA';
    static $ON_KINETIC_ARMOR = 'OCA';
    static $ON_ENERGY_WEAPON_DAMAGE = 'OEW';
    static $ON_KINETIC_WEAPON_DAMAGE = 'OKW';
    static $ON_MELEE_WEAPON_DAMAGE = 'OW';
    static $ON_REPUTATION = 'OR';
    static $ON_GROUP = 'OG';
    static $ON_STAT = 'OSA';
    static $ON_STAT_MULTIPLI = 'STM';
    static $ON_CREDIT = 'OC';
    static $ON_MORPH = 'OM';
    static $ON_REPUTATION_POINTS = 'ORP';
    static $ON_REPUTATION_MAX = 'ORM';
    static $ON_REPUTATION_ABSOLUTE = 'ORA';
    static $MULTIPLE = 'MUL';
    static $DESCRIPTIVE_ONLY = 'DO';
    static $ON_IMPLANT = 'OI';
    
    // Special case for Feeble negative trait
    static $ON_SPECIAL_01 = 'S01';
    // Special case for implant rejection level II trait
    static $ON_SPECIAL_02 = 'S02';
    
    //This constant are use by the GUI on the $targetForChoice for choiced skill
    static $ON_SKILL_WITH_PREFIX = 'SWP';
    static $ON_SKILL_ACTIVE = 'SAC';
    static $ON_SKILL_KNOWLEDGE = 'SKN';
    static $ON_SKILL_ACTIVE_AND_KNOWLEDGE = 'SAK';
    //---------
       
    static $FROM_MORPH = 'MORPH';
    static $FROM_TRAIT = 'TRAIT';
    static $FROM_FACTION = 'FACTION';
    static $FROM_BACKGROUND = 'BACKGROUND';
    static $FROM_SOFTGEAR = 'SOFTGEAR';
    static $FROM_PSY = 'PSY';
    
    public $bonusMalusType;
    public $forTargetNamed; //target of the bonus malus, can be set by user
    public $value; //value of the bonus or malus
    
    public $targetForChoice; //$ON_SKILL, $ON_ARMOR, etc.
    public $typeTarget; //fex : Networing, pilot if no choice get the prefix
    public $onCost; //true if this is a cost modificator
    
    public $bonusMalusTypes; // Recursive structure holding an array of EPBonusMalus
    public $multi_occurence; // How many $bonusMalusTypes the user is to select
    public $selected; // same a radio button group .... ( O X X O O X O) 
    
    function getSavePack(){
        $savePack = parent::getSavePack();
	    
        $savePack['bonusMalusType'] =  $this->bonusMalusType;
        $savePack['forTargetNamed'] =  $this->forTargetNamed;
        $savePack['value'] =  $this->value;
        $savePack['targetForChoice'] =  $this->targetForChoice; 
        $savePack['typeTarget'] =  $this->typeTarget; 
        $savePack['onCost'] =  $this->onCost;
        $savePack['multi_occurence'] =  $this->multi_occurence; 
        $savePack['selected'] =  $this->selected;
        $bmSavePacks = array();
        foreach($this->bonusMalusTypes as $m){
            array_push($bmSavePacks	, $m->getSavePack());
        }
        $savePack['bonusMalusTypes'] = $bmSavePacks;
	    
	return $savePack;
    }
    function loadSavePack($savePack,$cc = null){
	parent::loadSavePack($savePack);
	    
        $this->bonusMalusType = $savePack['bonusMalusType'];
        $this->forTargetNamed = $savePack['forTargetNamed'];
        $this->value = $savePack['value'];
        $this->targetForChoice = $savePack['targetForChoice']; 
        $this->typeTarget = $savePack['typeTarget']; 
        $this->onCost = $savePack['onCost'];
        $this->multi_occurence = $savePack['multi_occurence']; 
        $this->selected = $savePack['selected'];
        foreach($savePack['bonusMalusTypes'] as $m){
            $savedBm = new EPBonusMalus('','','');
            $savedBm->loadSavePack($m);
            array_push($this->bonusMalusTypes, $savedBm);
        }
    }   
    function __construct($atName,$type, $value,$targetName = "", $atDesc = "", $groups = array(),$onCost = 'false',$targetforChoice = "", $typeTarget="",$bonusMalusTypes=array(),$multiOccur = 0) {
        parent::__construct(EPAtom::$BONUSMALUS, $atName, $atDesc);
        $this->bonusMalusType = $type;
        $this->forTargetNamed = $targetName;
        $this->value = $value;
        $this->groups = $groups ;
        $this->onCost = $onCost;
        $this->targetForChoice = $targetforChoice;
        $this->typeTarget = $typeTarget;
        $this->bonusMalusTypes = $bonusMalusTypes; //array() bonus malus
        $this->multi_occurence = $multiOccur; // 1 sur 3 ,...
        $this->selected = false;
    }
}
?>
