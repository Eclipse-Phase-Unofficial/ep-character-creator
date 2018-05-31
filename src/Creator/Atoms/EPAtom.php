<?php
declare(strict_types=1);

namespace App\Creator\Atoms;

use EclipsePhaseCharacterCreator\Backend\Savable;

/**
 * EPAtom is the generic object class of the character creator, almost everything is subclassed from it.
 *
 * EPAtom provides several key features:
 *   * Save/Load functionality that can be expanded by subclasses
 *   * A Unique Id that's guaranteed to safe for use in HTML 'id' tags
 *        Do NOT attempt to sanitize this ID.  Doing so will merely break things when attempting to compare the sanitized version to the unsanitized one!
 *
 * @author reinhardt
 * @author EmperorArthur
 */
class EPAtom implements Savable
{

    static $APTITUDE = 'aptitude';
    static $BACKGROUND = 'background';
    static $FACTION = 'faction';
    static $GEAR = 'gear';
    static $WEAPON ='weapon';
    static $ARMOR = 'armor';
    static $MOTIVATION = 'motivation';
    static $REPUTATION = 'reputation';
    static $SKILL = 'skill';
    static $STAT = 'stat';
    static $TRAIT = 'trait';
    static $BONUSMALUS = 'bonusmalus';
    static $MORPH = 'morph';
    static $AI = 'ai';
    static $PSY = 'psy';

    /**
     * @var string
     */
    private $atomUid;
    public  $type;

    public $occurence;
    public $unique;
    public $name;
    public $description;
    public $groups;

    /**
     * @var int
     */
    public $cost;
    public $ratioCost;
    public $ratioCostMorphMod;
    public $ratioCostTraitMod;
    public $ratioCostBackgroundMod;
    public $ratioCostFactionMod;
    public $ratioCostSoftgearMod;
    public $ratioCostPsyMod;


    function __construct(string $atType, string $atName, string $atDesc) {
       $this->atomUid = uniqid('Atom_'.$this->sanitize($atName).'_');
       $this->type = $atType;
       $this->name = $atName;
       $this->description = $atDesc;
       $this->groups = array();
       $this->cost = 0;
       $this->ratioCost = 1;
       $this->ratioCostMorphMod = 1;
       $this->ratioCostTraitMod = 1;
       $this->ratioCostBackgroundMod = 1;
       $this->ratioCostFactionMod = 1;
       $this->ratioCostSoftgearMod = 1;
       $this->ratioCostPsyMod = 1;
       $this->occurence = 1;
       $this->unique = true;
    }
     function getSavePack(): array
     {
	    $savePack = array();

        $savePack['atomUid'] = $this->atomUid;
	    $savePack['type'] = $this->type;
	    $savePack['name'] = $this->name;
	    $savePack['description'] = $this->description;
	    $savePack['groups'] = $this->groups;
	    $savePack['cost'] = $this->cost;
        $savePack['ratioCost'] = $this->ratioCost;
	    $savePack['ratioCostMorphMod'] = $this->ratioCostMorphMod;
	    $savePack['ratioCostTraitMod'] = $this->ratioCostTraitMod;
	    $savePack['ratioCostBackgroundMod'] = $this->ratioCostBackgroundMod;
	    $savePack['ratioCostFactionMod'] = $this->ratioCostFactionMod;
	    $savePack['ratioCostSoftgearMod'] = $this->ratioCostSoftgearMod;
	    $savePack['ratioCostPsyMod'] = $this->ratioCostPsyMod;
        $savePack['occurence'] = $this->occurence;
        $savePack['unique'] = $this->unique;

	    return $savePack;
    }

    function loadSavePack($savePack,$cc = null){
        $this->atomUid = $savePack['atomUid'];
	    $this->type = $savePack['type'];
	    $this->name = $savePack['name'];
	    $this->description = $savePack['description'];
	    $this->groups = $savePack['groups'];
	    $this->cost = $savePack['cost'];
        $this->ratioCost = $savePack['ratioCost'];
	    $this->ratioCostMorphMod = $savePack['ratioCostMorphMod'];
	    $this->ratioCostTraitMod = $savePack['ratioCostTraitMod'];
	    $this->ratioCostBackgroundMod = $savePack['ratioCostBackgroundMod'];
	    $this->ratioCostFactionMod = $savePack['ratioCostFactionMod'];
	    $this->ratioCostSoftgearMod = $savePack['ratioCostSoftgearMod'];
	    $this->ratioCostPsyMod = $savePack['ratioCostPsyMod'];
        $this->occurence = $savePack['occurence'];
        $this->unique = $savePack['unique'];
    }

