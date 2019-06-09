<?php
declare(strict_types=1);

namespace App\Creator\Objects;

use App\Creator\Atoms\EPMorph;
use App\Creator\Savable;

/**
 * The character's general information.
 *
 * @author reinhardt
 */
class EPCharacter implements Savable
{
    /**
     * @var EPEgo
     */
    public $ego;
    /**
     * @var EPMorph|null
     */
    public $currentMorph;

    /**
     * @var EPMorph[]
     */
    public $morphs;
    
    //character sheet details
    public $playerName;
    public $charName;
    public $realAge;
    public $birthGender;
    public $note;
    
    
    function getSavePack(): array
    {
        $savePack = array();

        $savePack['playerName'] = $this->playerName;
        $savePack['charName'] = $this->charName;
        $savePack['realAge'] = $this->realAge;
        $savePack['birthGender'] = $this->birthGender;
        $savePack['note'] = $this->note;
        $morphSavePack = array();
        foreach($this->morphs as $m){
            array_push($morphSavePack, $m->getSavePack());
        }
        $savePack['morphSavePacks'] =  $morphSavePack;
        $savePack['egoSavePack'] = $this->ego->getSavePack();
        $savePack['currentMorphUid'] = ''; //For backwards compatibility

        return $savePack;
    }

    /**
     * @param array $an_array
     * @return EPCharacter
     */
    public static function __set_state(array $an_array)
    {
        $object = new self();

        $object->playerName  = $an_array['playerName'];
        $object->charName    = $an_array['charName'];
        $object->realAge     = $an_array['realAge'];
        $object->birthGender = $an_array['birthGender'];
        $object->note        = $an_array['note'];
        $morphSavePack       = $an_array['morphSavePacks'];
        if (!empty($morphSavePack)) {
            foreach ($morphSavePack as $m) {
                array_push($object->morphs, EPMorph::__set_state($m));
            }
        }
        $object->ego = EPEgo::__set_state($an_array['egoSavePack']);

        return $object;
    }

    function __construct() {
        $this->ego = new EPEgo();
        $this->morphs = array();
        $this->currentMorph = null;
        $this->playerName = '';
        $this->charName = '';
        $this->realAge = '';
        $this->birthGender = '';
        $this->note = '';
    }    
}
