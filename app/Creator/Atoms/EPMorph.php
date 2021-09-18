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

    /**
     * An Enum of [$BIOMORPH, $SYNTHMORPH, $PODMORPH, $INFOMORPH]
     * @var string
     */
    public $morphType;
    /**
     * Apparent age of the morph
     * Only used for display purposes, so don't limit it to just numbers
     * @var string
     */
    public $age;
    /**
     * @var string
     */
    public $gender;

    /**
     * TODO:  Rename this to fix the spelling mistake
     * @var int
     */
    public $maxApptitude;

    /**
     * @var int
     */
    public $durability;
    /**
     * @var string
     */
    public $nickname;
    /**
     * @var string
     */
    public $location;

    /**
     * @var int
     */
    public $cpCost;
    /**
     * If the morph is being purchased in creation mode or not.
     * Used to determine if cost is in CP or credits
     * TODO:  Things related to this should be handled internally.  The math should be done here!
     * @var bool
     */
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
        $savePack['buyInCreationMode'] = $this->buyInCreationMode;

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

    /**
     * @param array $an_array
     * @return EPMorph
     */
    public static function __set_state(array $an_array)
    {
        $object = new self((string)$an_array['name'], '', 0, 0, 0);
        parent::set_state_helper($object, $an_array);

        $object->morphType         = (string)$an_array['morphType'];
        $object->age               = (string)$an_array['age'];
        $object->gender            = (string)$an_array['gender'];
        $object->maxApptitude      = (int)$an_array['maxApptitude'];
        $object->durability        = (int)$an_array['durability'];
        $object->nickname          = (string)$an_array['nickname'];
        $object->location          = (string)$an_array['location'];
        $object->cpCost            = (int)$an_array['cpCost'];
        $object->buyInCreationMode = (string)$an_array['buyInCreationMode'];

        foreach ($an_array['traitsSavePacks'] as $m) {
            array_push($object->traits, EPTrait::__set_state($m));
        }
        foreach ($an_array['additionalTraitsSavePacks'] as $m) {
            array_push($object->additionalTraits, EPTrait::__set_state($m));
        }
        foreach ($an_array['gearSavePacks'] as $m) {
            array_push($object->gears, EPGear::__set_state($m));
        }
        foreach ($an_array['addGearSavePacks'] as $m) {
            array_push($object->additionalGears, EPGear::__set_state($m));
        }
        foreach ($an_array['bmSavePacks'] as $m) {
            array_push($object->bonusMalus, EPBonusMalus::__set_state($m));
        }

        return $object;
    }

    /**
     * EPMorph constructor.
     * @param string         $name
     * @param string         $morphType
     * @param int            $maxAptitude
     * @param int            $durability
     * @param int            $cpCost
     * @param EPTrait[]      $traits
     * @param EPGear[]       $gears
     * @param EPBonusMalus[] $bonusMalus
     * @param string         $description
     * @param int            $creditCost
     */
    function __construct(
        string $name,
        string $morphType,
        int $maxAptitude,
        int $durability,
        int $cpCost,
        array $traits = array(),
        array $gears = array(),
        array $bonusMalus = array(),
        string $description = '',
        int $creditCost = 0
    ) {
        parent::__construct($name, $description);
        $this->morphType = $morphType;
        $this->age = '0';
        $this->gender = self::$GENDER_NONE;
        $this->maxApptitude = $maxAptitude;
        $this->durability = $durability;
        $this->cpCost = $cpCost;
        $this->traits = $traits;
        $this->additionalTraits = array();
        $this->gears = $gears;
        $this->additionalGears = array();
        $this->bonusMalus = $bonusMalus;
        $this->nickname = '';
        $this->location = '';
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
