<?php
declare(strict_types=1);

namespace App\Creator\Atoms;

/**
 * Psy Sleights used in the game
 *
 * @author jigé
 */
class EPPsySleight extends EPAtom{
    
    static $ACTIVE_PSY = 'ACT';
    static $PASSIVE_PSY = 'PAS';
    
    static $LEVEL_CHI_PSY = 'CHI';
    static $LEVEL_GAMMA_PSY = 'GAM';
    
    static $RANGE_SELF = 'SELF';
    static $RANGE_TOUCH = 'TOUCH';
    static $RANGE_CLOSE = 'CLOSE';
    static $RANGE_PSY = 'PSY';
    
    static $DURATION_CONSTANT = 'constant';
    static $DURATION_INSTANT = 'instant';
    static $DURATION_TEMPORARY = 'temporary';
    static $DURATION_SUSTAINED = 'sustained';
    
    static $ACTION_AUTOMATIC = 'automatic' ;
    static $ACTION_QUICK = 'quick' ;
    static $ACTION_TASK = 'task' ;
    static $ACTION_COMPLEX = 'complex' ; 
   
    public $psyType;
    public $range;
    public $duration;
    public $action;
    public $strainMod;
    public $isActif;
    public $psyLevel;
    public $skillNeeded;
    public $buyInCreationMode;

    /**
     * @var EPBonusMalus[]
     */
    public  $bonusMalus;
    /**
     * @var bool
     */
    public $buyinCreationMode;


    function getSavePack(): array
    {
        $savePack = parent::getSavePack();
	    	    
        $savePack['psyType'] = $this->psyType;
        $savePack['range'] = $this->range;
        $savePack['duration'] = $this->duration;
        $savePack['action'] = $this->action;
        $savePack['strainMod'] = $this->strainMod;
        $savePack['isActif'] = $this->isActif;
        $savePack['psyLevel'] = $this->psyLevel;
        $savePack['skillNeeded'] = $this->skillNeeded;
        $savePack['buyInCreationMode'] = $this->buyInCreationMode;
        $bmSavePacks = array();
        foreach($this->bonusMalus as $m){
            array_push($bmSavePacks	, $m->getSavePack());
        }
        $savePack['bmSavePacks'] = $bmSavePacks;
        return $savePack;
    }
    
    function loadSavePack($savePack,$cc = null){
	parent::loadSavePack($savePack);
	    
        $this->psyType = $savePack['psyType'];
        $this->range = $savePack['range'];
        $this->duration = $savePack['duration'];
        $this->action = $savePack['action'];
        $this->strainMod = $savePack['strainMod'];
        $this->isActif = $savePack['isActif'];
        $this->psyLevel = $savePack['psyLevel'];
        $this->skillNeeded = $savePack['skillNeeded'];
        $this->buyInCreationMode = $savePack['buyInCreationMode'];
        foreach($savePack['bmSavePacks'] as $m){
            $savedBm = new EPBonusMalus('','','');
            $savedBm->loadSavePack($m);
            array_push($this->bonusMalus, $savedBm);
        }
    }
    
    function __construct($atName, $atDesc, $psyType, $range, $duration, $action, $strainMod,$psyLevel, $bonusMalusArray = array(), $skillNeeded="none") {
        parent::__construct(EPAtom::$PSY, $atName, $atDesc);
        $this->psyType = $psyType;
        $this->range = $range;
        $this->duration = $duration;
        $this->bonusMalus = $bonusMalusArray;
        $this->action = $action;
        $this->strainMod = $strainMod;
        $this->isActif = false;
        $this->psyLevel = $psyLevel;
        $this->skillNeeded = $skillNeeded;
        $this->buyInCreationMode = true;        
    }

    /**
     * Match identical Psy Sleights, even if atom Uids differ
     *
     * Psy is unique by name, psyType, and psyLevel.
     * This is more expensive than EPAtom's version, but catches duplicate Psy with different Uids.
     * @param EPPsySleight $atom
     * @return bool
     */
    public function match($atom): bool
    {
        if (strcasecmp($atom->name, $this->name) == 0 &&
            $atom->psyType === $this->psyType &&
            $atom->psyLevel === $this->psyLevel) {
            return true;
        }
        return false;
    }
}