    /**
     * Ensure a clone object has a different atomUid from the original
     */
    function __clone()
    {
        $this->atomUid = uniqid('Atom_' . $this->sanitize($this->name) . '_');
    }

    /**
     * Get the final cost of the object.
     *
     * @return int
     */
    public function getCost(): int
    {
        return (int)round($this->cost * $this->ratioCost * $this->ratioCostMorphMod * $this->ratioCostTraitMod * $this->ratioCostBackgroundMod * $this->ratioCostFactionMod * $this->ratioCostSoftgearMod * $this->ratioCostPsyMod);
    }

    public function getUid(): string
    {
        return $this->atomUid;
    }

    //Strip any character that could cause an issue in an id tag

    /**
     * @param string $input
     * @return string
     */
    private function sanitize(string $input): string
    {
        $badChars = '/[^A-Z,^a-z,^0-9]/';
        return preg_replace($badChars, '_', $input);
    }

    /**
     * Check if this and another atom are identical
     *
     * It does this via checking if Uids are the same or different.
     * TODO:  Add ids to the database so this is no longer needed
     * @param EPAtom $atom
     * @return bool
     */
    function match(EPAtom $atom): bool
    {
        if (strcmp($atom->getUid(), $this->atomUid) == 0) {
            return true;
        }
        return false;
    }

    /**
     * Check if this Atom is in an array
     * @param EPAtom[] $atoms
     * @return bool
     */
    public function isInArray(array $atoms): bool
    {
        foreach ($atoms as $anAtom) {
            if ($this->match($anAtom)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Add this Atom to an array
     *
     * @param EPAtom[] $atoms
     * @return bool true if successful, false if this atom was already in the array
     */
    public function addToArray(array &$atoms): bool
    {
        if (!$this->isInArray($atoms)) {
            array_push($atoms, $this);
            return true;
        }
        return false;
    }

    /**
     * Remove this Atom from an array
     *
     * @param EPAtom[] $atoms
     * @return bool Success or failure
     */
    public function removeFromArray(array &$atoms): bool
    {
        if ($this->isInArray($atoms)) {
            $index = 0;
            foreach ($atoms as $item) {
                if ($this->match($item)) {
                    break;
                } else {
                    $index++;
                }
            }
            array_splice($atoms, $index, 1);
            return true;
        }
        error_log("Not in array!");
        return false;
    }


    /**
     * Find an Atom with a particular name (potentially dangerous, do not use for skills)
     * @param EPAtom[] $atoms
     * @param string   $name
     * @return EPAtom|null|mixed
     */
    public static function getAtomByName(array $atoms, string $name)
    {
        if (empty($atoms)) {
            return null;
        }
        foreach ($atoms as $anAtom) {
            if (strcmp($anAtom->name, $name) == 0) {
                return $anAtom;
            }
        }
    }

    /**
     * Find an atom by unique id (safe)
     * @param EPAtom[] $atoms
     * @param string   $id
     * @return EPAtom|null|mixed
     */
    public static function getAtomByUid(array $atoms, string $id)
    {
        if (empty($atoms)) {
            return null;
        }
        foreach ($atoms as $anAtom) {
            if (strcmp($anAtom->getUid(), $id) == 0) {
                return $anAtom;
            }
        }
    }

    /**
     * TODO:  Make this non static
     *
     * @param EPAtom          $atom
     * @param string|string[] $groups
     * @return bool
     */
    public static function isInGroups(EPAtom $atom, $groups): bool
    {
        if (!empty($atom->groups)) {
            foreach ($atom->groups as $atomGroups) {
                if (is_array($groups)) {
                    foreach ($groups as $aGroup) {
                        if (strcmp($atomGroups, $aGroup) == 0) {
                            return true;
                        }
                    }
                } else {
                    if (strcmp($atomGroups, $groups) == 0) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    // Get all the members of a group from an array
    // May be expensive (not optimized at all)
    /**
     * @param array $atoms
     * @param mixed $groups
     * @return array
     */
    public static function getGroupMembers(array $atoms, $groups): array
    {
        $output = array();
        foreach ($atoms as $anAtom) {
            if (static::isInGroups($anAtom, $groups)) {
                $anAtom->addToArray($output);
            }
        }
        return $output;
    }
}
