<?php
declare(strict_types=1);

namespace App\Creator\Atoms;

/**
 * A character's Aptitude
 *
 * This determines unskilled rolls, and is the base value for skills.
 * Normally has a default max of 30, but can be modified by many things.
 *
 * @author reinhardt
 */
class EPAptitude extends EPAtom{
    static $COGNITION = 'COG';
    static $COORDINATION = 'COO';
    static $INTUITION = 'INT';
    static $REFLEXS = 'REF';
    static $SAVVY = 'SAV';
    static $SOMATICS = 'SOM';
    static $WILLPOWER  = 'WIL';
    
    public $abbreviation;
    public $value;
    
    public $maxEgoValue;
    public $maxEgoValueMorphMod;
    public $maxEgoValueTraitMod;
    public $maxEgoValueBackgroundMod;
    public $maxEgoValueFactionMod;
    public $maxEgoValueSoftgearMod;
    public $maxEgoValuePsyMod;
    
    public $minEgoValue;
    public $minEgoValueMorphMod;
    public $minEgoValueTraitMod;
    public $minEgoValueBackgroundMod;
    public $minEgoValueFactionMod;
    public $minEgoValueSoftgearMod;
    public $minEgoValuePsyMod;
    
    public $maxMorphValue;
    public $maxMorphValueMorphMod;
    public $maxMorphValueTraitMod;
    public $maxMorphValueBackgroundMod;
    public $maxMorphValueFactionMod;
    public $maxMorphValueSoftgearMod;
    public $maxMorphValuePsyMod;
    
    public $minMorphValue;
    public $minMorphValueMorphMod;
    public $minMorphValueTraitMod;
    public $minMorphValueBackgroundMod;
    public $minMorphValueFactionMod;
    public $minMorphValueSoftgearMod;
    public $minMorphValuePsyMod;
        
    //Special for feeble negative trait
    public $feebleMax;
    
    public $absoluteMaxValue;
            
    public $morphMod;
    public $traitMod;
    public $backgroundMod;
    public $factionMod;
    public $softgearMod;
    public $psyMod;

    /**
     * TODO:  This is way too much coupling, and should be removed
     * @var EPMorph|null
     */
    public $activMorph;
    public $maxValue;
    public $minValue;

    function getMaxEgoValue(){
        $res =  $this->maxEgoValue + $this->maxEgoValueMorphMod + $this->maxEgoValueTraitMod + 
                $this->maxEgoValueBackgroundMod + $this->maxEgoValueFactionMod +
                $this->maxEgoValueSoftgearMod + $this->maxEgoValuePsyMod;
        // Special case Feeble negative trait
        if ($this->feebleMax){
            $res = min(4,$res);
        } 
        return min($res,$this->absoluteMaxValue);
    }
    function getMinEgoValue(){
        return  $this->minEgoValue + $this->minEgoValueMorphMod + $this->minEgoValueTraitMod + 
                $this->minEgoValueBackgroundMod + $this->minEgoValueFactionMod +
                $this->minEgoValueSoftgearMod + $this->minEgoValuePsyMod;
    }
    function getMaxMorphValue(){
        $res =  $this->maxMorphValue + $this->maxMorphValueMorphMod + $this->maxMorphValueTraitMod + 
                $this->maxMorphValueBackgroundMod + $this->maxMorphValueFactionMod +
                $this->maxMorphValueSoftgearMod + $this->maxMorphValuePsyMod;
        // Special case Feeble negative trait
        if ($this->feebleMax){
            $res = min(10,$res);
        } 
        return min($res,$this->absoluteMaxValue);        
    }
    function getMinMorphValue(){
        return  $this->minMorphValue + $this->minMorphValueMorphMod + $this->minMorphValueTraitMod + 
                $this->minMorphValueBackgroundMod + $this->minMorphValueFactionMod +
                $this->minMorphValueSoftgearMod + $this->minMorphValuePsyMod;
    }  
    function getValue(){
        $res = $this->value + $this->backgroundMod + $this->factionMod;
        $res = min($res,$this->getMaxEgoValue());       
        $res += $this->traitMod + $this->softgearMod + $this->psyMod;
        
        if ($this->activMorph){
            $res += $this->morphMod;
            $res = min($res,$this->getMaxMorphValue());          
        }         

        return min($res,$this->absoluteMaxValue);            
    }
    function getValueForCpCost(){
        $res = $this->value + $this->backgroundMod + $this->factionMod;
        $res = min($res,$this->getMaxEgoValue());

        return $res;            
    }
    function getEgoValue(){
        $res = $this->value + $this->backgroundMod + $this->factionMod;
        $res = min($res,$this->getMaxEgoValue());
        $res += $this->softgearMod + $this->psyMod;

        return min($res,$this->absoluteMaxValue);            
    }
    function getSavePack(): array
    {
        $savePack = parent::getSavePack();
  
        $savePack['abbreviation'] =  $this->abbreviation;
        $savePack['value'] =  $this->value;
        $savePack['maxValue'] =  $this->maxValue;
        $savePack['minValue'] =  $this->minValue;
        $savePack['morphMod'] =  $this->morphMod;
        $savePack['traitMod'] =  $this->traitMod;
        $savePack['backgroundMod'] =  $this->backgroundMod;
        $savePack['factionMod'] =  $this->factionMod;
        $savePack['softgearMod'] =  $this->softgearMod;
        $savePack['psyMod'] =  $this->psyMod;
        $savePack['activMorph'] =  $this->activMorph;
        $savePack['absoluteMaxValue'] =  $this->absoluteMaxValue;

        return $savePack;
    }	
    function loadSavePack($savePack,$cc = null){
        parent::loadSavePack($savePack);
	    
        $this->abbreviation = $savePack['abbreviation'];
        $this->value = $savePack['value'];
        $this->maxValue = $savePack['maxValue'];
        $this->minValue = $savePack['minValue'];
        $this->morphMod = $savePack['morphMod'];
        $this->traitMod = $savePack['traitMod'];
        $this->backgroundMod = $savePack['backgroundMod'];
        $this->factionMod = $savePack['factionMod'];
        $this->softgearMod = $savePack['softgearMod'];
        $this->psyMod = $savePack['psyMod'];
        $this->activMorph = $savePack['activMorph'];
        $this->absoluteMaxValue = $savePack['absoluteMaxValue'];
    }
    function __construct($atName,$abbreviation,$atDesc = '', $groups = array(),$baseValue = 0, $maxValue = 0, $minValue = 0, $absoluteMaxValue = 0) {
        parent::__construct(EPAtom::$APTITUDE, $atName, $atDesc);
        $this->abbreviation = $abbreviation;
        $this->value = $baseValue;
        $this->morphMod = 0;
        $this->traitMod = 0;             
        $this->backgroundMod = 0;
        $this->factionMod = 0;
        $this->softgearMod = 0;
        $this->psyMod = 0;
        $this->groups = $groups;
        $this->maxValue = $maxValue;
        $this->minValue = $minValue;
        $this->minEgoValue = $minValue;
        $this->maxEgoValue = $maxValue;
        $this->minMorphValue = $minValue;
        $this->maxMorphValue = $maxValue;
        $this->activMorph = null;
        $this->feebleMax = false;
        $this->absoluteMaxValue = $absoluteMaxValue;
    }
}
