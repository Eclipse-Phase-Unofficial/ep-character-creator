<?php
declare(strict_types=1);

namespace App\Creator\Atoms;

/**
 * The Character's Background and Faction
 *
 * @author reinhardt
 */
class EPBackground extends EPAtom{

    static $ORIGIN = 'ORI';
    static $FACTION = 'FAC';

    /**
     * An Enum of [$ORIGIN, $FACTION]
     * TODO:  Change this to a bool (including in the database)
     * @var string
     */
    public $backgroundType;

    //TODO:  Be more specific with these types
    /**
     * @var EPBonusMalus[]
     */
    public $bonusMalus;
    /**
     * @var EPTrait[]
     */
    public $traits;
    /**
     * @var array
     */
    public $limitations;
    
    function getSavePack(): array
    {
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
	    
	return $savePack;
    }

    /**
     * @param array $an_array
     * @return EPBackground
     */
    public static function __set_state(array $an_array)
    {
        $object = new self((string)$an_array['name'], '');
        parent::set_state_helper($object, $an_array);

        $object->backgroundType = (string)$an_array['backgroundType'];
        foreach ($an_array['bmSavePacks'] as $m) {
            array_push($object->bonusMalus, EPBonusMalus::__set_state($m));
        }
        foreach ($an_array['traitSavePacks'] as $m) {
            array_push($object->traits, EPTrait::__set_state($m));
        }
        foreach ($an_array['limitationsArray'] as $m) {
            array_push($object->limitations, $m);
        }

        return $object;
    }

    /**
     * EPBackground constructor.
     * @param string         $name
     * @param string         $backgroundType Either of these two: [$ORIGIN, $FACTION]
     * @param EPBonusMalus[] $bonusMalus
     * @param EPTrait[]      $traits
     * @param array          $limitations
     * @param string         $description
     */
    function __construct(
        string $name,
        string $backgroundType,
        array $bonusMalus = array(),
        array $traits = array(),
        array $limitations = array(),
        string $description = ''
    ) {
        parent::__construct($name, $description);
        $this->backgroundType = $backgroundType;
        $this->bonusMalus = $bonusMalus;
        $this->traits = $traits;
        $this->limitations = $limitations;
    }
}
