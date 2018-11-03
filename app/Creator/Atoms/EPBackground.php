<?php
declare(strict_types=1);

namespace App\Creator\Atoms;

/**
 * The Character's Background and Faction
 *
 * @author reinhardt
 */
class EPBackground extends EPAtom{

    static $ORIGIN = 'ORI';
    static $FACTION = 'FAC';
    
    public $backgroundType;

    //TODO:  Be more specific with these types
    /**
     * @var EPBonusMalus[]
     */
    public $bonusMalus;
    /**
     * @var EPTrait[]
     */
    public $traits;
    /**
     * @var array
     */
    public $limitations;
    
    function getSavePack(): array
    {
        $savePack = parent::getSavePack();
	        
        $savePack['backgroundType'] = $this->backgroundType;  
        $bmSavePacks = array();
        foreach($this->bonusMalus as $m){
            array_push($bmSavePacks	, $m->getSavePack());
        }
        $savePack['bmSavePacks'] = $bmSavePacks;
        $traitsSavePacks = array();
        foreach($this->traits as $m){
            array_push($traitsSavePacks, $m->getSavePack());
        }
        $savePack['traitSavePacks'] = $traitsSavePacks;
        $limitationsArray = array();
        foreach($this->limitations as $m){
            array_push($limitationsArray, $m);
        } 
        $savePack['limitationsArray'] = $limitationsArray;
	    
	return $savePack;
    }
    function loadSavePack($savePack,$cc = null){
        parent::loadSavePack($savePack);
	    	    
        $this->backgroundType = $savePack['backgroundType'];
        foreach($savePack['bmSavePacks'] as $m){
            $savedBm = new EPBonusMalus('','','');
            $savedBm->loadSavePack($m);
            array_push($this->bonusMalus, $savedBm);
        }
        foreach($savePack['traitSavePacks'] as $m){
            $savedTrait = new EPTrait('','','','','');
            $savedTrait->loadSavePack($m);
            array_push($this->traits, $savedTrait);
        }
        foreach($savePack['limitationsArray'] as $m){
            array_push($this->limitations, $m);
        }
    }
    function __construct($atName,$atDesc,$backgroundType,$bonusMalus = array(),$traits = array(), $limitations = array()) {
        parent::__construct(EPAtom::$BACKGROUND, $atName, $atDesc);
        $this->backgroundType = $backgroundType;
        $this->bonusMalus = $bonusMalus;
        $this->traits = $traits;
        $this->limitations = $limitations;
    }
}
