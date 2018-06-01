<?php
declare(strict_types=1);

namespace App\Creator\Atoms;

/**
 * Calculated Stats & Moxie.
 *
 * @author reinhardt
 */
class EPStat extends EPAtom{
    
    static $MOXIE = 'MOX';
    static $TRAUMATHRESHOLD = 'TT';
    static $INSANITYRATING = 'IR';
    static $LUCIDITY = 'LUC';
    static $DEATHRATING = 'DR';
    static $WOUNDTHRESHOLD = 'WT';
    static $DURABILITY  = 'DUR';
    static $SPEED = 'SPD';
    static $INITIATIVE = 'INI';
    static $DAMAGEBONUS = 'DB';
    
    public $abbreviation;
    public $value;

    /**
     * //TODO:  This potentially introduces cyclic dependencies, and should be removed
     * @var null|\App\Creator\EPCharacterCreator
     */
    public $cc;
    
    public $morphMod;
    public $traitMod;
    public $factionMod;
    public $backgroundMod;
    public $softgearMod;
    public $gearMod;
    public $psyMod;
    
    public $multiMorphMod;
    public $multiTraitMod;
    public $multiFactionMod;
    public $multiBackgroundMod;
    public $multiSoftgearMod;
    public $multiGearMod;
    public $multiPsyMod;    
    
    function getSavePack(): array
    {
        $savePack = parent::getSavePack();
	    
        $savePack['abbreviation'] = $this->abbreviation;
        $savePack['value'] = $this->value;

        $savePack['morphMod'] = $this->morphMod;
        $savePack['traitMod'] = $this->traitMod;
        $savePack['factionMod'] = $this->factionMod;
        $savePack['backgroundMod'] = $this->backgroundMod;
        $savePack['softgearMod'] = $this->softgearMod;
        $savePack['gearMod'] = $this->gearMod;
        $savePack['psyMod'] = $this->psyMod;

        $savePack['multiMorphMod'] = $this->multiMorphMod;
        $savePack['multiTraitMod'] = $this->multiTraitMod;
        $savePack['multiFactionMod'] = $this->multiFactionMod;
        $savePack['multiBackgroundMod'] = $this->multiBackgroundMod;
        $savePack['multiSoftgearMod'] = $this->multiSoftgearMod;
        $savePack['multiGearMod'] = $this->multiGearMod;
        $savePack['multiPsyMod'] = $this->multiPsyMod;
        
        return $savePack;
    }
    function loadSavePack($savePack,$cc = null){
	parent::loadSavePack($savePack);    
	    	    
        $this->abbreviation = $savePack['abbreviation'];
        $this->value = $savePack['value'];
        
        $this->morphMod = $savePack['morphMod'];
        $this->traitMod = $savePack['traitMod'];
        $this->factionMod = $savePack['factionMod'];
        $this->backgroundMod = $savePack['backgroundMod'];
        $this->softgearMod = $savePack['softgearMod'];
        $this->gearMod = $savePack['gearMod'];
        $this->psyMod = $savePack['psyMod']; 
        
        $this->multiMorphMod = $savePack['multiMorphMod'];
        $this->multiTraitMod = $savePack['multiTraitMod'];
        $this->multiFactionMod = $savePack['multiFactionMod'];
        $this->multiBackgroundMod = $savePack['multiBackgroundMod'];
        $this->multiSoftgearMod = $savePack['multiSoftgearMod'];
        $this->multiGearMod = $savePack['multiGearMod'];
        $this->multiPsyMod = $savePack['multiPsyMod']; 
    }
    function __construct($atName, $atDesc, $abbreviation, $groups = array(),$value = 0, &$characterCreator = null) {
        parent::__construct(EPAtom::$STAT, $atName, $atDesc);
        $this->abbreviation = $abbreviation;
        $this->value = $value;
        $this->groups = $groups;
        $this->cc = $characterCreator;
        $this->morphMod = 0;
        $this->traitMod = 0;
        $this->factionMod = 0;
        $this->backgroundMod = 0;
        $this->softgearMod = 0;
        $this->gearMod = 0;
        $this->psyMod = 0;
        
        $this->multiMorphMod = 1;
        $this->multiTraitMod = 1;
        $this->multiFactionMod = 1;
        $this->multiBackgroundMod = 1;
        $this->multiSoftgearMod = 1;
        $this->multiGearMod = 1;
        $this->multiPsyMod = 1;
    }

