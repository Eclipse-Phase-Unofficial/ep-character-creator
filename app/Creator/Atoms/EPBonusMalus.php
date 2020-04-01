<?php
declare(strict_types=1);

namespace App\Creator\Atoms;

use App\Models\BonusMalus;

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
     * @var BonusMalus
     */
    protected $model;

    /**
     * Target of the bonus malus, can be set by user
     * TODO: This should be pointing to some sort of ID, not just a name
     * @var string
     */
    private $forTargetNamed;

    /**
     * A skill's Prefix
     * Used in combination with $forTargetNamed since ID's aren't being used anywhere
     * @var string
     */
    private $targetSkillPrefix;

    /**
     * Recursive structure holding an array of EPBonusMalus
     * These are all the possible selections, including the ones the user has selected.
     * @var EPBonusMalus[]
     */
    public $bonusMalusTypes;

    /**
     * If this is part of a $bonusMalusTypes collection, this determines if the user has selected this one or not.
     * @var bool
     */
    private $selected;

    function getSavePack(): array
    {
        $savePack = parent::getSavePack();

        $savePack['forTargetNamed']  =  $this->forTargetNamed;
        $savePack['typeTarget']      =  $this->targetSkillPrefix;
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
        $object = new self(BonusMalus::whereName((string)$an_array['name'])->first());
        parent::set_state_helper($object, $an_array);

        $object->forTargetNamed    = (string)$an_array['forTargetNamed'];
        $object->targetSkillPrefix = (string)$an_array['typeTarget'];
        $object->selected          = (bool)$an_array['selected'];
        foreach ($an_array['bonusMalusTypes'] as $m) {
            array_push($object->bonusMalusTypes, EPBonusMalus::__set_state($m));
        }

        return $object;
    }

    /**
     * EPBonusMalus constructor.
     * @param BonusMalus     $model
     */
    function __construct(BonusMalus $model)
    {
        parent::__construct("Unused", "");
        $this->model = $model;

        $this->forTargetNamed = $this->model->target;
        $this->targetSkillPrefix = $this->model->typeTarget;
        $this->selected = false;

        $this->bonusMalusTypes = array();
        foreach($this->model->bonusMalusTypes as $choice) {
            $this->bonusMalusTypes [] = new EPBonusMalus($choice);
        }

        //TODO:  This should be replaced by a subclassed getter when $this->groups is made private!
        $this->groups = $this->model->groups();
    }

    /**
     * If the BM is granted to the player.
     * @return bool
     */
    function isGranted(): bool
    {
        if (empty($this->getTargetForChoice())) {
            return true;
        }
        return false;
    }

    /**
     * If the BM requires the player to make a *single* choice.
     * @return bool
     */
    function isChoice(): bool
    {
        if ($this->getTargetForChoice() == EPBonusMalus::$ON_SKILL_ACTIVE ||
            $this->getTargetForChoice() == EPBonusMalus::$ON_SKILL_WITH_PREFIX ||
            $this->getTargetForChoice() == EPBonusMalus::$ON_SKILL_KNOWLEDGE ||
            $this->getTargetForChoice() == EPBonusMalus::$ON_SKILL_ACTIVE_AND_KNOWLEDGE ||
            $this->getTargetForChoice() == EPBonusMalus::$ON_REPUTATION ||
            $this->getTargetForChoice() == EPBonusMalus::$ON_APTITUDE) {
            return true;
        }
        return false;
    }

    /**
     * If the BM requires the player to choose between several sub BMs
     * @return bool
     */
    function isMultipleChoice(): bool
    {
        if ($this->getTargetForChoice() == EPBonusMalus::$MULTIPLE) {
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
        if ($this->getTargetForChoice() == EPBonusMalus::$ON_SKILL_WITH_PREFIX ||
            $this->getTargetForChoice() == EPBonusMalus::$ON_SKILL_ACTIVE ||
            $this->getTargetForChoice() == EPBonusMalus::$ON_SKILL_KNOWLEDGE ||
            $this->getTargetForChoice() == EPBonusMalus::$ON_SKILL_ACTIVE_AND_KNOWLEDGE) {
            return true;
        }
        return false;
    }

    /**
     * Get an objects name.
     *
     * May never be empty.
     * @return string
     */
    public function getName(): string
    {
        return $this->model->name;
    }

    /**
     * Get a raw HTML string describing the object.
     *
     * May be empty.
     * @return string
     */
    public function getDescription(): string
    {
        return $this->model->description;
    }

    /**
     * @return string
     */
    public function getBonusMalusType(): string
    {
        return $this->model->type;
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->model->value;
    }

    /**
     * @return string
     */
    public function getTargetForChoice(): string
    {
        return $this->model->targetForChoice;
    }

    /**
     * @return bool
     */
    public function isCostModifier(): bool
    {
        return $this->model->isCostModifier;
    }

    /**
     * @return int
     */
    public function getRequiredSelections(): int
    {
        return $this->model->requiredSelections;
    }

    /**
     * @return string
     */
    public function getTargetSkillPrefix(): string
    {
        return $this->targetSkillPrefix;
    }

    /**
     * @param string $targetSkillPrefix
     * @return EPBonusMalus
     */
    public function setTargetSkillPrefix(string $targetSkillPrefix): EPBonusMalus
    {
        $this->targetSkillPrefix = $targetSkillPrefix;
        return $this;
    }

    /**
     * @return bool
     */
    public function isSelected(): bool
    {
        return $this->selected;
    }

    /**
     * @param bool $selected
     * @return EPBonusMalus
     */
    public function setSelected(bool $selected): EPBonusMalus
    {
        $this->selected = $selected;
        return $this;
    }

    /**
     * @return string
     */
    public function getTargetName(): string
    {
        return $this->forTargetNamed;
    }

    /**
     * @param string $targetName
     * @return EPBonusMalus
     */
    public function setTargetName(string $targetName): EPBonusMalus
    {
        $this->forTargetNamed = $targetName;
        return $this;
    }
}
