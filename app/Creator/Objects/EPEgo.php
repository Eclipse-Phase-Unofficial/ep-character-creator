<?php
declare(strict_types=1);

namespace App\Creator\Objects;

use App\Creator\Atoms\EPAi;
use App\Creator\Atoms\EPAptitude;
use App\Creator\Atoms\EPAtom;
use App\Creator\Atoms\EPBackground;
use App\Creator\Atoms\EPGear;
use App\Creator\Atoms\EPPsySleight;
use App\Creator\Atoms\EPReputation;
use App\Creator\Atoms\EPSkill;
use App\Creator\Atoms\EPStat;
use App\Creator\Atoms\EPTrait;
use App\Creator\Savable;

/**
 * The Character's Ego.
 *
 * Everything that's intinsic to the character.
 * This is everything that.
 *
 * @author reinhardt
 */
class EPEgo implements Savable
{

     //values
    public $name;
    public $creditInstant;
    public $credit;
    public $creditMorphMod;
    public $creditTraitMod;
    public $creditFactionMod;
    public $creditBackgroundMod;
    public $creditSoftGearMod;
    public $creditPsyMod;
    public $creditPurchased;

    /**
     * @var EPBackground
     */
    public $faction;
    /**
     * @var EPBackground
     */
    public $background;

    /**
     * @var string[]
     */
    public $motivations;
    /**
     * @var EPAptitude[]
     */
    public $aptitudes;
    /**
     * @var EPSkill[]
     */
    public $skills;
    /**
     * @var EPReputation[]
     */
    public $reputations;
    /**
     * @var EPStat[]
     */
    public $stats;
    /**
	 * All the traits granted by faction and background (not user modifiable)
     * @var EPTrait[]
     */
    public $traits;
    /**
	 * All the traits the user has added
     * @var EPTrait[]
     */
    public $additionalTraits;
    /**
     * @var EPGear[]
     */
    public $softGears;
    /**
     * @var EPAi[]
     */
    public $ais;
    /**
     * @var EPAi[]
     */
    public $defaultAis;
    /**
     * @var EPPsySleight[]
     */
    public $psySleights;

    function getSavePack(): array
    {
	    $savePack = array();

	    $savePack['name'] = $this->name;
	    $savePack['creditInstant'] = $this->creditInstant;
	    $savePack['credit'] = $this->credit;
	    $savePack['creditMorphMod'] = $this->creditMorphMod;
	    $savePack['creditTraitMod'] = $this->creditTraitMod;
	    $savePack['creditFactionMod'] = $this->creditFactionMod;
	    $savePack['creditBackgroundMod'] = $this->creditBackgroundMod;
	    $savePack['creditSoftGearMod'] = $this->creditSoftGearMod;
	    $savePack['creditPsyMod'] = $this->creditPsyMod;
	    $savePack['creditPurchased'] = $this->creditPurchased;
	    if(isset($this->faction)){
	    	$savePack['factionSavePack'] = $this->faction->getSavePack();
	    }
	    else{
		$savePack['factionSavePack'] = null;
	    }
	    if(isset($this->background)){
		$savePack['backgroundSavePack'] = $this->background->getSavePack();
            }
            else{
		$savePack['backgroundSavePack'] = null;
            }
	    $motivationArray = array();
	    foreach($this->motivations as $m){
	    	array_push($motivationArray, $m);
	    }
	    $savePack['motivationArray'] = $motivationArray;
	    $aptitudesSavePacks = array();
	    foreach($this->aptitudes as $m){
	    	array_push($aptitudesSavePacks	, $m->getSavePack());
	    }
	    $savePack['aptitudesSavePacks'] = $aptitudesSavePacks;
	    $skillsSavesPacks = array();
	    foreach($this->skills as $m){
	    	array_push($skillsSavesPacks, $m->getSavePack());
	    }
	    $savePack['skillsSavePacks'] = $skillsSavesPacks;

	    $reputationsSavePacks = array();
	    foreach($this->reputations as $m){
	    	array_push($reputationsSavePacks, $m->getSavePack());
	    }
	    $savePack['reputationSavePack'] = $reputationsSavePacks;
	    $statsSavePacks = array();
	    foreach($this->stats as $m){
	    	array_push($statsSavePacks, $m->getSavePack());
	    }
	    $savePack['statsSavePacks'] = $statsSavePacks;

	    $traitsSavePacks = array();
	    foreach($this->traits as $m){
	    	array_push($traitsSavePacks, $m->getSavePack());
	    }
	    $savePack['traitSavePacks'] = $traitsSavePacks;

	    $additionaTraitsSavePacks = array();
	    foreach($this->additionalTraits as $m){
	    	array_push($additionaTraitsSavePacks, $m->getSavePack());
	    }
	    $savePack['additionaTraitsSavePacks'] = $additionaTraitsSavePacks;
	    $softGearSavePacks = array();
	    foreach($this->softGears as $m){
	    	array_push($softGearSavePacks, $m->getSavePack());
	    }
	    $savePack['softGearSavePacks'] = $softGearSavePacks;
	    $aiSavePacks = array();
	    foreach($this->ais as $m){
	    	array_push($aiSavePacks, $m->getSavePack());
	    }
	    $savePack['aiSavePacks'] = $aiSavePacks;
	    $defAiSavePacks = array();
	    foreach($this->defaultAis as $m){
	    	array_push($defAiSavePacks, $m->getSavePack());
	    }
	    $savePack['defaultAisSavePacks'] = $defAiSavePacks;

	    $psySleightSavePacks = array();
	    foreach($this->psySleights as $m){
	    	array_push($psySleightSavePacks, $m->getSavePack());
	    }
	    $savePack['psySleightSavePacks'] = $psySleightSavePacks;

	    return $savePack;
    }

