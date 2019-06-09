<?php
declare(strict_types=1);

namespace App\Creator\Atoms;

/**
 * The players reputation with a single faction
 *
 * This is how much a particular factionion likes you and can be used like a currency.
 *
 * @author reinhardt
 */
class EPReputation extends EPAtom{

    /**
     * @var int
     */
    public $value;
    
    public $morphMod;
    public $traitMod;
    public $backgroundMod;
    public $factionMod;
    public $softgearMod;
    public $psyMod;

    /**
     * @var int
     */
    public $maxValue;
    public $maxValueMorphMod;
    public $maxValueTraitMod;
    public $maxValueBackgroundMod;
    public $maxValueFactionMod;
    public $maxValueSoftgearMod;
    public $maxValuePsyMod;
    
    public $absoluteValueMorphMod;
    public $absoluteValueTraitMod;
    public $absoluteValueBackgroundMod;
    public $absoluteValueFactionMod;
    public $absoluteValueSoftgearMod;
    public $absoluteValuePsyMod;
    
    function getMaxValue(){
        return  $this->maxValue + $this->maxValueMorphMod + $this->maxValueTraitMod + 
                $this->maxValueBackgroundMod + $this->maxValueFactionMod +
                $this->maxValueSoftgearMod + $this->maxValuePsyMod;
    }
    function getAbsoluteValue(){
        $max = $this->absoluteValueMorphMod;
        $max = min($max,$this->absoluteValueTraitMod);
        $max = min($max,$this->absoluteValueBackgroundMod);
        $max = min($max,$this->absoluteValueFactionMod);
        $max = min($max,$this->absoluteValueSoftgearMod);
        $max = min($max,$this->absoluteValuePsyMod);
        return $max;
    }
    function getSavePack(): array
    {
        $savePack = parent::getSavePack();
	    
        $savePack['value'] =  $this->value;
        $savePack['morphMod'] =  $this->morphMod;
        $savePack['traitMod'] =  $this->traitMod;
        $savePack['backgroundMod'] =  $this->backgroundMod;
        $savePack['factionMod'] =  $this->factionMod;
        $savePack['softgearMod'] =  $this->softgearMod;
        $savePack['psyMod'] =  $this->psyMod;
        $savePack['maxValue'] =  $this->maxValue;
        
        return $savePack;
    }
    function loadSavePack($savePack,$cc = null){
	parent::loadSavePack($savePack);    
	    
        $this->value = $savePack['value'];
        $this->morphMod = $savePack['morphMod'];
        $this->traitMod = $savePack['traitMod'];
        $this->backgroundMod = $savePack['backgroundMod'];
        $this->factionMod = $savePack['factionMod'];
        $this->softgearMod = $savePack['softgearMod'];
        $this->psyMod = $savePack['psyMod'];
        $this->maxValue = $savePack['maxValue'];   
    }

    /**
     * EPReputation constructor.
     * @param string   $name
     * @param string   $description
     * @param string[] $groups
     */
    function __construct(string $name, string $description, array $groups = array())
    {
        parent::__construct($name, $description);
        $this->value = 0;
        $this->morphMod = 0;
        $this->traitMod = 0;             
        $this->backgroundMod = 0;
        $this->factionMod = 0;
        $this->softgearMod = 0;
        $this->psyMod = 0;
        $this->groups = $groups;
        $this->maxValue = config('epcc.RepMaxPoint');
    }
    function getValue(){
        return $this->value + $this->morphMod + $this->traitMod + $this->backgroundMod + $this->factionMod + $this->softgearMod + $this->psyMod;
        
    }
}
