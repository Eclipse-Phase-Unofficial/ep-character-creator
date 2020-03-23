<?php
declare(strict_types=1);

namespace App\Creator\Atoms;

/**
 * A skill used by the player.
 *
 * @author reinhardt
 */
class EPSkill extends EPAtom{

     static $ACTIVE_SKILL_TYPE = "AST";
     static $KNOWLEDGE_SKILL_TYPE = "KST";

    /**
     * Used to determine if the skill is an active or Knowledge skill
     * @var bool
     */
     private $isActiveSkill;

    /**
     * @var string|null
     */
     private $prefix;
    /**
     * @var int
     */
     public $baseValue;

    /**
     * @var int
     */
     public $morphMod;
    /**
     * @var int
     */
     public $traitMod;
    /**
     * @var int
     */
     public $backgroundMod;
    /**
     * @var int
     */
     public $factionMod;
    /**
     * @var int
     */
     public $softgearMod;
    /**
     * @var int
     */
     public $psyMod;
    /**
     * If the skill can be used without explicitly putting any points into it.
     * @var bool
     */
     public $isDefaultable;
    /**
     * If skill was not loaded from database
     * @var bool
     */
     public $tempSkill;
    /**
     * @var string
     */
     public $specialization;
    /**
     * If the skill gets the native tongue bonus
     * (Special handling means cost calculations ignore the normal double points at 60 rule)
     * @var bool
     */
     public $isNativeTongue;

     public $groups;

    /**
     * Linked Aptitude
     * TODO: Use a getter/setter, and handle the null case better
     * @var EPAptitude|null
     */
    public $linkedAptitude;

    /**
     * @var int
     */
    public $maxValue;
    /**
     * @var int
     */
    public $maxValueMorphMod;
    /**
     * @var int
     */
    public $maxValueTraitMod;
    /**
     * @var int
     */
    public $maxValueFactionMod;
    /**
     * @var int
     */
    public $maxValueBackgroundMod;
    /**
     * @var int
     */
    public $maxValuePsyMod;
    /**
     * @var int
     */
    public $maxValueSoftgearMod;

     function getMaxValue(){
        return  $this->maxValue + $this->maxValueMorphMod + $this->maxValueTraitMod +
                $this->maxValueBackgroundMod + $this->maxValueFactionMod +
                $this->maxValueSoftgearMod + $this->maxValuePsyMod;
     }

    /**
     * Get the base value a player sees without a morph
     * @return int
     */
    function getEgoValue(){
        $lnk = isset($this->linkedAptitude)? $this->linkedAptitude->getEgoValue(): 0;
        $nativeTongueBonus = $this->isNativeTongue ? config('epcc.NativeTongueBaseValue') : 0;
        return $lnk + $this->baseValue + $nativeTongueBonus + $this->traitMod + $this->backgroundMod + $this->factionMod + $this->softgearMod + $this->psyMod;
    }
    /**
     * Get the total skill value
     * @return int
     */
    function getValue(){
        // Only defaultable skills and skills that the user has put at least a point into are usable
        if ($this->isDefaultable || $this->baseValue > 0){
            return $this->morphMod + $this->getEgoValue();
        }
        return 0;
    }

    /**
     * This is the cost for all the skill points
     * @return int
     */
    function getCost(): int
    {
        $baseValue = $this->baseValue;
        $bonusValue = $this->getBonusForCost();
        $totalCostValue = $baseValue + $bonusValue;

        //Normally if raising an aptitude would cause a skill to raise above 60,
        // the player must pay the normal double cost.
        //However, Native Tongue is a special case, and that doesn't happen.
        //It's impossible to work backwards in some cases, so we're just going to say it always costs base.
        //Languages are no longer skills in 2nd edition so this problem goes away!
        if($this->isNativeTongue)
        {
            return $baseValue * config('epcc.SkillPointUnderCost');
        }
        //If the skill is under 60, then things are easy
        if($totalCostValue < config('epcc.SkillLimitForImprove'))
        {
            return $baseValue * config('epcc.SkillPointUnderCost');
        }

        //If just the bonus is greater than or equal to 60, then we can say everything costs double
        if($bonusValue >= config('epcc.SkillLimitForImprove'))
        {
            return $this->baseValue * config('epcc.SkillPointUpperCost');
        }

        //Re-phrase the limit in relation to the bonus (thanks to the if statement, we know it will always be positive)
        $newLimit =  config('epcc.SkillLimitForImprove') - $bonusValue;

        //Since the skill is over 60, and the new limit is positive, this works
        $underLimitCost = $newLimit * config('epcc.SkillPointUnderCost');
        $overLimitCost = $baseValue - $newLimit * config('epcc.SkillPointUpperCost');
        return (int) $underLimitCost + $overLimitCost;
    }