    /**
     * @param array $an_array
     * @return EPEgo
     */
    public static function __set_state(array $an_array)
    {
        $object = new self();

        $object->name                = $an_array['name'];
        $object->creditInstant       = $an_array['creditInstant'];
        $object->credit              = $an_array['credit'];
        $object->creditMorphMod      = $an_array['creditMorphMod'];
        $object->creditTraitMod      = $an_array['creditTraitMod'];
        $object->creditFactionMod    = $an_array['creditFactionMod'];
        $object->creditBackgroundMod = $an_array['creditBackgroundMod'];
        $object->creditSoftGearMod   = $an_array['creditSoftGearMod'];
        $object->creditPsyMod        = $an_array['creditPsyMod'];
        $object->creditPurchased     = $an_array['creditPurchased'];

        if ($an_array['factionSavePack'] != null) {
            $object->faction = EPBackground::__set_state($an_array['factionSavePack']);
        }
        if ($an_array['backgroundSavePack'] != null) {
            $object->background = EPBackground::__set_state($an_array['backgroundSavePack']);
        }
        foreach ($an_array['motivationArray'] as $m) {
            array_push($object->motivations, $m);
        }
        foreach ($an_array['aptitudesSavePacks'] as $m) {
            array_push($object->aptitudes, EPAptitude::__set_state($m));
        }
        foreach ($an_array['skillsSavePacks'] as $m) {
            array_push($object->skills, EPSkill::__set_state($m));
        }
        foreach ($an_array['reputationSavePack'] as $m) {
            array_push($object->reputations, EPReputation::__set_state($m));
        }
        foreach ($an_array['statsSavePacks'] as $m) {
            array_push($object->stats, EPStat::__set_state($m));
        }
        foreach ($an_array['traitSavePacks'] as $m) {
            array_push($object->traits, EPTrait::__set_state($m));
        }
        foreach ($an_array['additionaTraitsSavePacks'] as $m) {
            array_push($object->additionalTraits, EPTrait::__set_state($m));
        }
        foreach ($an_array['softGearSavePacks'] as $m) {
            array_push($object->softGears, EPGear::__set_state($m));
        }
        foreach ($an_array['aiSavePacks'] as $m) {
            array_push($object->ais, EPAi::__set_state($m));
        }
        foreach ($an_array['defaultAisSavePacks'] as $m) {
            array_push($object->defaultAis, EPAi::__set_state($m));
        }
        foreach ($an_array['psySleightSavePacks'] as $m) {
            array_push($object->psySleights, EPPsySleight::__set_state($m));
        }

        /*******last details*******/
        //Skills have pointers to their linked aptitudes.
        //Except they are not stored in the save pack
        //This means we need to re-associate each skill with the actual aptitude so modifications automatically take place
        foreach($object->skills as $m){
            //For normal skills, it's as simple as getting the Skill from the database
            $dbSkill = EpDatabase()->getSkillByNamePrefix($m->getName(),$m->getPrefixName());
            if($dbSkill != null){
                $linkedApt = $dbSkill->linkedAptitude;
            } else {
                //For user created skills (which don't exist in the database), link them based on their prefix
                $linkedApt = EpDatabase()->getAptitudeForPrefix($m->getPrefixName());
            }
            //Link skills to the aptitude imported, not the one in the database
            $m->linkedAptitude = EPAtom::getAtomLike($object->aptitudes, $linkedApt);
        }

        return $object;
    }

    function __construct() {
        $this->aptitudes = array();
        $this->skills = array();
        $this->motivations = array();
        $this->reputations = array();
        $this->stats = array();
        $this->traits = array();
        $this->additionalTraits = array();
        $this->softGears = array();
        $this->ais = array();
        $this->defaultAis = array();
        $this->psySleights = array();
        $this->credit = 0;
        $this->creditMorphMod = 0;
        $this->creditTraitMod = 0;
        $this->creditFactionMod = 0;
        $this->creditBackgroundMod = 0;
        $this->creditSoftGearMod = 0;
        $this->creditPsyMod = 0;
        $this->creditPurchased = 0;
        $this->creditInstant = 0;
    }
    function addDefaultAi(EPAi $defaultAi){
        if (isset($defaultAi)){
            array_push($this->defaultAis,$defaultAi);
        }
    }

    /**
     * All the traits, both user added, and from background/faction
     * @return EPTrait[]
     */
    function getTraits(): array
    {
        return array_merge($this->traits,$this->additionalTraits);
    }

    /**
     * Get all the knowledge skills
     * @return EPSkill[]
     */
    function getKnowledgeSkills(): array
    {
        $output = array();
        foreach ($this->skills as $skill) {
            if ($skill->isKnowledge()) {
                array_push($output, $skill);
            }
        }

        usort($output, [EPSkill::class, 'compareSkillsByPrefixName']);
        return $output;
    }

    /**
     * Get all the active skills
     * @return EPSkill[]
     */
    function getActiveSkills(): array
    {
        $output = array();
        foreach ($this->skills as $skill) {
            if ($skill->isActive()) {
                array_push($output, $skill);
            }
        }

        usort($output, [EPSkill::class, 'compareSkillsByPrefixName']);
        return $output;
    }

    /**
     * If the Ego can use Psy I Traits
     * @return bool
     */
    function canUsePsyTraits(){
        foreach($this->getTraits() as $t){
            if($t->isPsyTrait()) {
                return true;
            }
        }
        return false;
    }

    /**
     * If the Ego can use Psy II Traits
     * @return bool
     */
    function canUsePsy2Traits(){
        foreach($this->getTraits() as $t){
            if($t->isPsy2Trait()) {
                return true;
            }
        }
        return false;
    }
}
