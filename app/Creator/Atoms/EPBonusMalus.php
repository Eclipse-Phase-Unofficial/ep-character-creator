<?php
declare(strict_types=1);

namespace App\Creator\Atoms;

use InvalidArgumentException;

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

    //This constant are use by the GUI on the $targetForChoice for choice skill
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

    /**
     * An enum for about half the $ON_... static/const values
     * @var string
     */
    public $bonusMalusType;
    /**
     * Target of the bonus malus, can be set by user
     * TODO: This should be pointing to some sort of ID, not just a name
     * @var string
     */
    public $forTargetNamed;
    /**
     * Value of the bonus or malus
     * @var int
     */
    public $value;

    /**
     * An enum for the other half the $ON_... static/const values
     * $ON_SKILL, $ON_ARMOR, etc.
     * @var string
     */
    public $targetForChoice;
    /**
     * A skill's Prefix
     * Used in combination with $forTargetNamed since ID's aren't being used anywhere
     * @var string
     */
    public $typeTarget;
    /**
     * If this modifies the target's cost instead of it's attribute.
     * @var bool
     */
    public $isCostModifier;

    /**
     * Recursive structure holding an array of EPBonusMalus
     * These are all the possible selections, including the ones the user has selected.
     * @var EPBonusMalus[]
     */
    public $bonusMalusTypes;
    /**
     * How many $bonusMalusTypes the user must select
     * @var int
     */
    public $requiredSelections;
    /**
     * If this is part of a $bonusMalusTypes collection, this determines if the user has selected this one or not.
     * @var bool
     */
    public $selected;

    function getSavePack(): array
    {
        $savePack = parent::getSavePack();

        $savePack['bonusMalusType']  =  $this->bonusMalusType;
        $savePack['forTargetNamed']  =  $this->forTargetNamed;
        $savePack['value']           =  $this->value;
        $savePack['targetForChoice'] =  $this->targetForChoice;
        $savePack['typeTarget']      =  $this->typeTarget;
        $savePack['isCostModifier']  =  $this->isCostModifier;
        $savePack['multi_occurence'] =  $this->requiredSelections;
        $savePack['selected']        =  $this->selected;
        $bmSavePacks = array();
        foreach($this->bonusMalusTypes as $m){
            array_push($bmSavePacks	, $m->getSavePack());
        }
        $savePack['bonusMalusTypes'] = $bmSavePacks;

	return $savePack;
    }

    /**
     * @param array $an_array
     * @return EPBonusMalus
     */
    public static function __set_state(array $an_array)
    {
        $object = new self((string)$an_array['name'], '', 0);
        parent::set_state_helper($object, $an_array);

        $object->bonusMalusType     = (string)$an_array['bonusMalusType'];
        $object->forTargetNamed     = (string)$an_array['forTargetNamed'];
        $object->value              = (float)$an_array['value'];
        $object->targetForChoice    = (string)$an_array['targetForChoice'];
        $object->typeTarget         = (string)$an_array['typeTarget'];
        $object->requiredSelections = (int)$an_array['multi_occurence'];
        $object->selected           = (bool)$an_array['selected'];
        foreach ($an_array['bonusMalusTypes'] as $m) {
            array_push($object->bonusMalusTypes, EPBonusMalus::__set_state($m));
        }

        //Backwards compatibility with older (pre 1.53) save files
        if(isset($an_array['onCost'])) {
            $object->isCostModifier = filter_var($an_array['onCost'], FILTER_VALIDATE_BOOLEAN);
        } else {
            $object->isCostModifier = (bool)$an_array['isCostModifier'];
        }

        //Sanity Checks
        if ($object->targetForChoice == EPBonusMalus::$MULTIPLE && $object->requiredSelections <= 0) {
            throw new InvalidArgumentException("Object Should not ask for user choice when there are no selections allowed!");
        }

        return $object;
    }

    /**
     * EPBonusMalus constructor.
     * @param string         $name
     * @param string         $type
     * @param int            $value
     * @param string         $targetName
     * @param string         $description
     * @param string[]       $groups
     * @param bool           $isCostModifier
     * @param string         $targetForChoice
     * @param string         $typeTarget
     * @param EPBonusMalus[] $bonusMalusTypes
     * @param int            $requiredSelections
     */
    function __construct(
        string $name,
        string $type,
        int $value,
        string $targetName = "",
        string $description = "",
        array $groups = array(),
        bool $isCostModifier = false,
        string $targetForChoice = "",
        string $typeTarget = "",
        array $bonusMalusTypes = array(),
        int $requiredSelections = 0
    ) {
        parent::__construct($name, $description);
        $this->bonusMalusType = $type;
        $this->forTargetNamed = $targetName;
        $this->value = $value;
        $this->groups = $groups ;
        $this->isCostModifier = $isCostModifier;
        $this->targetForChoice = $targetForChoice;
        $this->typeTarget = $typeTarget;
        $this->bonusMalusTypes = $bonusMalusTypes; //array() bonus malus
        $this->requiredSelections = $requiredSelections;
        $this->selected = false;

        if ($this->targetForChoice == EPBonusMalus::$MULTIPLE && $this->requiredSelections <= 0) {
            throw new InvalidArgumentException("Object Should not ask for user choice when there are no selections allowed!");
        }
    }

    /**
     * If the BM is granted to the player.
     * @return bool
     */
    function isGranted(): bool
    {
        if($this->targetForChoice == ""){
            return True;
        }
        return False;
    }

    /**
     * If the BM requires the player to make a *single* choice.
     * @return bool
     */
    function isChoice(): bool
    {
        if($this->targetForChoice == EPBonusMalus::$ON_SKILL_ACTIVE ||
           $this->targetForChoice == EPBonusMalus::$ON_SKILL_WITH_PREFIX ||
           $this->targetForChoice == EPBonusMalus::$ON_SKILL_KNOWLEDGE ||
           $this->targetForChoice == EPBonusMalus::$ON_SKILL_ACTIVE_AND_KNOWLEDGE ||
           $this->targetForChoice == EPBonusMalus::$ON_REPUTATION ||
           $this->targetForChoice == EPBonusMalus::$ON_APTITUDE){
            return True;
        }
        return False;
    }

    /**
     * If the BM requires the player to choose between several sub BMs
     * @return bool
     */
    function isMultipleChoice(): bool
    {
        if ($this->targetForChoice == EPBonusMalus::$MULTIPLE) {
            return true;
        }
        return false;
    }

    /**
     * If this affects a skill.
     *
     * It's more convenient than checking directly, since there are multiple BonusMalus skill types.
     * @return bool
     */
    function isSkill()
    {
        if ($this->targetForChoice == EPBonusMalus::$ON_SKILL_WITH_PREFIX ||
            $this->targetForChoice == EPBonusMalus::$ON_SKILL_ACTIVE ||
            $this->targetForChoice == EPBonusMalus::$ON_SKILL_KNOWLEDGE ||
            $this->targetForChoice == EPBonusMalus::$ON_SKILL_ACTIVE_AND_KNOWLEDGE) {
            return true;
        }
        return false;
    }
}
