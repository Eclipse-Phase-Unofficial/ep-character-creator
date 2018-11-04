<?php
declare(strict_types=1);

namespace App\Creator\Atoms;

/**
 * @author reinhardt
 */
class EPTrait extends EPAtom
{
    CONST PSY_CHI_TRAIT_NAME   = "Psi I";
    CONST PSY_GAMMA_TRAIT_NAME = "Psi II";

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

    /**
     * @var EPBonusMalus[]
     */
    public $bonusMalus;
    
    
    function getSavePack(): array
    {
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
        parent::__construct($atName, $atDesc);
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
     * @param EPTrait $trait
     * @return bool
     */
    public function match($trait): bool
    {
        if (strcasecmp($trait->getName(),$this->getName()) == 0 &&
            $trait->traitPosNeg===$this->traitPosNeg &&
            $trait->traitEgoMorph===$this->traitEgoMorph &&
            $trait->cpCost===$this->cpCost &&
            $trait->level===$this->level &&
            $trait->canUse===$this->canUse){
                return true;
        }
        return false;
    }

    /**
     * Standard getter to save some comparison operators
     * @return bool
     */
    function isPositive(): bool
    {
        return $this->traitPosNeg == EPTrait::$POSITIVE_TRAIT;
    }

    /**
     * Standard getter to save some comparison operators
     * @return bool
     */
    function isNegative(): bool
    {
        return $this->traitPosNeg == EPTrait::$NEGATIVE_TRAIT;
    }

    /**
     * Standard getter to save some comparison operators
     * @return bool
     */
    function isEgo(): bool
    {
        return $this->traitEgoMorph == EPTrait::$EGO_TRAIT;
    }

    /**
     * Standard getter to save some comparison operators
     * @return bool
     */
    function isMorph(): bool
    {
        return $this->traitEgoMorph == EPTrait::$MORPH_TRAIT;
    }

    /**
     * If this trait allows the use of Psy Chi (Psy I) abilities
     * @return bool
     */
    public function isPsyTrait(): bool
    {
        if ($this->getName() === EPTrait::PSY_CHI_TRAIT_NAME || $this->getName() === EPTrait::PSY_GAMMA_TRAIT_NAME) {
            return true;
        }
        return false;
    }

    /**
     * If this trait allows the use of Psy Gamma (Psy II) abilities
     * @return bool
     */
    public function isPsy2Trait(): bool
    {
        if ($this->getName() === EPTrait::PSY_GAMMA_TRAIT_NAME) {
            return true;
        }
        return false;
    }

    /**
     * Get all positive traits from an array
     * @param EPTrait[] $traitsArray
     * @return array
     */
    static function getPositiveTraits(array $traitsArray): array
    {
        $result = array();
        foreach ($traitsArray as $t) {
            if ($t->isPositive()) {
                array_push($result, $t);
            }
        }
        return $result;
    }

    /**
     * Get all negative traits from an array
     * @param EPTrait[] $traitsArray
     * @return array
     */
    static function getNegativeTraits(array $traitsArray): array
    {
        $result = array();
        foreach ($traitsArray as $t) {
            if ($t->isNegative()) {
                array_push($result, $t);
            }
        }
        return $result;
    }
}
