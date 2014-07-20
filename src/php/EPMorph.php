<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EPMorph
 *
 * @author reinhardt
 */
//require_once 'EPGear.php';
require_once 'EPAtom.php';

class EPMorph extends EPAtom {
   
    static $BIOMORPH = 'biomorph';
    static $SYNTHMORPH = 'synthmorph';
    static $PODMORPH = 'podmorph';
    static $INFOMORPH = 'infomorph';
    
    static $GENDER_MALE = 'M';
    static $GENDER_FEMAL = 'F';
    static $GENDER_NONE = 'N';
        
    public $morphType;
    public $age;
    public $gender;
    
    public $maxApptitude;
    
    public $durability;
    public $nickname;
    public $location;
   
    public $cpCost;
    public $buyInCreationMode;
       
    //array
    public $traits;
    public $additionalTraits;
    public $gears;
    public $additionalGears;
    public $bonusMalus; 
    
    public $implantReject;
    
    function getSavePack(){
	$savePack = parent::getSavePack();
	    
        $savePack['morphType'] = $this->morphType;
        $savePack['age'] = $this->age;
        $savePack['gender'] = $this->gender;
        $savePack['maxApptitude'] = $this->maxApptitude;
        $savePack['durability'] = $this->durability;
        $savePack['nickname'] = $this->nickname;
        $savePack['location'] = $this->location;
        $savePack['cpCost'] = $this->cpCost;
        $savePack{'buyInCreationMode'} = $this->buyInCreationMode;
        
        $traitsSavePacks = array();
        foreach($this->traits as $m){
            array_push($traitsSavePacks	, $m->getSavePack());
        }
        $savePack['traitsSavePacks'] = $traitsSavePacks;
        $additionalTraitsSavePacks = array();
        foreach($this->additionalTraits as $m){
            array_push($additionalTraitsSavePacks, $m->getSavePack());
        }
        $savePack['additionalTraitsSavePacks'] = $additionalTraitsSavePacks;            
        $gearsSavePacks = array();
        foreach($this->gears as $m){
            array_push($gearsSavePacks	, $m->getSavePack());
        }
        $savePack['gearSavePacks'] = $gearsSavePacks;
        $addGearsSavePacks = array();
        foreach($this->additionalGears as $m){
            array_push($addGearsSavePacks	, $m->getSavePack());
        }
        $savePack['addGearSavePacks'] = $addGearsSavePacks;
        $bmSavePacks = array();
        foreach($this->bonusMalus as $m){
            array_push($bmSavePacks	, $m->getSavePack());
        }
        $savePack['bmSavePacks'] = $bmSavePacks;
        return $savePack;	    
    }   
    function loadSavePack($savePack,$cc = null){
	parent::loadSavePack($savePack);    
	    
        $this->name = $savePack['name'];
        $this->description = $savePack['description'];
        $this->morphType = $savePack['morphType']; 
        $this->age = $savePack['age'];
        $this->gender = $savePack['gender'];
        $this->maxApptitude = $savePack['maxApptitude'];
        $this->durability = $savePack['durability'];
        $this->nickname = $savePack['nickname'];
        $this->location = $savePack['location'];
        $this->cpCost = $savePack['cpCost'];
        $this->buyInCreationMode = $savePack['buyInCreationMode'];
        
        foreach($savePack['traitsSavePacks'] as $m){
            $savedTrait = new EPTrait('','','','','');
            $savedTrait->loadSavePack($m);
            array_push($this->traits, $savedTrait);
        }
        foreach($savePack['additionalTraitsSavePacks'] as $m){
            $savedTrait = new EPTrait('','','','','');
            $savedTrait->loadSavePack($m);
            array_push($this->additionalTraits, $savedTrait);
        }
        foreach($savePack['gearSavePacks'] as $m){
            $savedGear = new EPGear('','','','');
            $savedGear->loadSavePack($m);
            array_push($this->gears, $savedGear);
        }
        foreach($savePack['addGearSavePacks'] as $m){
            $savedGear = new EPGear('','','','');
            $savedGear->loadSavePack($m);
            array_push($this->additionalGears, $savedGear);
        }
        foreach($savePack['bmSavePacks'] as $m){
            $savedBm = new EPBonusMalus('','','');
            $savedBm->loadSavePack($m);
            array_push($this->bonusMalus, $savedBm);
        }
    }    
    function __construct( $atName,$morphType,$age, $gender,$maxApptitude,$durability,$cpCost,$traits=  array(),$gears=  array(),$bonusMalus=  array(), $atDesc= '',$nickname = '', $location = '',$creditCost=0) {
        parent::__construct(EPAtom::$MORPH, $atName, $atDesc);
        $this->morphType = $morphType;
        $this->age = $age;
        $this->gender = $gender;
        $this->maxApptitude = $maxApptitude;
        $this->durability = $durability;
        $this->cpCost = $cpCost;
        $this->traits = $traits;
        $this->additionalTraits = array();
        $this->gears = $gears;
        $this->additionalGears = array();
        $this->bonusMalus = $bonusMalus;
        $this->nickname = $nickname;
        $this->location = $location;
        $this->cost = $creditCost;
        $this->buyInCreationMode = true;
        $this->implantReject = false;
    }
    function addGear($gear){
        if (isset($gear) && $gear->type == EPAtom::$GEAR){
            array_push($this->gears, $gear);
            return true;
        } 
    }
    function addAdditionalGear($gear){
        if (isset($gear) && $gear->type == EPAtom::$GEAR){
            array_push($this->additionalGears, $gear);
            return true;
        } 
    }
}

?>
