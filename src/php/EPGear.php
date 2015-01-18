<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EPGear
 *
 * @author reinhardt
 */         
class EPGear extends EPAtom{
            
    static $SOFT_GEAR = "SOF";
    static $STANDARD_GEAR = "STD";
    static $WEAPON_MELEE_GEAR = "WMG";
    static $WEAPON_ENERGY_GEAR = "WEG";
    static $WEAPON_KINETIC_GEAR = "WKG";
    static $WEAPON_SPRAY_GEAR = "WSG";
    static $WEAPON_EXPLOSIVE_GEAR = "WXG";
    static $WEAPON_SEEKER_GEAR = "WSE";
    static $WEAPON_AMMUNITION = "WAM";
    static $WEAPON_ACCESSORY = "WAC";
    static $ARMOR_GEAR = "ARM";
    static $IMPLANT_GEAR = "IMG";
    static $DRUG_GEAR = "DRG";
    static $CHEMICALS_GEAR = "CHG";
    static $POISON_GEAR = "POG";
    static $PET_GEAR = "PEG";
    static $VEHICLES_GEAR = "VEG";
    static $ROBOT_GEAR = "ROG";
    
    //not used on the database
    static $FREE_GEAR = "FRE";
    
    //GUI use for filtering the listes
    static $CAN_USE_EVERYBODY = 'EVERY';
    static $CAN_USE_BIO = 'BIO';
    static $CAN_USE_BIO_POD = 'BIOPOD';
    static $CAN_USE_SYNTH_POD = 'SYNTHPOD';
    static $CAN_USE_SYNTH = 'SYNTH';
    static $CAN_USE_POD = 'POD';
    static $CAN_USE_CREATE_ONLY = 'CREATION';//useful for hiding gear
    
    public $armorEnergy;
    public $armorKinetic;
    
    public $degat;
    public $armorPenetration;
    
    public $gearType;
    public $gearRestriction;
        
    public $armorPenetrationMorphMod;
    public $degatMorphMod;
    public $armorEnergyMorphMod;
    public $armorKineticMorphMod;
    
    public $armorPenetrationTraitMod;
    public $degatTraitMod;
    public $armorEnergyTraitMod;
    public $armorKineticTraitMod;
    
    public $armorPenetrationBackgroundMod;
    public $degatBackgroundMod;
    public $armorEnergyBackgroundMod;
    public $armorKineticBackgroundMod;
    
    public $armorPenetrationFactionMod;
    public $degatFactionMod;
    public $armorEnergyFactionMod;
    public $armorKineticFactionMod;   
    
    public $armorPenetrationSoftgearMod;
    public $degatSoftgearMod;
    public $armorEnergySoftgearMod;
    public $armorKineticSoftgearMod;
    
    public $armorPenetrationPsyMod;
    public $degatPsyMod;
    public $armorEnergyPsyMod;
    public $armorKineticPsyMod;
   
    //array
    public $bonusMalus;
    
