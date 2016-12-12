<?php
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
class EPAtom {
    
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
    
    private $atomUid;
    public $type;
    
    public $occurence;
    public $unique;
    public $name;
    public $description;  
    public $groups;
    
    public $cost;
    public $ratioCost;
    public $ratioCostMorphMod;
    public $ratioCostTraitMod;
    public $ratioCostBackgroundMod;
    public $ratioCostFactionMod;
    public $ratioCostSoftgearMod;
    public $ratioCostPsyMod;
    
    
    function __construct($atType, $atName, $atDesc) {
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
     function getSavePack(){  
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
    function __clone()
    {
        // Ensure a clone object have a different atomUid from original 
        $this->atomUid = uniqid('Atom_'.$this->sanitize($this->name).'_');
    }
    public function getCost(){
        if (is_int($this->cost)){
            return round($this->cost * $this->ratioCost * $this->ratioCostMorphMod * $this->ratioCostTraitMod * $this->ratioCostBackgroundMod * $this->ratioCostFactionMod * $this->ratioCostSoftgearMod * $this->ratioCostPsyMod);
        }
        return 0;
    }
    public function getUid(){
        return $this->atomUid;
    }

    //Strip any character that could cause an issue in an id tag
    private function sanitize($input){
        $replace_char = '/[^A-Z,^a-z,^0-9]/';
        return preg_replace($replace_char, '_', $input);
    }

    // Check if this Atom is in the array
    public function isInArray($array){
        if (!empty($array)){
            foreach ($array as $item){
                if (strcmp($item->getUid(),$this->atomUid) == 0){
                    return true;
                }
            }
        }
        return false;
    }

    // Add this Atom to an array, returns true when done, or if this atom was already in the array
    public function addToArray(&$array){
        if (is_array($array)){
            if (!$this->isInArray($array)){
                array_push($array, $this);
            }
            return true;
        }
        return false;
    }

    // Remove this Atom from an array, returns false on failure
    public function removeFromArray(&$array){
        if (!$this->isInArray($array)){
            return false;
        }else{
            $index = 0;
            foreach ($array as $value) {
                if (strcmp($value->getUid(),$this->getUid()) == 0){
                    break;
                }else{
                    $index++;
                }
            }
            array_splice($array, $index, 1);
            return true;
        }
    }
}

//**********HELPER FUNCTIONS**********//

// Find an Atom with a particular name (potentially dangerous, do not use for skills)
function getAtomByName($array,$name){
    if(!empty($array)){
        foreach ($array as $a){
            if (strcmp($a->name,$name) == 0){
                return $a;
            }
        }
    }
    return null;
}

// Find an atom by unique id (safe)
function getAtomByUid($array,$id){
    if(!empty($array)){
        foreach ($array as $a){
            if (strcmp($a->getUid(),$id) == 0){
                return $a;
            }
        }
    }
    return null;
}

?>
