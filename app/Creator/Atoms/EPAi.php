<?php
declare(strict_types=1);

namespace App\Creator\Atoms;

/**
 * AIs and Muses the player can use.
 *
 * These can be treated as sub characters in their own right.
 *
 * @author reinhardt
 */
class EPAi extends EPAtom{
    /**
     * @var EPAptitude[]
     */
    public $aptitudes;
    /**
     * @var EPSkill[]
     */
    public $skills;
    /**
     * @var EPBonusMalus[]
     */
    public $bonusMalus;

    function getSavePack(): array
    {
        $savePack = parent::getSavePack();

        $aptitudesSavePacks = array();
        foreach($this->aptitudes as $m){
            array_push($aptitudesSavePacks,$m->getSavePack());
        }
        $savePack['aptitudesSavePacks'] = $aptitudesSavePacks;
        $skillsSavesPacks = array();
        foreach($this->skills as $m){
            array_push($skillsSavesPacks, $m->getSavePack());
        }
        $savePack['skillsSavePacks'] = $skillsSavesPacks;
        $bmSavePacks = array();
        foreach($this->bonusMalus as $m){
            array_push($bmSavePacks	, $m->getSavePack());
        }
        $savePack['bmSavePacks'] = $bmSavePacks;

        return $savePack;
    }  
    function loadSavePack($savePack,$cc = null){
        parent::loadSavePack($savePack);

        foreach($savePack['aptitudesSavePacks'] as $m){
            $savedAptitude = new EPAptitude('','');
            $savedAptitude->loadSavePack($m);
            array_push($this->aptitudes, $savedAptitude);
        }	    
        foreach($savePack['skillsSavePacks'] as $m){
            $savedSkill = new EPSkill('','','','','');
            $savedSkill->loadSavePack($m);
            array_push($this->skills, $savedSkill);
        }
        foreach($savePack['bmSavePacks'] as $m){
            $savedBm = new EPBonusMalus('','','');
            $savedBm->loadSavePack($m);
            array_push($this->bonusMalus, $savedBm);
        }    
    }

    function __construct($atName, $aptitudes, $costType, $skills = array(), $atDesc = '')
    {
        parent::__construct(EPAtom::$AI, $atName, $atDesc);
        $this->aptitudes = $aptitudes;
        $this->skills = $skills;
        $this->cost = $costType;
        $this->bonusMalus = array();
    }

    /**
     * Match identical AIs, even if atom Uids differ
     *
     * AIs are unique by name only.
     * This is more expensive than EPAtom's version, but catches duplicate AIs with different Uids.
     * @param EPAtom $atom
     * @return bool
     */
    public function match(EPAtom $atom): bool
    {
        if (strcasecmp($atom->getName(),$this->getName()) == 0){
            return true;
        }
        return false;
    }
}
