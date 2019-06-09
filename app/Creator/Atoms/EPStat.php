<?php
declare(strict_types=1);

namespace App\Creator\Atoms;

use App\Creator\EPCharacterCreator;

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

    /**
     * An enum of all the static/const values
     * @var string
     */
    public $abbreviation;
    /**
     * @var int
     */
    public $value;

    /**
     * //TODO:  This potentially introduces cyclic dependencies, and should be removed
     * @var null|\App\Creator\EPCharacterCreator
     */
    public $cc;

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
    public $factionMod;
    /**
     * @var int
     */
    public $backgroundMod;
    /**
     * @var int
     */
    public $softgearMod;
    /**
     * @var int
     */
    public $gearMod;
    /**
     * @var int
     */
    public $psyMod;

    /**
     * @var int
     */
    public $multiMorphMod;
    /**
     * @var int
     */
    public $multiTraitMod;
    /**
     * @var int
     */
    public $multiFactionMod;
    /**
     * @var int
     */
    public $multiBackgroundMod;
    /**
     * @var int
     */
    public $multiSoftgearMod;
    /**
     * @var int
     */
    public $multiGearMod;
    /**
     * @var int
     */
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

    function loadSavePack($savePack)
    {
        parent::loadSavePack($savePack);

        $this->abbreviation = (string)$savePack['abbreviation'];
        $this->value        = (int)$savePack['value'];

        $this->morphMod      = (int)$savePack['morphMod'];
        $this->traitMod      = (int)$savePack['traitMod'];
        $this->factionMod    = (int)$savePack['factionMod'];
        $this->backgroundMod = (int)$savePack['backgroundMod'];
        $this->softgearMod   = (int)$savePack['softgearMod'];
        $this->gearMod       = (int)$savePack['gearMod'];
        $this->psyMod        = (int)$savePack['psyMod'];

        $this->multiMorphMod      = (int)$savePack['multiMorphMod'];
        $this->multiTraitMod      = (int)$savePack['multiTraitMod'];
        $this->multiFactionMod    = (int)$savePack['multiFactionMod'];
        $this->multiBackgroundMod = (int)$savePack['multiBackgroundMod'];
        $this->multiSoftgearMod   = (int)$savePack['multiSoftgearMod'];
        $this->multiGearMod       = (int)$savePack['multiGearMod'];
        $this->multiPsyMod        = (int)$savePack['multiPsyMod'];
    }

    /**
     * EPStat constructor.
     * @param string                  $name
     * @param string                  $description
     * @param string                  $abbreviation
     * @param string[]                $groups
     * @param EPCharacterCreator|null $characterCreator
     */
    function __construct(
        string $name,
        string $description,
        string $abbreviation,
        array $groups = array(),
        ?EPCharacterCreator &$characterCreator = null
    ) {
        parent::__construct($name, $description);
        $this->abbreviation = $abbreviation;
        $this->value = 0;
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
        $morph = $this->cc->getCurrentMorph();
        $multi = $this->multiMorphMod * $this->multiTraitMod * $this->multiFactionMod * $this->multiBackgroundMod * $this->multiSoftgearMod * $this->multiGearMod * $this->multiPsyMod;
        switch ($this->abbreviation) {
            case EPStat::$MOXIE:
                return round(($this->value + $this->morphMod + $this->traitMod + $this->factionMod + $this->backgroundMod + $this->softgearMod + $this->gearMod + $this->psyMod) * $multi) ;
                break;
            case EPStat::$LUCIDITY:
                return round(($this->cc->getAptitudeByAbbreviation(EPAptitude::$WILLPOWER)->getValue() * 2  + $this->morphMod + $this->traitMod + $this->factionMod + $this->backgroundMod + $this->softgearMod + $this->gearMod + $this->psyMod) * $multi);
                break;
            case EPStat::$TRAUMATHRESHOLD:
                $stat = $this->cc->getStatByAbbreviation(EPStat::$LUCIDITY)->getValue();
                return round((round($stat / 5)  + $this->morphMod + $this->traitMod + $this->factionMod + $this->backgroundMod + $this->softgearMod + $this->gearMod + $this->psyMod) * $multi);
                break;
            case EPStat::$INSANITYRATING:
                $stat = $this->cc->getStatByAbbreviation(EPStat::$LUCIDITY)->getValue();
                return round(($stat * 2  + $this->morphMod + $this->traitMod + $this->factionMod + $this->backgroundMod + $this->softgearMod + $this->gearMod + $this->psyMod) * $multi);
                break;
            case EPStat::$DURABILITY:
                if (isset($morph)){
                    $res =  $morph->durability;
                }else{
                    $res = 0;
                }
                return round(($res  + $this->morphMod + $this->traitMod + $this->factionMod + $this->backgroundMod + $this->softgearMod + $this->gearMod + $this->psyMod) * $multi);
                break;
            case EPStat::$DEATHRATING:
                $stat = $this->cc->getStatByAbbreviation(EPStat::$DURABILITY)->getValue();
                if (isset($morph)){
                    if ($morph->morphType != EPMorph::$SYNTHMORPH){
                        $res = round($stat * 1.5);
                    }else{
                        $res = round($stat * 2);
                    }
                }else{
                    return 0;
                }
                return round(($res  + $this->morphMod + $this->traitMod + $this->factionMod + $this->backgroundMod + $this->softgearMod + $this->gearMod + $this->psyMod) * $multi);
                break;
            case EPStat::$WOUNDTHRESHOLD:
                $stat = $this->cc->getStatByAbbreviation(EPStat::$DURABILITY)->getValue();
                return round((round($stat / 5) + $this->morphMod + $this->traitMod + $this->factionMod + $this->backgroundMod + $this->softgearMod + $this->gearMod + $this->psyMod) * $multi);
                break;
            case EPStat::$INITIATIVE:
                $stat = $this->cc->getAptitudeByAbbreviation(EPAptitude::$INTUITION)->getValue();
                return round((round(($stat + $this->cc->getAptitudeByAbbreviation(EPAptitude::$REFLEXS)->getValue()) / 5)  + $this->morphMod + $this->traitMod + $this->factionMod + $this->backgroundMod + $this->softgearMod + $this->gearMod + $this->psyMod)* $multi);
                break;
            case EPStat::$DAMAGEBONUS:
                $stat = $this->cc->getAptitudeByAbbreviation(EPAptitude::$SOMATICS)->getValue();
                return round((round($stat / 10)  + $this->morphMod + $this->traitMod + $this->factionMod + $this->backgroundMod + $this->softgearMod + $this->gearMod + $this->psyMod) * $multi);
                break;
            case EPStat::$SPEED:
                $res = $this->value + $this->morphMod + $this->traitMod + $this->factionMod + $this->backgroundMod + $this->softgearMod + $this->gearMod + $this->psyMod;
                $res = min($res, config('epcc.SpeedMaxValue'));
                return round($res * $multi);
                break;
            default:
                return 0;
                break;
        }
    }
    
}