    function getSavePack(){
        $savePack = parent::getSavePack();
		
        $savePack['armorEnergy'] =  $this->armorEnergy;
        $savePack['armorKinetic'] =  $this->armorKinetic;
        $savePack['degat'] =  $this->degat;
        $savePack['armorPenetration'] =  $this->armorPenetration;
        $savePack['gearType'] =  $this->gearType;
        $savePack['gearRestriction'] =  $this->gearRestriction;
        $savePack['armorPenetrationMorphMod'] =  $this->armorPenetrationMorphMod;
        $savePack['degatMorphMod'] =  $this->degatMorphMod;
        $savePack['armorEnergyMorphMod'] =  $this->armorEnergyMorphMod;
        $savePack['armorKineticMorphMod'] =  $this->armorKineticMorphMod;
        $savePack['armorPenetrationTraitMod'] =  $this->armorPenetrationTraitMod;
        $savePack['degatTraitMod'] =  $this->degatTraitMod;
        $savePack['armorEnergyTraitMod'] =  $this->armorEnergyTraitMod;
        $savePack['armorKineticTraitMod'] =  $this->armorKineticTraitMod;
        $savePack['armorPenetrationBackgroundMod'] =  $this->armorPenetrationBackgroundMod;
        $savePack['degatBackgroundMod'] =  $this->degatBackgroundMod;
        $savePack['armorEnergyBackgroundMod'] =  $this->armorEnergyBackgroundMod;
        $savePack['armorKineticBackgroundMod'] =  $this->armorKineticBackgroundMod;
        $savePack['armorPenetrationFactionMod'] =  $this->armorPenetrationFactionMod;
        $savePack['degatFactionMod'] =  $this->degatFactionMod;
        $savePack['armorEnergyFactionMod'] =  $this->armorEnergyFactionMod;
        $savePack['armorKineticFactionMod'] =  $this->armorKineticFactionMod;   
        $savePack['armorPenetrationSoftgearMod'] =  $this->armorPenetrationSoftgearMod;
        $savePack['degatSoftgearMod'] =  $this->degatSoftgearMod;
        $savePack['armorEnergySoftgearMod'] =  $this->armorEnergySoftgearMod;
        $savePack['armorKineticSoftgearMod'] =  $this->armorKineticSoftgearMod;
        $savePack['armorPenetrationPsyMod'] =  $this->armorPenetrationPsyMod;
        $savePack['degatPsyMod'] =  $this->degatPsyMod;
        $savePack['armorEnergyPsyMod'] =  $this->armorEnergyPsyMod;
        $savePack['armorKineticPsyMod'] =  $this->armorKineticPsyMod;
        $bmSavePacks = array();
        foreach($this->bonusMalus as $m){
            array_push($bmSavePacks	, $m->getSavePack());
        }
        $savePack['bmSavePacks'] = $bmSavePacks;

        return $savePack;
    }
    function loadSavePack($savePack,$cc = null){
	parent::loadSavePack($savePack);    
	    
        $this->armorEnergy = $savePack['armorEnergy'];
        $this->armorKinetic = $savePack['armorKinetic'];
        $this->degat = $savePack['degat'];
        $this->armorPenetration = $savePack['armorPenetration'];
        $this->gearType = $savePack['gearType'];
        $this->gearRestriction = $savePack['gearRestriction'];
        $this->armorPenetrationMorphMod = $savePack['armorPenetrationMorphMod'];
        $this->degatMorphMod = $savePack['degatMorphMod'];
        $this->armorEnergyMorphMod = $savePack['armorEnergyMorphMod'];
        $this->armorKineticMorphMod = $savePack['armorKineticMorphMod'];
        $this->armorPenetrationTraitMod = $savePack['armorPenetrationTraitMod'];
        $this->degatTraitMod = $savePack['degatTraitMod'];
        $this->armorEnergyTraitMod = $savePack['armorEnergyTraitMod'];
        $this->armorKineticTraitMod = $savePack['armorKineticTraitMod'];
        $this->armorPenetrationBackgroundMod = $savePack['armorPenetrationBackgroundMod'];
        $this->degatBackgroundMod = $savePack['degatBackgroundMod'];
        $this->armorEnergyBackgroundMod = $savePack['armorEnergyBackgroundMod'];
        $this->armorKineticBackgroundMod = $savePack['armorKineticBackgroundMod'];
        $this->armorPenetrationFactionMod = $savePack['armorPenetrationFactionMod'];
        $this->degatFactionMod = $savePack['degatFactionMod'];
        $this->armorEnergyFactionMod = $savePack['armorEnergyFactionMod'];
        $this->armorKineticFactionMod = $savePack['armorKineticFactionMod'];   
        $this->armorPenetrationSoftgearMod = $savePack['armorPenetrationSoftgearMod'];
        $this->degatSoftgearMod = $savePack['degatSoftgearMod'];
        $this->armorEnergySoftgearMod = $savePack['armorEnergySoftgearMod'];
        $this->armorKineticSoftgearMod = $savePack['armorKineticSoftgearMod'];
        $this->armorPenetrationPsyMod = $savePack['armorPenetrationPsyMod'];
        $this->degatPsyMod = $savePack['degatPsyMod'];
        $this->armorEnergyPsyMod = $savePack['armorEnergyPsyMod'];
        $this->armorKineticPsyMod = $savePack['armorKineticPsyMod'];
        foreach($savePack['bmSavePacks'] as $m){
            $savedBm = new EPBonusMalus('','','');
            $savedBm->loadSavePack($m);
            array_push($this->bonusMalus, $savedBm);
        }
    }
    
    
    function __construct($atName, $atDesc,$gearType, $costType, $armorKinetic = 0,$armorEnergy = 0,$degat = 0,$armorPenetration = 0, $bonusmalus = array(),$gearRestriction='EVERY') {
        parent::__construct(EPAtom::$GEAR, $atName, $atDesc);
        $this->gearType = $gearType;
        $this->armorKinetic = $armorKinetic;
        $this->armorEnergy = $armorEnergy;
        $this->degat = $degat;
        $this->armorPenetration = $armorPenetration;
        $this->cost = $costType;
        $this->bonusMalus = $bonusmalus;
        $this->gearRestriction = $gearRestriction;
        $this->armorPenetrationMorphMod = 0;
        $this->degatMorphMod = 0;
        $this->armorEnergyMorphMod = 0;
        $this->armorKineticMorphMod = 0;
        $this->armorPenetrationTraitMod = 0;
        $this->degatTraitMod = 0;
        $this->armorEnergyTraitMod = 0;
        $this->armorKineticTraitMod = 0;
        $this->armorPenetrationBackgroundMod = 0;
        $this->degatBackgroundMod = 0;
        $this->armorEnergyBackgroundMod = 0;
        $this->armorKineticBackgroundMod = 0;
        $this->armorPenetrationFactionMod = 0;
        $this->degatFactionMod = 0;
        $this->armorEnergyFactionMod = 0;
        $this->armorKineticFactionMod = 0;
        $this->armorPenetrationSoftgearMod = 0;
        $this->degatSoftgearMod = 0;
        $this->armorEnergySoftgearMod = 0;
        $this->armorKineticSoftgearMod = 0;  
        $this->armorPenetrationPsyMod = 0;
        $this->degatPsyMod = 0;
        $this->armorEnergyPsyMod = 0;
        $this->armorKineticPsyMod = 0;
    }
    function getArmorEnergy(){
        return $this->armorEnergy + $this->armorEnergyMorphMod + $this->armorEnergyTraitMod + $this->armorEnergyBackgroundMod + $this->armorEnergyFactionMod + $this->armorEnergySoftgearMod + $this->armorEnergyPsyMod; 
    }
    function getArmorKinetic(){
        return $this->armorKinetic + $this->armorKineticMorphMod + $this->armorKineticTraitMod + $this->armorKineticBackgroundMod + $this->armorKineticFactionMod + $this->armorKineticSoftgearMod + $this->armorKineticPsyMod; 
    }
    function getDegat(){
        return $this->degat + $this->degatMorphMod + $this->degatTraitMod + $this->degatBackgroundMod + $this->degatFactionMod + $this->degatSoftgearMod + $this->degatPsyMod; 
    }
    function getArmorPenetration(){
        return $this->armorPenetration + $this->armorPenetrationMorphMod + $this->armorPenetrationTraitMod + $this->armorPenetrationBackgroundMod + $this->armorPenetrationFactionMod + $this->armorPenetrationSoftgearMod + $this->armorPenetrationPsyMod; 
    }
}

?>
