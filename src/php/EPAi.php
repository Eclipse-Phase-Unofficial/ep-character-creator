<?php
/**
 * Description of EPAi
 *
 * @author reinhardt
 */
require_once 'EPAtom.php';
class EPAi extends EPAtom{   
    public $aptitudes;
    public $skills;
    public $stats;
    public $bonusMalus;
        
    function getSavePack(){
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
        $statsSavePacks = array();
        foreach($this->stats as $m){
            array_push($statsSavePacks, $m->getSavePack());
        }
        $savePack['statsSavePacks'] = $statsSavePacks;
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
        foreach($savePack['statsSavePacks'] as $m){
            $savedStat = new EPStat('','','',0,$cc);
            $savedStat->loadSavePack($m);
            array_push($this->stats, $savedStat);
        }
        foreach($savePack['bmSavePacks'] as $m){
            $savedBm = new EPBonusMalus('','','');
            $savedBm->loadSavePack($m);
            array_push($this->bonusMalus, $savedBm);
        }    
    } 
    function __construct($atName,$aptitudes, $costType,$skills = array(),$stats = array(), $atDesc = '') {
        parent::__construct(EPAtom::$AI, $atName, $atDesc);
        $this->aptitudes = $aptitudes;
        $this->skills = $skills;
        $this->stats = $stats;
        $this->cost = $costType;
        $this->bonusMalus = array();
    }
}
?>
