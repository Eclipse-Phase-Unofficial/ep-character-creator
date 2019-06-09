<?php
declare(strict_types=1);

namespace App\Creator\Atoms;

use App\Creator\Savable;

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
    /**
     * @var string
     */
    private $atomUid;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string[]
     */
    public $groups;

    /**
     * @var int
     */
    protected $cost;
    /**
     * @var float
     */
    protected $ratioCostMorphMod;
    /**
     * @var float
     */
    protected $ratioCostTraitMod;
    /**
     * @var float
     */
    protected $ratioCostBackgroundMod;
    /**
     * @var float
     */
    protected $ratioCostFactionMod;
    /**
     * @var float
     */
    protected $ratioCostSoftgearMod;
    /**
     * @var float
     */
    protected $ratioCostPsyMod;


    /**
     * EPAtom constructor.
     * @param string $name        Commonly used as a DB selector and for things it really shouldn't be, so mandatory for now.
     * @param string $description
     */
    function __construct(string $name, string $description = '') {
        if(empty($name))
        {
            throw new \InvalidArgumentException("Name may never be empty");
        }

        $this->atomUid = uniqid();
        $this->name        = $name;
        $this->description = $description;
       $this->groups = array();
       $this->cost = 0;
       $this->ratioCostMorphMod = 1;
       $this->ratioCostTraitMod = 1;
       $this->ratioCostBackgroundMod = 1;
       $this->ratioCostFactionMod = 1;
       $this->ratioCostSoftgearMod = 1;
       $this->ratioCostPsyMod = 1;
    }
     function getSavePack(): array
     {
	    $savePack = array();

        $savePack['atomUid'] = $this->atomUid;
	    $savePack['name'] = $this->getName();
	    $savePack['description'] = $this->getDescription();
	    $savePack['groups'] = $this->groups;
	    $savePack['cost'] = $this->cost;
	    $savePack['ratioCostMorphMod'] = $this->ratioCostMorphMod;
	    $savePack['ratioCostTraitMod'] = $this->ratioCostTraitMod;
	    $savePack['ratioCostBackgroundMod'] = $this->ratioCostBackgroundMod;
	    $savePack['ratioCostFactionMod'] = $this->ratioCostFactionMod;
	    $savePack['ratioCostSoftgearMod'] = $this->ratioCostSoftgearMod;
	    $savePack['ratioCostPsyMod'] = $this->ratioCostPsyMod;

	    return $savePack;
    }

    function loadSavePack($savePack,$cc = null){
        $this->atomUid = $savePack['atomUid'];
	    $this->name = $savePack['name'];
	    $this->description = $savePack['description'];
	    $this->groups = $savePack['groups'];
	    $this->cost = $savePack['cost'];
	    $this->ratioCostMorphMod = $savePack['ratioCostMorphMod'];
	    $this->ratioCostTraitMod = $savePack['ratioCostTraitMod'];
	    $this->ratioCostBackgroundMod = $savePack['ratioCostBackgroundMod'];
	    $this->ratioCostFactionMod = $savePack['ratioCostFactionMod'];
	    $this->ratioCostSoftgearMod = $savePack['ratioCostSoftgearMod'];
	    $this->ratioCostPsyMod = $savePack['ratioCostPsyMod'];

        if(empty($this->name))
        {
            throw new \InvalidArgumentException("Name may never be empty");
        }
    }

    /**
     * Ensure a clone object has a different atomUid from the original
     */
    function __clone()
    {
        $this->atomUid = uniqid();
    }

    /**
     * Get an objects name.
     *
     * May never be empty.
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get a raw HTML string describing the object.
     *
     * May be empty.
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Get the final cost of the object.
     *
     * @return int
     */
    public function getCost(): int
    {
        return (int)round($this->cost * $this->ratioCostMorphMod * $this->ratioCostTraitMod * $this->ratioCostBackgroundMod * $this->ratioCostFactionMod * $this->ratioCostSoftgearMod * $this->ratioCostPsyMod);
    }

    public function getUid(): string
    {
        return $this->atomUid;
    }

    /**
     * Reset ratioCostMorphMod to 1
     */
    public function resetRatioCostMorphMod(): void
    {
        $this->ratioCostMorphMod = 1;
    }

    /**
     * Multiply ratioCostMorphMod by a value
     * @param float $ratioCostMorphMod
     */
    public function multiplyRatioCostMorphMod(float $ratioCostMorphMod): void
    {
        $this->ratioCostMorphMod *= $ratioCostMorphMod;
    }

    /**
     * Reset ratioCostTraitMod to 1
     */
    public function resetRatioCostTraitMod(): void
    {
        $this->ratioCostTraitMod = 1;
    }

    /**
     * Multiply ratioCostTraitMod by a value
     * @param float $ratioCostTraitMod
     */
    public function multiplyRatioCostTraitMod(float $ratioCostTraitMod): void
    {
        $this->ratioCostTraitMod *= $ratioCostTraitMod;
    }

    /**
     * Reset ratioCostBackgroundMod to 1
     */
    public function resetRatioCostBackgroundMod(): void
    {
        $this->ratioCostBackgroundMod = 1;
    }

    /**
     * Multiply ratioCostBackgroundMod by a value
     * @param float $ratioCostBackgroundMod
     */
    public function multiplyRatioCostBackgroundMod(float $ratioCostBackgroundMod): void
    {
        $this->ratioCostBackgroundMod *= $ratioCostBackgroundMod;
    }

    /**
     * Reset ratioCostFactionMod to 1
     */
    public function resetRatioCostFactionMod(): void
    {
        $this->ratioCostFactionMod = 1;
    }

    /**
     * Multiply ratioCostFactionMod by a value
     * @param float $ratioCostFactionMod
     */
    public function multiplyRatioCostFactionMod(float $ratioCostFactionMod): void
    {
        $this->ratioCostFactionMod *= $ratioCostFactionMod;
    }

    /**
     * Reset ratioCostSoftgearMod to 1
     */
    public function resetRatioCostSoftgearMod(): void
    {
        $this->ratioCostSoftgearMod = 1;
    }

    /**
     * Multiply ratioCostSoftgearMod by a value
     * @param float $ratioCostSoftgearMod
     */
    public function multiplyRatioCostSoftgearMod(float $ratioCostSoftgearMod): void
    {
        $this->ratioCostSoftgearMod *= $ratioCostSoftgearMod;
    }

    /**
     * Reset ratioCostPsyMod to 1
     */
    public function resetRatioCostPsyMod(): void
    {
        $this->ratioCostPsyMod = 1;
    }

    /**
     * Multiply RatioCostPsyMod by a value
     * @param float $ratioCostPsyMod
     */
    public function multiplyRatioCostPsyMod(float $ratioCostPsyMod): void
    {
        $this->ratioCostPsyMod *= $ratioCostPsyMod;
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
            if (strcmp($anAtom->getName(), $name) == 0) {
                return $anAtom;
            }
        }
        return null;
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
        return null;
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
     * @return EPAtom[]|EPSkill[]
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
