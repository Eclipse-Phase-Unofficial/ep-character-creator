<?php
declare(strict_types=1);

namespace EclipsePhaseCharacterCreator\Backend;

use App\Creator\Atoms\EPAi;
use App\Creator\Atoms\EPAptitude;
use App\Creator\Atoms\EPBackground;
use App\Creator\Atoms\EPGear;
use App\Creator\Atoms\EPPsySleight;
use App\Creator\Atoms\EPReputation;
use App\Creator\Atoms\EPSkill;
use App\Creator\Atoms\EPStat;
use App\Creator\Atoms\EPTrait;

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

    function loadSavePack($savePack,$cc = null){
	    $this->name = $savePack['name'];
	    $this->creditInstant = $savePack['creditInstant'];
	    $this->credit = $savePack['credit'];
	    $this->creditMorphMod = $savePack['creditMorphMod'];
	    $this->creditTraitMod = $savePack['creditTraitMod'];
	    $this->creditFactionMod = $savePack['creditFactionMod'];
	    $this->creditBackgroundMod = $savePack['creditBackgroundMod'];
	    $this->creditSoftGearMod = $savePack['creditSoftGearMod'];
	    $this->creditPsyMod = $savePack['creditPsyMod'];

	    $this->creditPurchased = $savePack['creditPurchased'];

	    if($savePack['factionSavePack'] != null){
	    	$faction = new EPBackground('','','');
	    	$faction->loadSavePack($savePack['factionSavePack']);
	    	$this->faction = $faction;
	    }
	    if($savePack['backgroundSavePack'] != null){
	    	$faction = new EPBackground('','','');
	    	$faction->loadSavePack($savePack['backgroundSavePack']);
	    	$this->background = $faction;
	    }

	    foreach($savePack['motivationArray'] as $m){
	    	array_push($this->motivations, $m);
	    }

	    //must be done before skills !
	    $this->aptitudes = array();
	    foreach($savePack['aptitudesSavePacks'] as $m){
	    	$savedAptitude = new EPAptitude('','');
	    	$savedAptitude->loadSavePack($m);
	    	array_push($this->aptitudes, $savedAptitude);
	    }

        $this->skills = array();
	    foreach($savePack['skillsSavePacks'] as $m){
	    	$savedSkill = new EPSkill('','','','','');
	    	$savedSkill->loadSavePack($m);
	    	array_push($this->skills, $savedSkill);
	    }

	    $this->reputations = array();
	    foreach($savePack['reputationSavePack'] as $m){
	    	$savedRep = new EPReputation('','');
	    	$savedRep->loadSavePack($m);
	    	array_push($this->reputations, $savedRep);
	    }

        $this->stats = array();
	    foreach($savePack['statsSavePacks'] as $m){
	    	$savedStat = new EPStat('','','',0,$cc);
	    	$savedStat->loadSavePack($m);
	    	array_push($this->stats, $savedStat);
	    }

	    foreach($savePack['traitSavePacks'] as $m){
	    	$savedTrait = new EPTrait('','','','','');
	    	$savedTrait->loadSavePack($m);
	    	array_push($this->traits, $savedTrait);
	    }

	    foreach($savePack['additionaTraitsSavePacks'] as $m){
	    	$savedAddTrait = new EPTrait('','','','','');
	    	$savedAddTrait->loadSavePack($m);
	    	array_push($this->additionalTraits, $savedAddTrait);
	    }

	    foreach($savePack['softGearSavePacks'] as $m){
	    	$savedSoftG = new EPGear('','','','');
	    	$savedSoftG->loadSavePack($m);
	    	array_push($this->softGears, $savedSoftG);
	    }

	    foreach($savePack['aiSavePacks'] as $m){
	    	$savedAi = new EPAi('',array(),'');
	    	$savedAi->loadSavePack($m,$cc);
	    	array_push($this->ais, $savedAi);
	    }

        $this->defaultAis = array();
	    foreach($savePack['defaultAisSavePacks'] as $m){
	    	$defSavedAi = new EPAi('',array(),'');
	    	$defSavedAi->loadSavePack($m,$cc);
	    	array_push($this->defaultAis, $defSavedAi);
	    }

		foreach($savePack['psySleightSavePacks'] as $m){
	    	$savedPsyS = new EPPsySleight('','','','','','','','');
	    	$savedPsyS->loadSavePack($m);
	    	array_push($this->psySleights, $savedPsyS);
	    }
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
}