    /**
     * How many skill points are from extra bonuses (specifically for calculating the cost of the skill)
     * @return int
     */
    function getBonusForCost(): int
    {
         if (isset($this->linkedAptitude)){
             $lnk = $this->linkedAptitude->getValueForCpCost();
         }else{
             $lnk = 0;
         }
         return (int) $lnk + $this->backgroundMod + $this->factionMod;
     }

    function getSavePack(): array
    {
        $savePack = parent::getSavePack();

        $savePack['isActiveSkill'] =  $this->isActiveSkill;
        $savePack['prefix'] =  $this->prefix;
        $savePack['baseValue'] =  $this->baseValue;
        $savePack['morphMod'] =  $this->morphMod;
        $savePack['traitMod'] =  $this->traitMod;
        $savePack['backgroundMod'] =  $this->backgroundMod;
        $savePack['factionMod'] =  $this->factionMod;
        $savePack['softgearMod'] =  $this->softgearMod;
        $savePack['psyMod'] =  $this->psyMod;
        $savePack['isDefaultable'] =  $this->isDefaultable;
        $savePack['tempSkill'] =  $this->tempSkill;
        $savePack['specialization'] =  $this->specialization;
        $savePack['isNativeTongue'] =  $this->isNativeTongue;
        $groupsArray = array();
        if(!empty($this->groups)){
                foreach($this->groups as $m){
                    array_push($groupsArray, $m);
                }
        }
        $savePack['groupsArray'] = $groupsArray;
        $savePack['maxValue'] =  $this->maxValue;
        $savePack['maxValueMorphMod'] =  $this->maxValueMorphMod;
        $savePack['maxValueTraitMod'] =  $this->maxValueTraitMod;
        $savePack['maxValueFactionMod'] =  $this->maxValueFactionMod;
        $savePack['maxValueBackgroundMod'] =  $this->maxValueBackgroundMod;
        $savePack['maxValuePsyMod'] =  $this->maxValuePsyMod;
        $savePack['maxValueSoftgearMod'] =  $this->maxValueSoftgearMod;

        //For backwards compatibility
        $savePack['nativeTongueBonus'] =  $this->isNativeTongue ? config('epcc.NativeTongueBaseValue') : 0;

        return $savePack;
    }

    /**
     * @param array $an_array
     * @return EPSkill
     */
    public static function __set_state(array $an_array)
    {
        //Backwards compatibility with older (pre 1.53) save files
        if(isset($an_array['defaultable'])) {
            $isDefaultable = ($an_array['defaultable'] == 'Y');
        } else {
            $isDefaultable = (bool)$an_array['isDefaultable'];
        }
        if(isset($an_array['skillType'])) {
            $isActive = ($an_array['skillType'] == 'AST');
        } else {
            $isActive = (bool)$an_array['isActiveSkill'];
        }

        $object = new self((string)$an_array['name'], '', $isActive, $isDefaultable);
        parent::set_state_helper($object, $an_array);

        $object->prefix         = (string)$an_array['prefix'];
        $object->baseValue      = (int)$an_array['baseValue'];
        $object->morphMod       = (int)$an_array['morphMod'];
        $object->traitMod       = (int)$an_array['traitMod'];
        $object->backgroundMod  = (int)$an_array['backgroundMod'];
        $object->factionMod     = (int)$an_array['factionMod'];
        $object->softgearMod    = (int)$an_array['softgearMod'];
        $object->psyMod         = (int)$an_array['psyMod'];
        $object->tempSkill      = (bool)$an_array['tempSkill'];
        $object->specialization = (string)$an_array['specialization'];
        $object->isNativeTongue = (bool)$an_array['isNativeTongue'];
        if (isset($an_array['groupsArray'])) {
            foreach ($an_array['groupsArray'] as $m) {
                array_push($object->groups, $m);
            }
        }
        $object->maxValue              = (int)$an_array['maxValue'];
        $object->maxValueMorphMod      = (int)$an_array['maxValueMorphMod'];
        $object->maxValueTraitMod      = (int)$an_array['maxValueTraitMod'];
        $object->maxValueFactionMod    = (int)$an_array['maxValueFactionMod'];
        $object->maxValueBackgroundMod = (int)$an_array['maxValueBackgroundMod'];
        $object->maxValuePsyMod        = (int)$an_array['maxValuePsyMod'];
        $object->maxValueSoftgearMod   = (int)$an_array['maxValueSoftgearMod'];

        //Backwards compatibility with older (pre 1.53) save files
        if(empty($object->prefix)) {
            $object->prefix = null;
        }

        return $object;
    }

