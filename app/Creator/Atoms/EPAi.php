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

    /**
     * @param array $an_array
     * @return EPAi
     */
    public static function __set_state(array $an_array)
    {
        $object = new self((string)$an_array['name'], [], 0);
        parent::set_state_helper($object, $an_array);

        foreach ($an_array['aptitudesSavePacks'] as $m) {
            array_push($object->aptitudes, EPAptitude::__set_state($m));
        }
        foreach ($an_array['skillsSavePacks'] as $m) {
            array_push($object->skills, EPSkill::__set_state($m));
        }
        foreach ($an_array['bmSavePacks'] as $m) {
            array_push($object->bonusMalus, EPBonusMalus::__set_state($m));
        }

        return $object;
    }

    /**
     * EPAi constructor.
     * @param string       $name
     * @param EPAptitude[] $aptitudes
     * @param int          $cost
     * @param EPSkill[]    $skills
     * @param string       $description
     */
    function __construct(string $name, array $aptitudes, int $cost, $skills = array(), string $description = '')
    {
        parent::__construct($name, $description);
        $this->aptitudes = $aptitudes;
        $this->skills = $skills;
        $this->cost = $cost;
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