    function getValue(){
        if (!isset($this->cc)){
            return 0;
        }
        $multi = $this->multiMorphMod * $this->multiTraitMod * $this->multiFactionMod * $this->multiBackgroundMod * $this->multiSoftgearMod * $this->multiGearMod * $this->multiPsyMod;
        switch ($this->abbreviation) {
            case EPStat::$MOXIE:
                return round(($this->value + $this->morphMod + $this->traitMod + $this->factionMod + $this->backgroundMod + $this->softgearMod + $this->gearMod + $this->psyMod) * $multi) ;
                break;
            case EPStat::$LUCIDITY:
                return round(($this->cc->getAptitudeByAbbreviation(EPAptitude::$WILLPOWER)->getValue() * 2  + $this->morphMod + $this->traitMod + $this->factionMod + $this->backgroundMod + $this->softgearMod + $this->gearMod + $this->psyMod) * $multi);
                break;
            case EPStat::$TRAUMATHRESHOLD:
                return round((round($this->cc->getStatByAbbreviation(EPStat::$LUCIDITY)->getValue() / 5)  + $this->morphMod + $this->traitMod + $this->factionMod + $this->backgroundMod + $this->softgearMod + $this->gearMod + $this->psyMod) * $multi);
                break;
            case EPStat::$INSANITYRATING:
                return round(($this->cc->getStatByAbbreviation(EPStat::$LUCIDITY)->getValue() * 2  + $this->morphMod + $this->traitMod + $this->factionMod + $this->backgroundMod + $this->softgearMod + $this->gearMod + $this->psyMod) * $multi);
                break;
            case EPStat::$DURABILITY:
                $morph = $this->cc->getCurrentMorph();
                if (isset($morph)){
                    $res =  $morph->durability;
                }else{
                    $res = 0;
                }
                return round(($res  + $this->morphMod + $this->traitMod + $this->factionMod + $this->backgroundMod + $this->softgearMod + $this->gearMod + $this->psyMod) * $multi);
                break;
            case EPStat::$DEATHRATING:
                $morph = $this->cc->getCurrentMorph();
                if (isset($morph)){
                    if ($morph->morphType != EPMorph::$SYNTHMORPH){
                        $res = round($this->cc->getStatByAbbreviation(EPStat::$DURABILITY)->getValue() * 1.5);
                    }else{
                        $res = round($this->cc->getStatByAbbreviation(EPStat::$DURABILITY)->getValue() * 2);
                    }
                }else{
                    return 0;
                }
                return round(($res  + $this->morphMod + $this->traitMod + $this->factionMod + $this->backgroundMod + $this->softgearMod + $this->gearMod + $this->psyMod) * $multi);
                break;
            case EPStat::$WOUNDTHRESHOLD:
                return round((round($this->cc->getStatByAbbreviation(EPStat::$DURABILITY)->getValue() / 5) + $this->morphMod + $this->traitMod + $this->factionMod + $this->backgroundMod + $this->softgearMod + $this->gearMod + $this->psyMod) * $multi);
                break;
            case EPStat::$INITIATIVE:
                return round((round(($this->cc->getAptitudeByAbbreviation(EPAptitude::$INTUITION)->getValue() + $this->cc->getAptitudeByAbbreviation(EPAptitude::$REFLEXS)->getValue()) / 5)  + $this->morphMod + $this->traitMod + $this->factionMod + $this->backgroundMod + $this->softgearMod + $this->gearMod + $this->psyMod)* $multi);
                break;
            case EPStat::$DAMAGEBONUS:
                return round((round($this->cc->getAptitudeByAbbreviation(EPAptitude::$SOMATICS)->getValue() / 10)  + $this->morphMod + $this->traitMod + $this->factionMod + $this->backgroundMod + $this->softgearMod + $this->gearMod + $this->psyMod) * $multi);
                break;
            case EPStat::$SPEED:
                $res = $this->value + $this->morphMod + $this->traitMod + $this->factionMod + $this->backgroundMod + $this->softgearMod + $this->gearMod + $this->psyMod;
                if (!empty($this->cc)){
                    $res = min($res,$this->cc->configValues->getValue('RulesValues','SpeedMaxValue'));
                }
                return round($res * $multi);
                break;
            default:
                return 0;
                break;
        }
    }
    
}