    /**
     * EPSkill constructor.
     * @param string          $name
     * @param string          $description
     * @param bool            $isActive
     * @param bool            $isDefaultable
     * @param EPAptitude|null $linkedAptitude
     * @param string|null     $prefix
     * @param array           $groups
     * @param bool            $isTempSkill
     */
    function __construct(
        string $name,
        string $description,
        bool $isActive,
        bool $isDefaultable,
        EPAptitude $linkedAptitude = null,
        ?string $prefix = null,
        array $groups = array(),
        bool $isTempSkill = false
    ) {
        //The `trim`s are because this could be user created.
         parent::__construct(trim($name), trim($description));
         $this->linkedAptitude = $linkedAptitude;
         $this->isActiveSkill  = $isActive;
         $this->prefix         = trim($prefix?? "");
         if(empty($prefix)) {
             $this->prefix = null;
         }
         $this->baseValue = 0;
         $this->isDefaultable = $isDefaultable;
         $this->groups = $groups;
         $this->morphMod = 0;
         $this->traitMod = 0;
         $this->backgroundMod = 0;
         $this->factionMod = 0;
         $this->softgearMod = 0;
         $this->psyMod = 0;
         $this->tempSkill = $isTempSkill;
         $this->specialization = '';
         $this->isNativeTongue = false;
     }

    /**
     * Match identical Skills, even if atom Uids differ
     *
     * Skills are unique by name AND prefix.
     * This is more expensive than EPAtom's version, but catches duplicate skills with different Uids.
     * This is especially important as it prevents users from adding duplicate skills.
     * @param EPSkill $skill
     * @return bool
     */
    public function match($skill): bool{
        if (strcasecmp($skill->getName(),$this->getName()) == 0 && $skill->prefix === $this->prefix){
            return true;
        }
        return false;
    }

    /**
     * Give a name that can be printed out everywhere
     * @return string
     */
    public function getPrintableName(): string
    {
        $nameStr = "";
        if (!empty($this->prefix)) {
            $nameStr .= $this->prefix . " : ";
        }
        $nameStr .= $this->getName();
        if (!$this->isDefaultable) {
            $nameStr .= " *";
        }
        return $nameStr;
    }

    /**
     * If the skill is a Knowledge Skill
     * @return bool
     */
    public function isKnowledge(): bool
    {
        return !$this->isActiveSkill;
    }

    /**
     * If the skill is an Active Skill
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->isActiveSkill;
    }

    /**
     * If the Skill has a prefix, and everything that comes along with that
     * @return bool
     */
    public function hasPrefix(): bool
    {
        return !is_null($this->prefix);
    }

    /**
     * @return string
     */
    public function getPrefixName(): string
    {
        return $this->prefix?? "";
    }

    /**
     * Find a skill in an array.
     *
     * Skills are unique by name AND prefix, so both are important.
     * @param EPSkill[] $skills
     * @param string    $name
     * @param string    $prefix
     * @return EPSkill|null
     */
    public static function getSkill(array $skills, string $name, string $prefix = '')
    {
        foreach ($skills as $aSkill) {
            if (strcasecmp($aSkill->getName(), $name) == 0 && strcasecmp($aSkill->getPrefixName(), $prefix) == 0) {
                return $aSkill;
            }
        }
        return null;
    }


    /**
     * Use with usort to sort skills
     *
     * Usage:  usort($res, [EPSkill::class, 'compareSkillsByPrefixName'])
     * @param EPSkill $a
     * @param EPSkill $b
     * @return int
     */
    public static function compareSkillsByPrefixName(EPSkill $a, EPSkill $b): int
    {
        $an = $a->prefix . $a->getName();
        $bn = $b->prefix . $b->getName();

        return strcmp($an, $bn);
    }
}
