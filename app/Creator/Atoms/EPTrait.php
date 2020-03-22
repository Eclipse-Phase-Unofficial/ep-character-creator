<?php
declare(strict_types=1);

namespace App\Creator\Atoms;

/**
 * Traits are a primary way that players customize their characters.
 * They can provide both numeric and non-numeric advantages.
 * Not all traits are good.  Players can take negative traits either at the GM's requirement or to gain additional CP.
 * In addition, there are Neutral, 0 Cost traits.
 * Within this system, BonusMaluses are used to describe all effects.  So, traits are BonusMalus containers.
 *
 * @author reinhardt
 */
class EPTrait extends EPAtom
{
    CONST PSY_CHI_TRAIT_NAME   = "Psi I";
    CONST PSY_GAMMA_TRAIT_NAME = "Psi II";

    //GUI use for filtering the lists
    static $CAN_USE_EVERYBODY = 'EVERY';
    static $CAN_USE_BIO = 'BIO';
    static $CAN_USE_SYNTH = 'SYNTH';
    static $CAN_USE_POD = 'POD';
    /*
     * An enum value of [$CAN_USE_EVERYBODY, $CAN_USE_BIO, $CAN_USE_SYNTH, $CAN_USE_POD]
     * @var string
     */
    public $canUse;

    /*
     * Determines if this is for an Ego, Morph or both.
     * Traits for both are null!
     * @var bool|null
     */
    private $isForMorph;
    /*
     * How much the trait is worth.
     * Also stores negative traits as negative, and neutral traits as 0 internally.
     * @var int
     */
    private $cpCost;

    /**
     * TODO: Convert this to a private variable with a getter
     * @var int
     */
    public $level;

    /**
     * @var EPBonusMalus[]
     */
    public $bonusMalus;


    function getSavePack(): array
    {
        $savePack = parent::getSavePack();

        $savePack['canUse'] = $this->canUse;

        $savePack['isForMorph'] =  $this->isForMorph;
        $savePack['cpCost'] =  $this->cpCost;

        $savePack['level'] =  $this->level;

        $bmSavePacks = array();
        foreach($this->bonusMalus as $m){
            array_push($bmSavePacks	, $m->getSavePack());
        }
        $savePack['bmSavePacks'] = $bmSavePacks;

        //Included for backwards compatibility
        $savePack['mandatory'] = null;

        return $savePack;
    }

    /**
     * @param array $an_array
     * @return EPTrait
     */
    public static function __set_state(array $an_array)
    {
        //Backwards compatibility with older (pre 1.53) save files
        if(isset($an_array['traitEgoMorph'])) {
            $isForMorph = ($an_array['traitEgoMorph'] == 'MOR');
        } else {
            $isForMorph = $an_array['isForMorph'];
        }

        $object = new self((string)$an_array['name'], $isForMorph, 0);
        parent::set_state_helper($object, $an_array);

        $object->canUse = (string)$an_array['canUse'];
        $object->cpCost = (int)$an_array['cpCost'];
        $object->level  = (int)$an_array['level'];
        foreach ($an_array['bmSavePacks'] as $m) {
            array_push($object->bonusMalus, EPBonusMalus::__set_state($m));
        }

        //Backwards compatibility with older (pre 1.53) save files
        if (isset($an_array['traitPosNeg'])) {
            $isNegative = filter_var($an_array['traitPosNeg'], FILTER_VALIDATE_BOOLEAN);
            if ($isNegative) {
                $object->cpCost = -$object->cpCost;
            }
        }

        return $object;
    }

    /**
     * EPTrait constructor.
     * @param string         $name
     * @param string         $description
     * @param bool|null      $isForMorph     Traits can be for Egos, Morphs, or both.  Both is represented by null.
     * @param int            $cpCost
     * @param EPBonusMalus[] $bonusMalusArray
     * @param int            $level
     * @param string         $canUse          An enum value of [$CAN_USE_EVERYBODY, $CAN_USE_BIO, $CAN_USE_SYNTH, $CAN_USE_POD]
     */
    function __construct(
        string $name,
        ?bool $isForMorph,
        int $cpCost,
        string $description = '',
        array $bonusMalusArray = array(),
        int $level = 1,
        string $canUse = 'EVERY'
    ) {
        parent::__construct($name, $description);
        $this->isForMorph = $isForMorph;
        $this->cpCost     = $cpCost;
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
            $trait->isForMorph===$this->isForMorph &&
            $trait->cpCost===$this->cpCost &&
            $trait->level===$this->level &&
            $trait->canUse===$this->canUse){
                return true;
        }
        return false;
    }

    /**
     * The trait costs CP, and is "Good"
     * @return bool
     */
    function isPositive(): bool
    {
        return $this->cpCost > 0;
    }

    /**
     * The trait gives CP, and is "Bad"
     * @return bool
     */
    function isNegative(): bool
    {
        return $this->cpCost < 0;
    }

    /**
     * The trait does not give or cost CP and is "Neutral"
     * @return bool
     */
    function isNeutral(): bool
    {
        return $this->cpCost == 0;
    }

    /**
     * If this can be applied to an Ego
     * @return bool
     */
    function isEgo(): bool
    {
        if (is_null($this->isForMorph)) {
            return true;
        }
        return !$this->isForMorph;
    }

    /**
     * If this can be applied to a Morph
     * @return bool
     */
    function isMorph(): bool
    {
        if (is_null($this->isForMorph)) {
            return true;
        }
        return (bool)$this->isForMorph;
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
     * Get how much the trait costs or adds.  This is always a positive number!
     * @return int
     */
    public function getCpCost(): int
    {
        if ($this->isNegative()) {
            return -$this->cpCost;
        }
        return $this->cpCost;
    }

    /**
     * Get all positive traits from an array
     * @param EPTrait[] $traitsArray
     * @return EPTrait[]
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
     * @return EPTrait[]
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
