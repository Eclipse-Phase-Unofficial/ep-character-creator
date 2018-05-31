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
     
     public $skillType;
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
     public $nativeTongueBonus;
     
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
     function getValue(){
         if (isset($this->linkedApt)){
             $lnk = $this->linkedApt->getValue();
         }else{
             $lnk = 0;
         }
         if (strcmp($this->defaultable,  EPSkill::$DEFAULTABLE) == 0 || $this->baseValue > 0){
             return $lnk + $this->baseValue + $this->nativeTongueBonus + $this->morphMod + $this->traitMod + $this->backgroundMod + $this->factionMod + $this->softgearMod + $this->psyMod;
         } 
         return 0;
     }
     function getBonusForCost(){
         if (isset($this->linkedApt)){
             $lnk = $this->linkedApt->getValueForCpCost();
         }else{
             $lnk = 0;
         }
         return $lnk + $this->backgroundMod + $this->factionMod;       
     }
     function getEgoValue(){
	 	if (isset($this->linkedApt)){
             $lnk = $this->linkedApt->getEgoValue();
         }else{
             $lnk = 0;
         }
        return $lnk + $this->baseValue + $this->nativeTongueBonus + $this->traitMod + $this->backgroundMod + $this->factionMod + $this->softgearMod + $this->psyMod;
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
        $savePack['nativeTongueBonus'] =  $this->nativeTongueBonus; 
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
        $this->nativeTongueBonus = $savePack['nativeTongueBonus'];             
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
         parent::__construct(EPAtom::$SKILL, trim($atName), trim($atDesc));
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
         $this->nativeTongueBonus = 0;
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
        if (strcasecmp($skill->name,$this->name) == 0 && strcasecmp($skill->prefix,$this->prefix) == 0){
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
        $nameStr .= $this->name;
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
            if (strcasecmp($aSkill->name, $name) == 0 && strcasecmp($aSkill->prefix, $prefix) == 0) {
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
        $an = $a->prefix . $a->name;
        $bn = $b->prefix . $b->name;

        return strcmp($an, $bn);
    }
}
