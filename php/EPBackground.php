<?php
/**
 * Description of EPBackground / Faction
 *
 * @author reinhardt
 */
class EPBackground extends EPAtom{

    static $ORIGIN = 'ORI';
    static $FACTION = 'FAC';
    
    public $backgroundType;
    
    //array
    public $bonusMalus;
    public $traits;
    public $limitations;
    public $obligations;
    
    
    function getSavePack(){
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
        $obligationsArray = array();
        foreach($this->obligations as $m){
            array_push($obligationsArray, $m);
        } 
        $savePack['obligationsArray'] = $obligationsArray;
	    
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
        foreach($savePack['obligationsArray'] as $m){
            array_push($this->obligations, $m);
        }  
    }
    function __construct($atName,$atDesc,$backgroundType,$bonusMalus = array(),$traits = array(), $limitations = array(),$obligations = array()) {
        parent::__construct(EPAtom::$BACKGROUND, $atName, $atDesc);
        $this->backgroundType = $backgroundType;
        $this->bonusMalus = $bonusMalus;
        $this->traits = $traits;
        $this->limitations = $limitations;
        $this->obligations = $obligations;
    }
}

?>
