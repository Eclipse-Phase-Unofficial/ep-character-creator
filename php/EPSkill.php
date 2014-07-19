<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EPSkill
 *
 * @author reinhardt
 */
require_once 'EPAtom.php';

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
     
    function getSavePack(){
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
         parent::__construct(EPAtom::$SKILL, $atName, $atDesc);
         $this->linkedApt = $linkedApt;
         $this->skillType = $skillType;
         $this->prefix = $prefix;
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
}

?>
