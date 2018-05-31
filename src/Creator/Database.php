<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 5/31/18
 * Time: 1:59 AM
 */

namespace App\Creator;

use App\Creator\Atoms\EPAtom;
use EclipsePhaseCharacterCreator\Backend\EPConfigFile;
use EclipsePhaseCharacterCreator\Backend\EPListProvider;

class Database
{
    /**
     * @var string[]
     */
    private $prefixes;
    /**
     * @var Atoms\EPBackground[]
     */
    private $backgrounds;
    /**
     * @var Atoms\EPMorph[]
     */
    private $morphs;
    /**
     * @var Atoms\EPAi[]
     */
    private $ais;
    /**
     * @var Atoms\EPGear[]
     */
    private $gears;
    /**
     * @var Atoms\EPTrait[]
     */
    private $traits;
    /**
     * @var Atoms\EPPsySleight[]
     */
    private $psySleights;
    /**
     * @var Atoms\EPReputation[]
     */
    private $reputations;
    /**
     * @var Atoms\EPAptitude[]
     */
    private $aptitudes;
    /**
     * @var Atoms\EPStat[]
     */
    private $stats;
    /**
     * @var Atoms\EPSkill[]
     */
    private $skills;

    /**
     * Obtain all objects from the database in one fell swoop.
     *
     * Everything has a Unique Id that will change between sessions!
     */
    public function __construct()
    {
        $config   = new EPConfigFile(getConfigLocation());
        $provider = new EPListProvider(getConfigLocation());
        $provider->connect();

        // To be removed from EPCharacterCreator
        $this->prefixes    = $provider->getListPrefix();
        $this->backgrounds = $provider->getListBackgrounds();
        $this->morphs      = $provider->getListMorph();
        $this->ais         = $provider->getListAi();
        $this->gears       = $provider->getListGears();
        $this->traits      = $provider->getListTraits();
        $this->psySleights = $provider->getListPsySleights();

        //To be removed from EPEgo
        $this->reputations = $provider->getListReputation();
        $this->aptitudes   = $provider->getListAptitudes($config->getValue('RulesValues', 'AptitudesMinValue'),
            $config->getValue('RulesValues', 'AptitudesMaxValue'));
        $this->stats       = $provider->getListStats($config);  //TODO:  This does not handle the creator here.
        $this->skills      = $provider->getListSkills($this->aptitudes);
        usort($this->skills, [Atoms\EPSkill::class, 'compareSkillsByPrefixName']);
    }

    /**
     * @return string[]
     */
    public function getPrefixes(): array
    {
        return $this->prefixes;
    }

    /**
     * @return Atoms\EPBackground[]
     */
    public function getBackgrounds(): array
    {
        return $this->backgrounds;
    }

    /**
     * @return Atoms\EPMorph[]
     */
    public function getMorphs(): array
    {
        return $this->morphs;
    }

    /**
     * @return Atoms\EPAi[]
     */
    public function getAis(): array
    {
        return $this->ais;
    }

    /**
     * @return Atoms\EPGear[]
     */
    public function getGears(): array
    {
        return $this->gears;
    }

    /**
     * @return Atoms\EPTrait[]
     */
    public function getTraits(): array
    {
        return $this->traits;
    }

    /**
     * @return Atoms\EPPsySleight[]
     */
    public function getPsySleights(): array
    {
        return $this->psySleights;
    }

    /**
     * @return Atoms\EPReputation[]
     */
    public function getReputations(): array
    {
        return $this->reputations;
    }

    /**
     * @return Atoms\EPAptitude[]
     */
    public function getAptitudes(): array
    {
        return $this->aptitudes;
    }

    /**
     * @return Atoms\EPStat[]
     */
    public function getStats(): array
    {
        return $this->stats;
    }

    /**
     * @return Atoms\EPSkill[]
     */
    public function getSkills(): array
    {
        return $this->skills;
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /// get{Something}ByName TODO:  Replace these with getById
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    function getBackgroundByName($name): Atoms\EPBackground
    {
        return EPAtom::getAtomByName($this->backgrounds, $name);
    }

    function getStatByName($name): Atoms\EPStat
    {
        return EPAtom::getAtomByName($this->stats, $name);
    }

    /**
     * FIXME:  Dangerous (Skills should always be referenced by name AND prefix)
     *
     * @param Atoms\EPAi $ai
     * @param string     $name
     * @return Atoms\EPSkill
     */
    function getAiSkillByName(Atoms\EPAi $ai, string $name): Atoms\EPSkill
    {
        return EPAtom::getAtomByName($ai->skills, $name);
    }

    function getMorphByName(string $name): Atoms\EPMorph
    {
        return EPAtom::getAtomByName($this->morphs, $name);
    }

    function getPsySleightsByName(string $name): Atoms\EPPsySleight
    {
        return EPAtom::getAtomByName($this->psySleights, $name);
    }

    function getAiByName(string $name): Atoms\EPAi
    {
        return EPAtom::getAtomByName($this->ais, $name);
    }

    function getGearByName($name): Atoms\EPGear
    {
        return EPAtom::getAtomByName($this->gears, $name);
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /// get{}ByAtomUid TODO:  Replace these with getById
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    function getSkillByAtomUid(string $id): Atoms\EPSkill
    {
        return EPAtom::getAtomByUid($this->skills, $id);
    }


    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /// Random selectors
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    function getStatByAbbreviation($abbr): Atoms\EPStat
    {
        foreach ($this->stats as $s) {
            if (strcmp($s->abbreviation, $abbr) == 0) {
                return $s;
            }
        }
        return null;
    }

    /**
     * @param string $prefix
     * @return Atoms\EPSkill[]
     */
    function getSkillsByPrefix(string $prefix): array
    {
        $output = array();
        foreach ($this->skills as $skill) {
            if (strcmp($skill->prefix, $prefix) == 0) {
                array_push($output, $skill);
            }
        }
        return $output;
    }


    /**
     * TODO:  Double check that this is really unused and remove it
     * @param string $group
     * @return Atoms\EPSkill[]
     */
    function getSkillsByGroup(string $group): array
    {
        $output = array();
        foreach ($this->skills as $skill) {
            foreach ($skill->groups as $skillGroup) {
                if (strcmp($skillGroup, $group) == 0) {
                    array_push($output, $skill);
                }
            }
        }
        return $output;
    }

    function prefixExists(string $prefix): bool
    {
        foreach ($this->prefixes as $aPrefix) {
            if ($aPrefix == $prefix) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param string $morphName
     * @return Atoms\EPGear[]
     */
    function getGearForMorphName(string $morphName): array
    {
        $morph = $this->getMorphByName($morphName);
        return $morph->getGear();
    }
}