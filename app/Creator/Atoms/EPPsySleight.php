<?php
declare(strict_types=1);

namespace App\Creator\Atoms;

/**
 * Psy Sleights used in the game
 *
 * @author jigé
 */
class EPPsySleight extends EPAtom{

    static $ACTIVE_PSY = 'ACT';
    static $PASSIVE_PSY = 'PAS';

    static $LEVEL_CHI_PSY = 'CHI';
    static $LEVEL_GAMMA_PSY = 'GAM';

    static $RANGE_SELF = 'SELF';
    static $RANGE_TOUCH = 'TOUCH';
    static $RANGE_CLOSE = 'CLOSE';
    static $RANGE_PSY = 'PSY';

    static $DURATION_CONSTANT = 'constant';
    static $DURATION_INSTANT = 'instant';
    static $DURATION_TEMPORARY = 'temporary';
    static $DURATION_SUSTAINED = 'sustained';

    static $ACTION_AUTOMATIC = 'automatic' ;
    static $ACTION_QUICK = 'quick' ;
    static $ACTION_TASK = 'task' ;
    static $ACTION_COMPLEX = 'complex' ;

    /**
     * TODO:  Convert this to an 'isActiveType' bool
     * An Enum value of [$ACTIVE_PSY, $PASSIVE_PSY]
     * @var string
     */
    public $psyType;
    /**
     * An Enum of the $RANGE_... static/const values
     * @var string
     */
    public $range;
    /**
     * An Enum of the $DURATION_... static/const values
     * @var string
     */
    public $duration;
    /**
     * An Enum of the $ACTION_... static/const values
     * @var string
     */
    public $action;
    public $strainMod;
    /**
     * TODO:  Rename this to 'isActive'
     * @var bool
     */
    public $isActif;
    /**
     * An Enum of [$LEVEL_CHI_PSY, $LEVEL_GAMMA_PSY]
     * TODO:  Convert this to a bool of 'isPsyGamma'
     * @var string
     */
    public $psyLevel;
    /**
     * Never actually used for anything
     * @var string|null
     */
    private $skillNeeded;
    /**
     * If the Sleight was purchased in creation mode
     * Used so Sleights can be discarded later on, but without giving rez points
     * TODO:  Rename to $purchasedInCreationMode
     * @var bool
     */
    public $buyInCreationMode;

    /**
     * @var EPBonusMalus[]
     */
    public  $bonusMalus;


    function getSavePack(): array
    {
        $savePack = parent::getSavePack();

        $savePack['psyType'] = $this->psyType;
        $savePack['range'] = $this->range;
        $savePack['duration'] = $this->duration;
        $savePack['action'] = $this->action;
        $savePack['strainMod'] = $this->strainMod;
        $savePack['isActif'] = $this->isActif;
        $savePack['psyLevel'] = $this->psyLevel;
        $savePack['skillNeeded'] = $this->skillNeeded;
        $savePack['buyInCreationMode'] = $this->buyInCreationMode;
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
        $object = new self((string)$an_array['name'], '', '', '', '', '', '', '');
        parent::set_state_helper($object, $an_array);

        $object->psyType           = (string)$an_array['psyType'];
        $object->range             = (string)$an_array['range'];
        $object->duration          = (string)$an_array['duration'];
        $object->action            = (string)$an_array['action'];
        $object->strainMod         = (string)$an_array['strainMod'];
        $object->isActif           = (bool)$an_array['isActif'];
        $object->psyLevel          = (string)$an_array['psyLevel'];
        $object->skillNeeded       = (string)$an_array['skillNeeded'];
        $object->buyInCreationMode = (string)$an_array['buyInCreationMode'];
        foreach ($an_array['bmSavePacks'] as $m) {
            array_push($object->bonusMalus, EPBonusMalus::__set_state($m));
        }

        return $object;
    }

    /**
     * EPPsySleight constructor.
     * @param string         $name
     * @param string         $description
     * @param string         $psyType
     * @param string         $range
     * @param string         $duration
     * @param string         $action
     * @param string         $strainMod
     * @param string         $psyLevel
     * @param EPBonusMalus[] $bonusMalusArray
     * @param string         $skillNeeded
     */
    function __construct(
        string $name,
        string $description,
        string $psyType,
        string $range,
        string $duration,
        string $action,
        string $strainMod,
        string $psyLevel,
        array $bonusMalusArray = array(),
        ?string $skillNeeded = null
    ) {
        parent::__construct($name, $description);
        $this->psyType = $psyType;
        $this->range = $range;
        $this->duration = $duration;
        $this->bonusMalus = $bonusMalusArray;
        $this->action = $action;
        $this->strainMod = $strainMod;
        $this->isActif = false;
        $this->psyLevel = $psyLevel;
        $this->skillNeeded = $skillNeeded;
        $this->buyInCreationMode = true;
    }

    /**
     * Match identical Psy Sleights, even if atom Uids differ
     *
     * Psy is unique by name, psyType, and psyLevel.
     * This is more expensive than EPAtom's version, but catches duplicate Psy with different Uids.
     * @param EPPsySleight $atom
     * @return bool
     */
    public function match($atom): bool
    {
        if (strcasecmp($atom->getName(), $this->getName()) == 0 &&
            $atom->psyType === $this->psyType &&
            $atom->psyLevel === $this->psyLevel) {
            return true;
        }
        return false;
    }
}
