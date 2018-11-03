<?php
declare(strict_types=1);

namespace App\Creator\Atoms;

/**
 * The character's morph.
 *
 * This is the character's body, and contains all their (physical) stuff.
 *
 * @author reinhardt
 */
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

    /**
     * All the traits granted by default (not user modifiable)
     * @var EPTrait[]
     */
    public $traits;
    /**
     * All the traits the user has added
     * @var EPTrait[]
     */
    public $additionalTraits;
    /**
     * All the gear granted by default (not user modifiable)
     * @var EPGear[]
     */
    public $gears;
    /**
     * All the gear the user has added
     * @var EPGear[]
     */
    public $additionalGears;
    /**
     * @var EPBonusMalus[]
     */
    public $bonusMalus;

    public $implantReject;

    function getSavePack(): array
    {
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

    /**
     * Match identical morphs, even if atom Uids differ
     *
     * Check if *almost all* morph values match.
     * This is more expensive than EPAtom's version, but catches duplicate morphs with different Uids.
     * @param EPMorph $morph
     * @return bool
     */
    public function match($morph): bool
    {
        if (strcasecmp($morph->getName(),$this->getName()) == 0 &&
            $morph->morphType===$this->morphType &&
            $morph->maxApptitude===$this->maxApptitude &&
            $morph->cost===$this->cost &&
            $morph->cpCost===$this->cpCost &&
            $morph->durability===$this->durability){
                return true;
        }
        return false;
    }

    function addGear(EPGear $gear)
    {
        array_push($this->gears, $gear);
    }

    function addAdditionalGear(EPGear $gear)
    {
        array_push($this->additionalGears, $gear);
    }

    /**
     * All the traits, both user added, and from morph default
     * @return EPTrait[]
     */
    function getTraits(): array
    {
        return array_merge($this->traits, $this->additionalTraits);
    }

    /**
     * All gear, both user added, and from morph default
     * @return EPGear[]
     */
    function getGear(): array
    {
        return array_merge($this->gears, $this->additionalGears);
    }
}
