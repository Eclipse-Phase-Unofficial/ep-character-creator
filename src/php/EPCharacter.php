<?php
declare(strict_types=1);

namespace EclipsePhaseCharacterCreator\Backend;

/**
 * The character's general information.
 *
 * @author reinhardt
 */
class EPCharacter {
    public $ego;
    public $currentMorphUid;
    
    //array
    public $morphs;
    
    //character sheet details
    public $playerName;
    public $charName;
    public $realAge;
    public $birthGender;
    public $note;
    
    
    function getSavePack(){
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
        $savePack['currentMorphUid'] = $this->currentMorphUid;

        return $savePack;
    }
    
    function loadSavePack($savePack,$cc = null){
        $this->playerName = $savePack['playerName'];
        $this->charName = $savePack['charName'];
        $this->realAge = $savePack['realAge'];
        $this->birthGender = $savePack['birthGender'];
        $this->note = $savePack['note'];
        $morphSavePack = $savePack['morphSavePacks'];
        if(!empty($morphSavePack)){
                foreach($morphSavePack as $m){
                    $moph = new EPMorph('','','','','','','');
                    $moph->loadSavePack($m);
                    array_push($this->morphs, $moph);
                }
        }
        $this->ego->loadSavePack($savePack['egoSavePack'],$cc); 
        $this->currentMorphUid = $savePack['currentMorphUid'];
    }
    function __construct() {
        $this->ego = new EPEgo();
        $this->morphs = array();
        $this->currentMorphUid = '';
        $this->playerName = '';
        $this->charName = '';
        $this->realAge = '';
        $this->birthGender = '';
        $this->note = '';
    }    
}
