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
     
     static $NO_DEFAULTABLE = 'N';
     static $DEFAULTABLE = 'Y';

    /**
     * Contains either AST or KST
     * TODO:  Convert this to an 'isActive' bool (with backwards compatibility)
     * @var string Used to determine if the skill is an active or Knowledge skill
     */
     private $skillType;

    /**
     * TODO:  Make this private with a getter (no setter)
     * @var string
     */
     public $prefix;   
     
     public $baseValue;
     
     public $morphMod;
     public $traitMod;
     public $backgroundMod;
     public $factionMod;
     public $softgearMod;
     public $psyMod;
     
     public $defaultable;
     public $tempSkill; //for skill not loaded from database
     public $specialization;
     public $isNativeTongue;

     public $groups;

    /**
     * Linked Aptitude
     * //TODO:  Rename this to be more clear
     * @var EPAptitude
     */
    public $linkedApt;
     
     public $maxValue;
     public $maxValueMorphMod;
     public $maxValueTraitMod;
     public $maxValueFactionMod;
     public $maxValueBackgroundMod;
     public $maxValuePsyMod;
     public $maxValueSoftgearMod;
     
     function getMaxValue(){
        return  $this->maxValue + $this->maxValueMorphMod + $this->maxValueTraitMod + 
                $this->maxValueBackgroundMod + $this->maxValueFactionMod +
                $this->maxValueSoftgearMod + $this->maxValuePsyMod;
     }    
     function getRatioCost(){
         return $this->ratioCostMorphMod * $this->ratioCostTraitMod * $this->ratioCostFactionMod * $this->ratioCostBackgroundMod * $this->ratioCostPsyMod * $this->ratioCostSoftgearMod;
     }

    /**
     * Get the base value a player sees without a morph
     * @return int
     */
    function getEgoValue(){
        $lnk = isset($this->linkedApt)? $this->linkedApt->getEgoValue(): 0;
        $nativeTongueBonus = $this->isNativeTongue ? config('epcc.NativeTongueBaseValue') : 0;
        return $lnk + $this->baseValue + $nativeTongueBonus + $this->traitMod + $this->backgroundMod + $this->factionMod + $this->softgearMod + $this->psyMod;
    }
    /**
     * Get the total skill value
     * @return int
     */
    function getValue(){
        // Only defaultable skills and skills that the user has put at least a point into are usable
        if (strcmp($this->defaultable,  EPSkill::$DEFAULTABLE) == 0 || $this->baseValue > 0){
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
        return $underLimitCost + $overLimitCost;
    }

    /**
     * How many skill points are from extra bonuses (specifically for calculating the cost of the skill)
     * @return int
     */
    function getBonusForCost(){
         if (isset($this->linkedApt)){
             $lnk = $this->linkedApt->getValueForCpCost();
         }else{
             $lnk = 0;
         }
         return $lnk + $this->backgroundMod + $this->factionMod;       
     }
     
    function getSavePack(): array
    {
        $savePack = parent::getSavePack();
	    
        $savePack['skillType'] =  $this->skillType;
        $savePack['prefix'] =  $this->prefix;   
        $savePack['baseValue'] =  $this->baseValue;
        $savePack['morphMod'] =  $this->morphMod;
        $savePack['traitMod'] =  $this->traitMod;
        $savePack['backgroundMod'] =  $this->backgroundMod;
        $savePack['factionMod'] =  $this->factionMod;
        $savePack['softgearMod'] =  $this->softgearMod;
        $savePack['psyMod'] =  $this->psyMod;
        $savePack['defaultable'] =  $this->defaultable;
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
    function loadSavePack($savePack,$cc = null){
	parent::loadSavePack($savePack);
	    
        $this->skillType = $savePack['skillType'];
        $this->prefix = $savePack['prefix'];   
        $this->baseValue = $savePack['baseValue'];
        $this->morphMod = $savePack['morphMod'];
        $this->traitMod = $savePack['traitMod'];
        $this->backgroundMod = $savePack['backgroundMod'];
        $this->factionMod = $savePack['factionMod'];
        $this->softgearMod = $savePack['softgearMod'];
        $this->psyMod = $savePack['psyMod'];
        $this->defaultable = $savePack['defaultable'];
        $this->tempSkill = $savePack['tempSkill']; 
        $this->specialization = $savePack['specialization'];
        $this->isNativeTongue = $savePack['isNativeTongue'];
        if(isset($savePack['groupsArray'])){
                foreach($savePack['groupsArray'] as $m){
                    array_push($this->groups, $m);
                } 
            }	
        $this->maxValue = $savePack['maxValue'];     
        $this->maxValueMorphMod = $savePack['maxValueMorphMod'];
        $this->maxValueTraitMod = $savePack['maxValueTraitMod'];
        $this->maxValueFactionMod = $savePack['maxValueFactionMod'];
        $this->maxValueBackgroundMod = $savePack['maxValueBackgroundMod'];
        $this->maxValuePsyMod = $savePack['maxValuePsyMod'];
        $this->maxValueSoftgearMod = $savePack['maxValueSoftgearMod'];	 
    }
    function __construct($atName, $atDesc, $linkedApt,$skillType,$defaultable,$prefix="",$groups = array(),$baseValue = 0, $tempSkill = false) {
         parent::__construct(trim($atName), trim($atDesc));
         $this->linkedApt = $linkedApt;
         $this->skillType = $skillType;
         $this->prefix = trim($prefix);
         $this->baseValue = $baseValue;
         $this->defaultable = $defaultable;
         $this->groups = $groups;
         $this->morphMod = 0;
         $this->traitMod = 0;             
         $this->backgroundMod = 0;
         $this->factionMod = 0;
         $this->softgearMod = 0;
         $this->psyMod = 0;
         $this->tempSkill = $tempSkill;
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
        if (strcasecmp($skill->getName(),$this->getName()) == 0 && strcasecmp($skill->prefix,$this->prefix) == 0){
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
        if ($this->defaultable == EPSkill::$NO_DEFAULTABLE) {
            $nameStr .= " *";
        }
        return $nameStr;
    }

    /**
     * Standard getter to save some comparison operators
     * @return bool
     */
    public function isKnowledge(): bool
    {
        return $this->skillType == EPSkill::$KNOWLEDGE_SKILL_TYPE;
    }

    /**
     * Standard getter to save some comparison operators
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->skillType == EPSkill::$ACTIVE_SKILL_TYPE;
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
            if (strcasecmp($aSkill->getName(), $name) == 0 && strcasecmp($aSkill->prefix, $prefix) == 0) {
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
