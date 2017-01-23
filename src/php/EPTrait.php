<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EPTrait
 *
 * @author reinhardt
 */
class EPTrait extends EPAtom{
    
    static $POSITIVE_TRAIT = 'POS';
    static $NEGATIVE_TRAIT = 'NEG';
    
    static $EGO_TRAIT = 'EGO';
    static $MORPH_TRAIT = 'MOR';
    
    //GUI use for filtering the listes
    static $CAN_USE_EVERYBODY = 'EVERY';
    static $CAN_USE_BIO = 'BIO';
    static $CAN_USE_SYNTH = 'SYNTH';
    static $CAN_USE_POD = 'POD';
    //-----
    public $canUse;
    public $mandatory;
    //-----
    
    public $traitPosNeg;
    public $traitEgoMorph;
    public $cpCost;
    
    public $level;
    
    //Array 
    public $bonusMalus;
    
    
    function getSavePack(){
        $savePack = parent::getSavePack();
        
        $savePack['canUse'] = $this->canUse;
        $savePack['mandatory'] = $this->mandatory;

        $savePack['traitPosNeg'] =  $this->traitPosNeg;
        $savePack['traitEgoMorph'] =  $this->traitEgoMorph;
        $savePack['cpCost'] =  $this->cpCost;

        $savePack['level'] =  $this->level;
        
        $bmSavePacks = array();
        foreach($this->bonusMalus as $m){
            array_push($bmSavePacks	, $m->getSavePack());
        }
        $savePack['bmSavePacks'] = $bmSavePacks;
        return $savePack;
    }
    function loadSavePack($savePack,$cc = null){
        parent::loadSavePack($savePack);

        $this->canUse = $savePack['canUse'];
        $this->mandatory = $savePack['mandatory'];
        $this->traitPosNeg = $savePack['traitPosNeg'];
        $this->traitEgoMorph = $savePack['traitEgoMorph'];
        $this->cpCost = $savePack['cpCost'];	    
        $this->level = $savePack['level'];
        foreach($savePack['bmSavePacks'] as $m){
            $savedBm = new EPBonusMalus('','','');
            $savedBm->loadSavePack($m);
            array_push($this->bonusMalus, $savedBm);
        }	    
    }   
    function __construct($atName, $atDesc, $traitPosNeg, $traitEgoMorph, $cpCost , $bonusMalusArray = array(),$level = 1,$canUse='EVERY') {
        parent::__construct(EPAtom::$TRAIT, $atName, $atDesc);
        $this->traitPosNeg = $traitPosNeg;
        $this->traitEgoMorph = $traitEgoMorph;
        $this->cpCost = $cpCost;
        $this->bonusMalus = $bonusMalusArray;
        $this->level = $level;
        $this->canUse = $canUse;
    }

    /**
     * Match identical traits, even if atom Uids differ
     *
     * Check if *all* trait values match.
     * This is more expensive than EPAtom's version, but catches duplicate traits with different Uids.
     */
    public function match($trait){
        if (strcasecmp($trait->name,$this->name) == 0 &&
            $trait->traitPosNeg===$this->traitPosNeg &&
            $trait->traitEgoMorph===$this->traitEgoMorph &&
            $trait->cpCost===$this->cpCost &&
            $trait->level===$this->level &&
            $trait->canUse===$this->canUse){
                return true;
        }
        return false;
    }

    //Standard getters to save some comparison operators
    function isPositive(){
        return $this->traitPosNeg == EPTrait::$POSITIVE_TRAIT;
    }
    function isNegative(){
        return $this->traitPosNeg == EPTrait::$NEGATIVE_TRAIT;
    }
    function isEgo(){
        return $this->traitEgoMorph == EPTrait::$EGO_TRAIT;
    }
    function isMorph(){
        return $this->traitEgoMorph == EPTrait::$MORPH_TRAIT;
    }
}

//**********HELPER FUNCTIONS**********//

/**
 * Get all positive traits from an array
 */
function getPosTraits($array){
    $result = array();
    foreach($array as $t)
    {
        if($t->isPositive())
            array_push($result, $t);
    }
    return $result;
}

/**
 * Get all negative traits from an array
 */
function getNegTraits($array){
    $result = array();
    foreach($array as $t)
    {
        if($t->isNegative())
            array_push($result, $t);
    }
    return $result;
}

?>
