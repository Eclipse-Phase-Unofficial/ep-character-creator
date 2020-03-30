<?php
declare(strict_types=1);

namespace App\Creator\Atoms;

use App\Models\PsySleight;
use App\Models\Skill;

/**
 * Psy Sleights used in the game
 *
 * @author jigé
 * @author Arthur Moore
 */
class EPPsySleight extends EPAtom{

    static $ACTION_AUTOMATIC = 'automatic' ;
    static $ACTION_QUICK = 'quick' ;
    static $ACTION_TASK = 'task' ;
    static $ACTION_COMPLEX = 'complex' ;

    /**
     * @var PsySleight
     */
    protected $model;

    /**
     * If the Sleight was purchased in creation mode
     * Used so Sleights can be discarded later on, but without giving rez points
     * TODO:  This whole thing should be handled differently!
     * @var bool
     */
    public $purchasedInCreationMode;

    /**
     * @var EPBonusMalus[]
     */
    public  $bonusMalus;


    function getSavePack(): array
    {
        $savePack = parent::getSavePack();

        $savePack['buyInCreationMode'] = $this->purchasedInCreationMode;
        $bmSavePacks = array();
        foreach($this->bonusMalus as $m){
            array_push($bmSavePacks	, $m->getSavePack());
        }
        $savePack['bmSavePacks'] = $bmSavePacks;
        return $savePack;
    }

    /**
     * @param array $an_array
     * @return EPPsySleight
     */
    public static function __set_state(array $an_array)
    {
        $object = new self(PsySleight::whereName((string)$an_array['name']));
        parent::set_state_helper($object, $an_array);

        $object->purchasedInCreationMode = (string)$an_array['buyInCreationMode'];
        foreach ($an_array['bmSavePacks'] as $m) {
            array_push($object->bonusMalus, EPBonusMalus::__set_state($m));
        }

        return $object;
    }

    /**
     * EPPsySleight constructor.
     * @param PsySleight $model
     */
    function __construct(PsySleight $model) {
        parent::__construct("Unused", "");
        $this->model = $model;
        $this->purchasedInCreationMode = true;

        $this->bonusMalus = array();
        foreach($this->model->bonusMalus as $bonusMalus) {
            $this->bonusMalus [] = new EPBonusMalus($bonusMalus);
        }
    }

    /**
     * @return bool
     */
    public function isPsyGamma(): bool
    {
        return $this->model->isPsyGamma;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->model->isActive;
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
     * Match identical Psy Sleights, even if atom Uids differ
     *
     * This is more expensive than EPAtom's version, but catches duplicate Psy with different Uids.
     * @param EPPsySleight $sleight
     * @return bool
     */
    public function match($sleight): bool
    {
        return $this->model->getKey() === $sleight->model->getKey();
    }

    /**
     * This is the Skill which is used for tests for the Sleight.
     * TODO:  Make sure this is used, or at least mentioned somewhere on the final sheet.
     * @return Skill|null
     */
    public function getAssociatedSkill(): ?Skill
    {
        return $this->model->skill;
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->model->action;
    }
}
