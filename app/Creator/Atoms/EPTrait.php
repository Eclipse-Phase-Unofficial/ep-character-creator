<?php
declare(strict_types=1);

namespace App\Creator\Atoms;

use App\Models\Traits;

/**
 * Traits are a primary way that players customize their characters.
 * They can provide both numeric and non-numeric advantages.
 * Not all traits are good.  Players can take negative traits either at the GM's requirement or to gain additional CP.
 * In addition, there are Neutral, 0 Cost traits.
 * Within this system, BonusMaluses are used to describe all effects.  So, traits are BonusMalus containers.
 *
 * @aurthor Arthur Moore
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

    /**
     * @var Traits
     */
    protected $model;

    /**
     * @var EPBonusMalus[]
     */
    public $bonusMalus;


    function getSavePack(): array
    {
        $savePack = parent::getSavePack();

        $bmSavePacks = array();
        foreach($this->bonusMalus as $m){
            array_push($bmSavePacks	, $m->getSavePack());
        }
        $savePack['bmSavePacks'] = $bmSavePacks;

        return $savePack;
    }

    /**
     * @param array $an_array
     * @return EPTrait
     */
    public static function __set_state(array $an_array)
    {
        //Fix a Trait being mis-named in 1.53 and below!
        $an_array['name'] = str_replace("Improved Imm. Sys. Morph I", "Improved Immune System Morph I", (string)$an_array['name']);
        $an_array['name'] = str_replace("Improved Imm. Sys. Morph II", "Improved Immune System Morph II", (string)$an_array['name']);
        //TODO:  Add a warning on load that "Pain Tolerance morph I" did not have its effect (bonusMalus) added!

        $object = new self(Traits::whereName((string)$an_array['name']));
        parent::set_state_helper($object, $an_array);

        foreach ($an_array['bmSavePacks'] as $m) {
            array_push($object->bonusMalus, EPBonusMalus::__set_state($m));
        }

        return $object;
    }

    /**
     * EPTrait constructor.
     * @param Traits $model
     */
    function __construct(Traits $model) {
        parent::__construct("Unused", "");
        $this->model = $model;

        $this->bonusMalus = array();
        foreach($this->model->bonusMalus as $bonusMalus) {
            $this->bonusMalus [] = new EPBonusMalus($bonusMalus);
        }
    }

    public function getName(): string
    {
        return $this->model->name;
    }

    public function getDescription(): string
    {
        return $this->model->description;
    }

    /**
     * Match identical traits, even if atom Uids differ
     * @param EPTrait $trait
     * @return bool
     */
    public function match($trait): bool
    {
        return $this->model->getKey() === $trait->model->getKey();
    }

    /**
     * The trait costs CP, and is "Good"
     * @return bool
     */
    function isPositive(): bool
    {
        return $this->model->cpCost > 0;
    }

    /**
     * The trait gives CP, and is "Bad"
     * @return bool
     */
    function isNegative(): bool
    {
        return $this->model->cpCost < 0;
    }

    /**
     * The trait does not give or cost CP and is "Neutral"
     * @return bool
     */
    function isNeutral(): bool
    {
        return $this->model->cpCost == 0;
    }

    /**
     * If this can be applied to an Ego
     * @return bool
     */
    function isEgo(): bool
    {
        if (is_null($this->model->isForMorph)) {
            return true;
        }
        return !$this->model->isForMorph;
    }

    /**
     * If this can be applied to a Morph
     * @return bool
     */
    function isMorph(): bool
    {
        if (is_null($this->model->isForMorph)) {
            return true;
        }
        return (bool)$this->model->isForMorph;
    }

    /**
     * If this trait allows the use of Psy Chi (Psy I) abilities
     * @return bool
     */
    public function isPsyTrait(): bool
    {
        //TODO:  Deal with Lost Psy Trait!
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
        //TODO:  Deal with Lost Psy Trait!
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
            return -$this->model->cpCost;
        }
        return $this->model->cpCost;
    }

    public function getLevel(): int
    {
        return $this->model->level;
    }


    /**
     * Please note, Infomorphs can't use traits!
     *
     * @return bool
     */
    public function canUseAllMorphs(): bool
    {
        //Don't use the isEgo check because that's not the opposite.
        if (!$this->isMorph()) {
            return false;
        }
        return $this->model->JustFor === EPTrait::$CAN_USE_EVERYBODY;
    }

    /**
     * @return bool
     */
    public function canUseSynthmorph(): bool
    {
        //Don't use the isEgo check because that's not the opposite.
        if (!$this->isMorph()) {
            return false;
        }
        return ($this->model->JustFor === EPTrait::$CAN_USE_EVERYBODY) || ($this->model->JustFor === EPTrait::$CAN_USE_SYNTH);
    }

    /**
     * @return bool
     */
    public function canUseBiomorph(): bool
    {
        //Don't use the isEgo check because that's not the opposite.
        if (!$this->isMorph()) {
            return false;
        }
        return ($this->model->JustFor === EPTrait::$CAN_USE_EVERYBODY) || ($this->model->JustFor === EPTrait::$CAN_USE_BIO);
    }

    /**
     * @return bool
     */
    public function canUsePodmorph(): bool
    {
        //Don't use the isEgo check because that's not the opposite.
        if (!$this->isMorph()) {
            return false;
        }
        return ($this->model->JustFor === EPTrait::$CAN_USE_EVERYBODY) || ($this->model->JustFor === EPTrait::$CAN_USE_POD);
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
