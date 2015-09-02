<?php

/*
 * Provide all list of EPAtom object needed by EPCharacterCreator
 * 
 */
require_once 'EPAptitude.php';
require_once 'EPStat.php';
require_once 'EPReputation.php';
require_once 'EPConfigFile.php';
require_once 'EPBonusMalus.php';
require_once 'EPTrait.php';
require_once 'EPBackground.php';
require_once 'EPGear.php';
require_once 'EPAi.php';
require_once 'EPPsySleight.php';

class EPListProvider {
    
    static $BOOK_RIMWARD 		= "Rimward";
    static $BOOK_PANOPTICON 	= "Panopticon";
    static $BOOK_SUNWARD 		= "Sunward";
    static $BOOK_GATECRASHING 	= "Gatecrashing";
    static $BOOK_TRANSHUMAN 	= "Transhuman";
    static $BOOK_ECLIPSEPHASE   = "Eclipse Phase";
    
    
    public $errors;
    private $database;
    private $configValues;
    
    function __construct($configPath) {
        $this->errors = array();
        
        $this->configValues = new EPConfigFile($configPath);
        $serverName = $this->configValues->getValue('SQLValues','serverName');
        $databaseName = $this->configValues->getValue('SQLValues','databaseName');
        $databaseUser = $this->configValues->getValue('SQLValues','databaseUser');
        $databasePassword = $this->configValues->getValue('SQLValues','databasePassword'); 
        $databasePort = $this->configValues->getValue('SQLValues','databasePort');        

        $this->database = new mysqli($serverName, $databaseUser, $databasePassword, $databaseName, $databasePort);
        if ($this->database->connect_errno) {
             $this->addError("Failed to connect to MySQL: (" . $this->database->connect_errno . ") " . $this->database->connect_error);
        };
    }
    
    function reconnect(){
	    $serverName = $this->configValues->getValue('SQLValues','serverName');
        $databaseName = $this->configValues->getValue('SQLValues','databaseName');
        $databaseUser = $this->configValues->getValue('SQLValues','databaseUser');
        $databasePassword = $this->configValues->getValue('SQLValues','databasePassword'); 
        $databasePort = $this->configValues->getValue('SQLValues','databasePort');        

        $this->database = new mysqli($serverName, $databaseUser, $databasePassword, $databaseName, $databasePort);
        if ($this->database->connect_errno) {
             $this->addError("Failed to connect to MySQL: (" . $this->database->connect_errno . ") " . $this->database->connect_error);
        };
    }
    
    function addError($error){
        array_push($this->errors, $error);
    }
    
    function getLastError(){
        return array_pop($this->errors);
    }
    
    //Helper functions
    function adjustForSQL($string){
        //$candidat = "";
        $candidat = str_replace("'", "''", $string);
        return $candidat;
    }
    
    //==== BONUS MALUS =========
    
    function getListBonusMalus(){
         $bmList = array();
        
        if($this->database->real_query("SELECT `name`, `desc`, `type`, `target`, `value`, `tragetForCh`, `typeTarget`, `onCost`, `multiOccur` FROM `bonusMalus`;")){
            $res = $this->database->store_result();

            while ($row = $res->fetch_assoc()) {
                
                $groups = $this->getListGroups($row['name']);
             
                $bmTypes = $this->getBonusMalusTypes($row['name']);
            
                $epBonMal = new EPBonusMalus($row['name'],$row['type'],$row['value'],$row['target'],$row['desc'],$groups,$row['onCost'],$row['tragetForCh'], $row['typeTarget'],$bmTypes,$row['multiOccur']);
                //$bmList[$row['name']] = $epBonMal; 
                array_push($bmList, $epBonMal);
            }

            return $bmList;
        }
        else{
            $this->addError("Get Bonus Malus failed: (" . $this->database->errno . ") " . $this->database->error);
           return null; 
        }
    }
    
    function getBonusMalusByName($name){   
        if($this->database->real_query("SELECT `name`, `desc`, `type`, `target`, `value`, `tragetForCh`, `typeTarget`, `onCost`, `multiOccur` FROM `bonusMalus` WHERE `name` = '".$this->adjustForSQL($name)."';")){
            $bmRes = $this->database->store_result();
            $row = $bmRes->fetch_array();
			$groups = $this->getListGroups($row['name']);
              
            $bmTypes = $this->getBonusMalusTypes($row['name']);
              
            $epBonMal = new EPBonusMalus($row['name'],$row['type'],$row['value'],$row['target'],$row['desc'],$groups,$row['onCost'],$row['tragetForCh'], $row['typeTarget'],$bmTypes,$row['multiOccur']);
            return $epBonMal;
        }
        else{
            $this->addError("Get Bonus Malus by name failed: (" . $this->database->errno . ") " . $this->database->error);
            return null;
        }
    }
    
    function getBonusMalusTypes($bmName){
	    $bmTypeArray = array();
	    if($this->database->real_query("SELECT `bmChoices` FROM `BonusMalusTypes` WHERE `bmNameMain` = '".$this->adjustForSQL($bmName)."';")){
	    	$res = $this->database->store_result();

            while ($row = $res->fetch_assoc()) {
            	$assocBm = $this->getBonusMalusByName($row['bmChoices']);
            	array_push($bmTypeArray, $assocBm);
            }
            
            return $bmTypeArray;
	    }
	    else{
            $this->addError("Get Bonus Malus multi Types by name failed: (" . $this->database->errno . ") " . $this->database->error);
            return null;
        }
    }
    
    // ===== INFOS ======
    function getInfosById($id){   
        if($this->database->real_query("SELECT `id`, `data` FROM `infos` WHERE `id` = '".$this->adjustForSQL($id)."';")){
            $infoRes = $this->database->store_result();
            $row = $infoRes->fetch_array();
            $info = $row['data'];
            return $info;
        }
        else{
            $this->addError("Get Info by id failed: (" . $this->database->errno . ") " . $this->database->error);
            return null;
        }
    }
    
    // ===== TRAIT ======
    
    function getListTraits(){
        $traitList = array();
        
        
        if($this->database->real_query("SELECT `name`, `desc`, `side`, `onwhat`, `cpCost` , `level` , `JustFor` FROM `traits`")){
            $traitRes = $this->database->store_result();
            while ($traitRow = $traitRes->fetch_assoc()) {

                if($this->database->real_query("SELECT `traitName`, `bonusMalusName`,`occur` FROM `TraitBonusMalus` WHERE `traitName` = '".$this->adjustForSQL($traitRow['name'])."';")){
                    $bonusMalus = $this->database->store_result();
                    
                    $bonusMalusTraitList = array();
                        
                    while ($bmRow = $bonusMalus->fetch_assoc()) {
                         $epBonMal = $this->getBonusMalusByName($bmRow['bonusMalusName']); 
                         if($epBonMal == null){
                             $this->addError("Get Trait getBonusByName function call failed: (" . $this->database->errno . ") " . $this->database->error);
                             return null; 
                         }
                         else{
                            for($i = 0; $i < $bmRow['occur']; $i++ ){ 
                                //$bonusMalusTraitList[$bmRow['bonusMalusName']] = $epBonMal;
                                array_push($bonusMalusTraitList, $epBonMal);
                            }
                         }
                    }
                }
                else{
                    $this->addError("Get Trait BonusmalusList failed: (" . $this->database->errno . ") " . $this->database->error);
                    return null; 
                }

                $trait = new EPTrait($traitRow['name'],$traitRow['desc'],$traitRow['side'],$traitRow['onwhat'],$traitRow['cpCost'],$bonusMalusTraitList,$traitRow['level'],$traitRow['JustFor']);
                array_push($traitList, $trait);
             }

            return $traitList;
        }
        else{
            $this->addError("Get Trait failed: (" . $this->database->errno . ") " . $this->database->error);
            return null;
        }
    }
    
    function getTraitByName($traitName){
        $bonusMalusTraitList = array();
        if($this->database->real_query("SELECT `name`, `desc`, `side`, `onwhat`, `cpCost`, `level`, `JustFor` FROM `traits` WHERE `name` = '".$this->adjustForSQL($traitName)."';")){
            $traitRes = $this->database->store_result();
            $traitRow = $traitRes->fetch_array();

            if($this->database->real_query("SELECT `traitName`, `bonusMalusName`,`occur` FROM `TraitBonusMalus` WHERE `traitName` = '".$this->adjustForSQL($traitRow['name'])."';")){
                $bonusMalus = $this->database->store_result();


                while ($bmRow = $bonusMalus->fetch_assoc()) {
                     $epBonMal = $this->getBonusMalusByName($bmRow['bonusMalusName']); 
                     if($epBonMal == null){
                         $this->addError("Get Trait by name getBonusByName function call failed: (" . $this->database->errno . ") " . $this->database->error);
                         return null; 
                     }
                     else{
                        for($i = 0; $i < $bmRow['occur']; $i++ ){ 
                            //$bonusMalusTraitList[$bmRow['bonusMalusName']] = $epBonMal;
                            array_push($bonusMalusTraitList, $epBonMal);
                        }
                     }
                }
            }
            else{
                $this->addError("Get Trait by name BonusmalusList failed: (" . $this->database->errno . ") " . $this->database->error);
                return null; 
            }

            $trait = new EPTrait($traitRow['name'],$traitRow['desc'],$traitRow['side'],$traitRow['onwhat'],$traitRow['cpCost'],$bonusMalusTraitList,$traitRow['level'],$traitRow['JustFor']);
             

            return $trait;
        }
        else{
            $this->addError("Get Trait failed: (" . $this->database->errno . ") " . $this->database->error);
            return null;
        }
        
    }
    
    // ==== APTITUDE ======
    
    function getListAptitudes($minValue = 0, $maxValue = 0){
       $apt = array();
       if ($minValue == 0){
           $minValue = $this->configValues->getValue('RulesValues', 'AptitudesMinValue');
       }
       if ($maxValue == 0){
           $maxValue = $this->configValues->getValue('RulesValues', 'AptitudesMaxValue');
       }      
       $absMax = $this->configValues->getValue('RulesValues', 'AbsoluteAptitudesMaxValue');
       
        if($this->database->real_query("SELECT `name`, `description`, `abbreviation` FROM `aptitude`")){
            $res = $this->database->store_result();
            
            while ($row = $res->fetch_assoc()) {
                $groups = $this->getListGroups($row['name']);
                $epAppt = new EPAptitude($row['name'], $row['abbreviation'], $row['description'], $groups,$minValue,$maxValue,$minValue,$absMax);
                //$apt[$epAppt->abbreviation] = $epAppt;
                array_push($apt, $epAppt);
            }

            return $apt;
        }
        else{
            $this->addError("Get Aptitude failed: (" . $this->database->errno . ") " . $this->database->error);
            return null;
        }
    }
    
     function getListAptitudesComplete($minValue = 0, $maxValue = 0){
       $apt = array();

       if ($minValue == 0){
           $minValue = $this->configValues->getValue('RulesValues', 'AptitudesMinValue');
       }
       if ($maxValue == 0){
           $maxValue = $this->configValues->getValue('RulesValues', 'AptitudesMaxValue');
       }
       
       $absMax = $this->configValues->getValue('RulesValues', 'AbsoluteAptitudesMaxValue');
       
        if($this->database->real_query("SELECT `name`, `description`, `abbreviation` FROM `aptitude`")){
            $res = $this->database->store_result();
            
            while ($row = $res->fetch_assoc()) {
                $groups = $this->getListGroups($row['name']);
                $epAppt = new EPAptitude($row['name'], $row['abbreviation'], $row['description'], $groups,$minValue,$maxValue,$minValue,$absMax);
                array_push($apt, $epAppt);
            }

            return $apt;
        }
        else{
            $this->addError("Get Complete Aptitude failed: (" . $this->database->errno . ") " . $this->database->error);
            return null;
        }
    }
    
    function getAptitudeByName($aptName,$minValue=0,$maxValue=0){
       if ($minValue == 0){
           $minValue = $this->configValues->getValue('RulesValues', 'AptitudesMinValue');
       }
       if ($maxValue == 0){
           $maxValue = $this->configValues->getValue('RulesValues', 'AptitudesMaxValue');
       }
       
       $absMax = $this->configValues->getValue('RulesValues', 'AbsoluteAptitudesMaxValue');
       
        if($this->database->real_query("SELECT `name`, `description`, `abbreviation` FROM `aptitude` WHERE `name` = '".$this->adjustForSQL($aptName)."';")){
            $res = $this->database->store_result();
            $row = $res->fetch_array();

            $groups = $this->getListGroups($row['name']);
            $epAppt = new EPAptitude($row['name'], $row['abbreviation'], $row['description'], $groups,$minValue,$maxValue,$minValue,$absMax);

            return $epAppt;
        }
        else{
            $this->addError("Get Aptitude by name failed: (" . $this->database->errno . ") " . $this->database->error);
        }      
    }
    function getAptitudeByAbbreviation($abbrev,$minValue=0,$maxValue=0){
       if ($minValue == 0){
           $minValue = $this->configValues->getValue('RulesValues', 'AptitudesMinValue');
       }
       if ($maxValue == 0){
           $maxValue = $this->configValues->getValue('RulesValues', 'AptitudesMaxValue');
       }
       
       $absMax = $this->configValues->getValue('RulesValues', 'AbsoluteAptitudesMaxValue');
       
        if($this->database->real_query("SELECT `name`, `description`, `abbreviation` FROM `aptitude` WHERE `abbreviation` = '".$abbrev."';")){
            $res = $this->database->store_result();
            $row = $res->fetch_array();

            $groups = $this->getListGroups($row['name']);
            $epAppt = new EPAptitude($row['name'], $row['abbreviation'], $row['description'], $groups,$minValue,$maxValue,$minValue,$absMax);

            return $epAppt;
        }
        else{
            $this->addError("Get Aptitude by abbreviation failed: (" . $this->database->errno . ") " . $this->database->error);
        }        
    }
    
    //=== STATS ====
    
    function getListStats($configValues,&$cc=null){
        $stats = array();
        
        if($this->database->real_query("SELECT `name`, `description`, `abbreviation` FROM `stat`")){
            $res = $this->database->store_result();
            
            while ($row = $res->fetch_assoc()) {
                $groups = $this->getListGroups($row['name']);
                $epStats = new EPStat($row['name'], $row['description'], $row['abbreviation'], $groups,0,$cc);
                if (strcmp($epStats->abbreviation,EPStat::$MOXIE) == 0){
                    $epStats->value = $configValues->getValue('RulesValues','MoxieStartValue');
                }
                if (strcmp($epStats->abbreviation,EPStat::$SPEED) == 0){
                    $epStats->value = $configValues->getValue('RulesValues','SpeedStartValue');
                }
                //$stats[$row['abbreviation']] = $epStats;
                array_push($stats, $epStats);
            }

            return $stats;
        }
        else{
            $this->addError("Get Stats failed: (" . $this->database->errno . ") " . $this->database->error);
            return null;
        }
    }
    
    function getStatByName($statName){
         if($this->database->real_query("SELECT `name`, `description`, `abbreviation` FROM `stat` WHERE `name`='".$this->adjustForSQL($statName)."';")){
            $res = $this->database->store_result();
            $row = $res->fetch_array();

            $groups = $this->getListGroups($row['name']);
            $epStats = new EPStat($row['name'], $row['description'], $row['abbreviation'], $groups);

            return $epStats;
        }
        else{
            $this->addError("Get Stats by name failed: (" . $this->database->errno . ") " . $this->database->error);
            return null;
        }
    }
    
    //=== PREFIX ===
    
    function getListPrefix(){
         $prefixes = array();
        
        if($this->database->real_query("SELECT `prefix` FROM `skillPrefixes`")){
            $res = $this->database->store_result();
            
            while ($row = $res->fetch_assoc()) {
                array_push($prefixes, $row['prefix']);
            }

            return $prefixes;
        }
        else{
            $this->addError("Get Prefixes failed: (" . $this->database->errno . ") " . $this->database->error);
            return null;
        }
    }
    
    
    function getAptForPrefix($prefixName){
                
        if($this->database->real_query("SELECT `linkedApt` FROM `skillPrefixes` WHERE `prefix` = '".$prefixName."';")){
            $res = $this->database->store_result();
            
           $row = $res->fetch_array();

            return $row['linkedApt'];
        }
        else{
            $this->addError("Get Apt for prefix failed: (" . $this->database->errno . ") " . $this->database->error);
            return null;
        }
    }
    
    function getTypeForPrefix($prefixName){
                
        if($this->database->real_query("SELECT `skillType` FROM `skillPrefixes` WHERE `prefix` = '".$prefixName."';")){
            $res = $this->database->store_result();
            
           $row = $res->fetch_array();

            return $row['skillType'];
        }
        else{
            $this->addError("Get type for prefix failed: (" . $this->database->errno . ") " . $this->database->error);
            return null;
        }
    }
    
    function getPrefixDescription($prefixName){
                
        if($this->database->real_query("SELECT `desc` FROM `skillPrefixes` WHERE `prefix` = '".$prefixName."';")){
            $res = $this->database->store_result();
            
           $row = $res->fetch_array();

            return $row['desc'];
        }
        else{
            $this->addError("Get prefix description failed: (" . $this->database->errno . ") " . $this->database->error);
            return null;
        }
    }
    
    // ===== Services ====
    function getAptByAbreviation($listApts,$abr){
        foreach ($listApts as $ap){
            if (strcmp($ap->abbreviation,$abr) == 0){
                return $ap;
            }
        }
        return null;
    }
    
    // ===== SKILLS ===========
    
    function getListSkills($listApt){        
        $skills = array();
        
        if($this->database->real_query("SELECT `name`, `desc`, `linkedApt`, `prefix`, `skillType`, `defaultable`  FROM skills")){
            $res = $this->database->store_result();
            
            while ($row = $res->fetch_assoc()) {
                $groups = $this->getListGroups($row['name']);
                $epSkills = new EPSkill($row['name'],$row['desc'], $this->getAptByAbreviation($listApt,$row['linkedApt']),$row['skillType'],$row['defaultable'],$row['prefix'],$groups);
                array_push($skills, $epSkills);
            }
            return $skills;
        }
        else{
            $this->addError("Get Skill failed: (" . $this->database->errno . ") " . $this->database->error);
            return null;
        }
    }
    
    function getSkillByNamePrefix($name,$prefix,$listApt){
        
        if($this->database->real_query("SELECT `name`, `desc`, `linkedApt`, `prefix`, `skillType`, `defaultable`  FROM skills WHERE `name` = '".$this->adjustForSQL($name)."' AND `prefix` ='".$this->adjustForSQL($prefix)."';")){
            $res = $this->database->store_result();
            $row = $res->fetch_array();
           
            $groups = $this->getListGroups($row['name']);
            $epSkills = new EPSkill($row['name'],$row['desc'],$this->getAptByAbreviation($listApt,$row['linkedApt']),$row['skillType'],$row['defaultable'],$row['prefix'],$groups);
            

            return $epSkills;
        }
        else{
            $this->addError("Get Skill by name and prefix failed: (" . $this->database->errno . ") " . $this->database->error);
            return null;
        }
    }
    
    function getSkillByName($name,$listApt){
        
        if($this->database->real_query("SELECT `name`, `desc`, `linkedApt`, `prefix`, `skillType`, `defaultable`  FROM skills WHERE `name` = '".$this->adjustForSQL($name)."';")){
            $res = $this->database->store_result();
            $row = $res->fetch_array();
           
            $groups = $this->getListGroups($row['name']);
            if ($row['defaultable']== 'Y'){
                $defaultTable = EPSkill::$DEFAULTABLE;
            }else{
                $defaultTable = EPSkill::$NO_DEFAULTABLE;
            }
            $epSkills = new EPSkill($row['name'],$row['desc'],$this->getAptByAbreviation($listApt,$row['linkedApt']),$row['skillType'],$defaultTable,$row['prefix'],$groups);
            

            return $epSkills;
        }
        else{
            $this->addError("Get Skill by name failed: (" . $this->database->errno . ") " . $this->database->error);
            return null;
        }
    }
    
    // ==== GROUPE =====
    
    function getListGroups($targetName = ""){
        if(!empty($targetName)){
            $groupsList = array();
          
            if($this->database->real_query("SELECT `groupName`, `targetName` FROM `GroupName` WHERE `targetName` = '".$this->adjustForSQL($targetName)."';")){
                $groups = $this->database->store_result();

                while ($groupRow = $groups->fetch_assoc()) {
                     if($groupRow == null){
                         $this->addError("Get group list  failed: (" . $this->database->errno . ") " . $this->database->error);
                         //error_log($this->getLastError());
                         return null; 
                     }
                     else{
                        array_push($groupsList, $groupRow['groupName']);
                     }
                }
            }
            else{
                $this->addError("Get Skill GroupsList failed: (" . $this->database->errno . ") " . $this->database->error);
                //error_log($this->getLastError());
                return null; 
            }
            return $groupsList; 
        }
        else{
             $groupsList = array();
          
            if($this->database->real_query("SELECT DISTINCT `groupName` FROM `GroupName`;")){
                $groups = $this->database->store_result();

                while ($groupRow = $groups->fetch_assoc()) {
                     if($groupRow == null){
                         $this->addError("Get group list for Skill  failed: (" . $this->database->errno . ") " . $this->database->error);
                         // error_log($this->getLastError());
                         return null; 
                     }
                     else{
                        array_push($groupsList, $groupRow['groupName']);
                     }
                }
            }
            else{
                $this->addError("Get Skill GroupsList failed: (" . $this->database->errno . ") " . $this->database->error);
                 //error_log($this->getLastError());
                return null; 
            }
            return $groupsList; 
        }
        
        return $grps;
    }
    
    //==== REPUTATION ====
    
    function getListReputation(){
         $reputations = array();
        
        if($this->database->real_query("SELECT `name`, `description` FROM `reputation`")){
            $res = $this->database->store_result();
            
            while ($row = $res->fetch_assoc()) {
                $groups = $this->getListGroups($row['name']);
                $epReputation = new EPReputation($row['name'],$row['description'],$groups,0,$this->configValues->getValue('RulesValues', 'RepMaxPoint'));
                //$reputations[$row['name']] = $epReputation;
                array_push($reputations, $epReputation);
            }

            return $reputations;
        }
        else{
            $this->addError("Get Reputation failed: (" . $this->database->errno . ") " . $this->database->error);
            return null;
        }
        
        return $reps;
    }
    
    //==== BACKGROUND =====
    
    function getListBackgrounds(){
        $backgroundList = array();
        
        
        if($this->database->real_query("SELECT `name`, `description`, `type` FROM `background`")){
            $bckRes = $this->database->store_result();
            while ($bckRow = $bckRes->fetch_assoc()) {
                //Bonus Malus
                if($this->database->real_query("SELECT `background`, `bonusMalus`, `occurrence` FROM `BackgroundBonusMalus` WHERE `background` = '".$this->adjustForSQL($bckRow['name'])."';")){
                    $bonusMalus = $this->database->store_result();
                    $backgroundBonusMalusList = array();
        
                    
                    while ($bmRow = $bonusMalus->fetch_assoc()) {
                      
                        for($i = 0; $i < $bmRow['occurrence']; $i++ ){ 
                        	$epBonMal = $this->getBonusMalusByName($bmRow['bonusMalus']); 
	                         if($epBonMal == null){
	                             $this->addError("Get Background getBonusByName function call failed: (" . $this->database->errno . ") " . $this->database->error);
	                             return null; 
	                         }
                            array_push($backgroundBonusMalusList, $epBonMal);
                        }
                         
                    }
                }
                else{
                    $this->addError("Get Background BonusmalusList failed: (" . $this->database->errno . ") " . $this->database->error);
                    return null; 
                }
                //Traits
                if($this->database->real_query("SELECT `background`, `trait` FROM `BackgroundTrait` WHERE `background` = '".$this->adjustForSQL($bckRow['name'])."';")){
                    $traits = $this->database->store_result();
                    $backgroundTraitList = array();
                    
                    while ($traitRow = $traits->fetch_assoc()) {
                         $epTraits = $this->getTraitByName($traitRow['trait']); 
                         if($epTraits == null){
                             $this->addError("Get Background getTraitByName function call failed: (" . $this->database->errno . ") " . $this->database->error );
                             return null; 
                         }
                         else{
                             array_push($backgroundTraitList, $epTraits);
                         }
                    }
                }
                else{
                    $this->addError("Get Background Traits failed: (" . $this->database->errno . ") " . $this->database->error);
                    return null; 
                }
                //limitations
                if($this->database->real_query("SELECT `background`, `limitationGroup` FROM `BackgroundLimitation` WHERE `background` = '".$this->adjustForSQL($bckRow['name'])."';")){
                    $limit = $this->database->store_result();
                    
                     $bckLimitation = array();
                    
                    while ($limitRow = $limit->fetch_assoc()) {
                        array_push($bckLimitation, $limitRow['limitationGroup']);  
                    }
                }
                else{
                    $this->addError("Get Background limitations failed: (" . $this->database->errno . ") " . $this->database->error);
                    return null; 
                }
                
                //obligations
                if($this->database->real_query("SELECT `background`, `obligationGroup` FROM `BackgroundObligation` WHERE `background` = '".$this->adjustForSQL($bckRow['name'])."';")){
                    $obl = $this->database->store_result();
                    
                    $bckObligation = array();
                    
                    while ($oblRow = $obl->fetch_assoc()) {
                        array_push($bckObligation, $oblRow['obligationGroup']);  
                    }
                }
                else{
                    $this->addError("Get Background obligation failed: (" . $this->database->errno . ") " . $this->database->error);
                    return null; 
                }               
                $bck = new EPBackground($bckRow['name'],$bckRow['description'],$bckRow['type'],$backgroundBonusMalusList,$backgroundTraitList,$bckLimitation,$bckObligation);
                //$backgroundList[$bckRow['name']] = $bck;
                array_push($backgroundList, $bck);
             }

            return $backgroundList;
        }
        else{
            $this->addError("Get Bckground failed: (" . $this->database->errno . ") " . $this->database->error);
            return null;
        }
    }
    
    // ==== AI =====
    function getListAi(){
        $aiList = array();
        
        if($this->database->real_query("SELECT `name`, `desc`, `cost`, `unique` FROM `ai`")){
            $aiRes = $this->database->store_result();
            while ($aiRow = $aiRes->fetch_assoc()) {
                
                //aptitudes
                if($this->database->real_query("SELECT `ai`, `aptitude`, `value` FROM `AiAptitude` WHERE `ai` = '".$this->adjustForSQL($aiRow['name'])."';")){
                    $aptRes = $this->database->store_result();
                    $aptitudeList = array();

                    while ($aptRow = $aptRes->fetch_assoc()) {
                         $epApt = $this->getAptitudeByName($aptRow['aptitude']);
                         $epApt->value = $aptRow['value'];
                         if($epApt == null){
                             $this->addError("Get Ai getAptitudeByName function call failed: (" . $this->database->errno . ") " . $this->database->error);
                             return null; 
                         }
                         else{
                             //$aptitudeList[$epApt->abbreviation] = $epApt;
                             array_push($aptitudeList, $epApt);
                         }
                    }
                }
                else{
                    $this->addError("Get Ai Aptitude List failed: (" . $this->database->errno . ") " . $this->database->error);
                    return null; 
                }

                //skills
                if($this->database->real_query("SELECT `ai`, `skillName`, `skillPrefix`, `value` FROM `AiSkill` WHERE `ai` = '".$this->adjustForSQL($aiRow['name'])."';")){
                    $skillRes = $this->database->store_result();
                    $skillList = array();

                    while ($skillRow = $skillRes->fetch_assoc()) {
                         $epSkill = $this->getSkillByNamePrefix($skillRow['skillName'],$skillRow['skillPrefix'],$aptitudeList);
                         $epSkill->baseValue = $skillRow['value'];
                         if($epSkill == null){
                             $this->addError("Get Ai getSkillByNamePrefix function call failed: (" . $this->database->errno . ") " . $this->database->error);
                             return null; 
                         }
                         else{
                             //$skillList[$epSkill->prefix.$epSkill->name] = $epSkill;
                             array_push($skillList, $epSkill);
                         }
                    }
                }
                else{
                    $this->addError("Get Ai Skill List failed: (" . $this->database->errno . ") " . $this->database->error);
                    return null; 
                }
                
                //stats
                if($this->database->real_query("SELECT `ai`, `stat`, `value` FROM `AiStat` WHERE `ai` = '".$this->adjustForSQL($aiRow['name'])."';")){
                    $statRes = $this->database->store_result();
                    $statList = array();

                    while ($statRow = $statRes->fetch_assoc()) {
                         $epStat = $this->getStatByName($statRow['stat']); 
                         $epStat->value = $statRow['value'];
                         if($epApt == null){
                             $this->addError("Get Ai getStatByName function call failed: (" . $this->database->errno . ") " . $this->database->error);
                             return null; 
                         }
                         else{
                             //$statList[$epStat->name] = $epStat;
                             array_push($statList, $epStat);
                         }
                    }
                }
                else{
                    $this->addError("Get Ai Stat List failed: (" . $this->database->errno . ") " . $this->database->error);
                    return null; 
                }
                
                $ai = new EPAi($aiRow['name'], $aptitudeList, intval($aiRow['cost']), $skillList, $statList, $aiRow['desc']);
                if ($aiRow['unique'] == "N") $unik = false;
                else $unique = true;
                $ai->unique = $unik;
                //$aiList[$aiRow['name']] = $ai;
                array_push($aiList, $ai);
             }

            return $aiList;
        }
        else{
            $this->addError("Get Ai failed: (" . $this->database->errno . ") " . $this->database->error);
            return null;
        }
    }
    
    
    //==== GEAR ====
    
    function getListGears(){
        $gearList = array();
        
        if($this->database->real_query("SELECT `name`, `description`, `type`, `cost`, `armorKinetic`, `armorEnergy`, `degat`, `armorPene`,`JustFor`, `unique` FROM `Gear`")){
            $gearRes = $this->database->store_result();
            while ($gearRow = $gearRes->fetch_assoc()) {

                if($this->database->real_query("SELECT `gear`, `bonusMalus`, `occur` FROM `GearBonusMalus` WHERE `gear` = '".$this->adjustForSQL($gearRow['name'])."';")){
                    $bonusMalus = $this->database->store_result();
                    $bonusMalusGearList = array();

                    while ($bmRow = $bonusMalus->fetch_assoc()) {
                         $epBonMal = $this->getBonusMalusByName($bmRow['bonusMalus']); 
                         if($epBonMal == null){
                             $this->addError("Get Gear getBonusByName function call failed: (" . $this->database->errno . ") " . $this->database->error);
                             return null; 
                         }
                         else{
                            for($i = 0; $i < $bmRow['occur']; $i++ ){ 
                                array_push($bonusMalusGearList, $epBonMal);
                            }
                         }
                    }
                }
                else{
                    $this->addError("Get Gear BonusmalusList failed: (" . $this->database->errno . ") " . $this->database->error);
                    return null; 
                }

                $gear = new EPGear($gearRow['name'],$gearRow['description'],$gearRow['type'],  intval($gearRow['cost']),$gearRow['armorKinetic'],$gearRow['armorEnergy'],$gearRow['degat'],$gearRow['armorPene'],$bonusMalusGearList,$gearRow['JustFor']);
                if($gearRow['unique'] == "N") $gear->unique = false;
                //$gearList[$gearRow['name']] = $gear;
                array_push($gearList, $gear);
             }

            return $gearList;
        }
        else{
            $this->addError("Get Gear failed: (" . $this->database->errno . ") " . $this->database->error);
            return null;
        }
    }
    
    function getGearByName($name){   
        if($this->database->real_query("SELECT `name`, `description`, `type`, `cost`, `armorKinetic`, `armorEnergy`, `degat`, `armorPene`,`JustFor`, `unique` FROM `Gear` WHERE `name` = '".$this->adjustForSQL($name)."';")){
            $gRes = $this->database->store_result();
            $gearRow = $gRes->fetch_array();
            if($this->database->real_query("SELECT `gear`, `bonusMalus`, `occur` FROM `GearBonusMalus` WHERE `gear` = '".$this->adjustForSQL($gearRow['name'])."';")){
                    $bonusMalus = $this->database->store_result();
                    $bonusMalusGearList = array();

                    while ($bmRow = $bonusMalus->fetch_assoc()) {
                         $epBonMal = $this->getBonusMalusByName($bmRow['bonusMalus']); 
                         if($epBonMal == null){
                             $this->addError("Get Gear getBonusByName function call failed: (" . $this->database->errno . ") " . $this->database->error);
                             return null; 
                         }
                         else{
                            for($i = 0; $i < $bmRow['occur']; $i++ ){ 
                                array_push($bonusMalusGearList, $epBonMal);
                            }
                         }
                    }
                }
                else{
                    $this->addError("Get Gear BonusmalusList failed: (" . $this->database->errno . ") " . $this->database->error);
                    return null; 
                }

                $gear = new EPGear($gearRow['name'],$gearRow['description'],$gearRow['type'],  intval($gearRow['cost']),$gearRow['armorKinetic'],$gearRow['armorEnergy'],$gearRow['degat'],$gearRow['armorPene'],$bonusMalusGearList,$gearRow['JustFor']);
                if($gearRow['unique'] == "N") $gear->unique = false;
                return $gear;
        }
        else{
            $this->addError("Get Gear by name failed: (" . $this->database->errno . ") " . $this->database->error);
            return null;
        }
    }
    
    //==== MORPH =====
    
    function getListMorph(){
       $morphList = array();
        
        
        if($this->database->real_query("SELECT `name`, `description`, `type`, `gender`, `age`, `maxApptitude`, `durablility`, `cpCost`, `creditCost` FROM `morph`")){
            $morphRes = $this->database->store_result();
            while ($morphRow = $morphRes->fetch_assoc()) {
                //Bonus Malus
                if($this->database->real_query("SELECT `morph`, `bonusMalus`, `occur` FROM `MorphBonusMalus` WHERE `morph` = '".$this->adjustForSQL($morphRow['name'])."';")){
                    $bonusMalus = $this->database->store_result();
                    $morphBonusMalusList = array();
        
                    
                    while ($bmRow = $bonusMalus->fetch_assoc()) {                         
                        for($i = 0; $i < $bmRow['occur']; $i++ ){ 
                        	$epBonMal = $this->getBonusMalusByName($bmRow['bonusMalus']); 
	                         if($epBonMal == null){
	                             $this->addError("Get Morph getBonusByName function call failed: (" . $this->database->errno . ") " . $this->database->error);
	                             return null; 
	                         }
                            array_push($morphBonusMalusList, $epBonMal);
                        }
                    }
                }
                else{
                    $this->addError("Get Morph BonusmalusList failed: (" . $this->database->errno . ") " . $this->database->error);
                    return null; 
                }
                //Gear
                if($this->database->real_query("SELECT `morph`, `gear`, `occur` FROM `MorphGears` WHERE `morph` = '".$this->adjustForSQL($morphRow['name'])."';")){
                    $gears = $this->database->store_result();
                    $morphGearsList = array();
        
                    
                    while ($gRow = $gears->fetch_assoc()) {
                         $epGear = $this->getGearByName($gRow['gear']); 
                         if($epGear == null){
                             $this->addError("Get Morph getGearByName function call failed: (" . $this->database->errno . ") " . $this->database->error);
                             return null; 
                         }
                         else{
                            for($i = 0; $i < $gRow['occur']; $i++ ){ 
                                array_push($morphGearsList, $epGear);
                            }
                         }
                    }
                }
                else{
                    $this->addError("Get Morph BonusmalusList failed: (" . $this->database->errno . ") " . $this->database->error);
                    return null; 
                }
                //Traits
                if($this->database->real_query("SELECT `morph`, `trait` FROM `MorphTrait` WHERE `morph` = '".$this->adjustForSQL($morphRow['name'])."';")){
                	
                    $traits = $this->database->store_result();
                    $morphTraitList = array();
                    
                    while ($traitRow = $traits->fetch_assoc()) {
                         $epTraits = $this->getTraitByName($traitRow['trait']); 
                         if($epTraits == null){
                             $this->addError("Get Background getTraitByName function call failed: (" . $this->database->errno . ") " . $this->database->error );
                             return null; 
                         }
                         else{
                             array_push($morphTraitList, $epTraits);
                         }
                    }
                }
                else{
                    $this->addError("Get Background Traits failed: (" . $this->database->errno . ") " . $this->database->error);
                    return null; 
                }
               
                
                $morph = new EPMorph($morphRow['name'],$morphRow['type'],$morphRow['age'],$morphRow['gender'],$morphRow['maxApptitude'],$morphRow['durablility'],$morphRow['cpCost'],$morphTraitList,$morphGearsList,$morphBonusMalusList,$morphRow['description'],"","",  intval($morphRow['creditCost']));
                array_push($morphList, $morph);
                //$morphList[$morphRow['name']] = $morph;
             }

            return $morphList;
        }
        else{
            $this->addError("Get Morph failed: (" . $this->database->errno . ") " . $this->database->error);
            return null;
        }
    }
    
    
    //PSY SLEIGHT
     function getListPsySleights(){
        $psyList = array();
        
        if($this->database->real_query("SELECT `name`, `desc`, `type`, `range`, `duration`, `action`, `strainMod`, `level`,`skillNeeded` FROM `psySleight`")){
            $psyRes = $this->database->store_result();
            while ($psyRow = $psyRes->fetch_assoc()) {

                if($this->database->real_query("SELECT `psy`, `bonusmalus`, `occur` FROM `PsySleightBonusMalus` WHERE `psy` = '".$this->adjustForSQL($psyRow['name'])."';")){
                    $bonusMalus = $this->database->store_result();
                    $bonusMalusPsyList = array();

                    while ($bmRow = $bonusMalus->fetch_assoc()) {
                         $epBonMal = $this->getBonusMalusByName($bmRow['bonusmalus']); 
                         if($epBonMal == null){
                             $this->addError("Get Psy getBonusByName function call failed: (" . $this->database->errno . ") " . $this->database->error);
                             return null; 
                         }
                         else{
                            for($i = 0; $i < $bmRow['occur']; $i++ ){ 
                                array_push($bonusMalusPsyList, $epBonMal);
                            }
                         }
                    }
                }
                else{
                    $this->addError("Get Psy BonusmalusList failed: (" . $this->database->errno . ") " . $this->database->error);
                    return null; 
                }

                $psy = new EPPsySleight($psyRow['name'],$psyRow['desc'],$psyRow['type'],$psyRow['range'],$psyRow['duration'],$psyRow['action'],$psyRow['strainMod'],$psyRow['level'],$bonusMalusPsyList,$psyRow['skillNeeded']);
                array_push($psyList, $psy);
             }

            return $psyList;
        }
        else{
            $this->addError("Get Psy failed: (" . $this->database->errno . ") " . $this->database->error);
            return null;
        }
    }
    
    //ATOM
    function getAtomByName($atomList,$atomName){
     	foreach ($atomList as $a){
            if ($a->name == $atomName){
                return $a;
            }
        }
        return null;
    }
    
    //BOOK 
    function getBookForName($name){
	    if($this->database->real_query("SELECT `book` FROM `AtomBook` WHERE `name` = '".$this->adjustForSQL($name)."';")){
            $bookRes = $this->database->store_result();
            $row = $bookRes->fetch_array();
            if(empty($row)) return null;
            $book = $row['book'];
            return $book;
        }
        else{
            $this->addError("Get Book by name failed: (" . $this->database->errno . ") " . $this->database->error);
            return null;
        }
    }
    
    function getListBook(){
         $nameBook = array();
        
        if($this->database->real_query("SELECT `name`,`book` FROM `AtomBook`")){
            $res = $this->database->store_result();
            
            while ($row = $res->fetch_assoc()) {
                $nameBook[$row['name']] = $row['book'];
            }

            return $nameBook;
        }
        else{
            $this->addError("Get Namebook list failed: (" . $this->database->errno . ") " . $this->database->error);
            return null;
        }
    }
    
    function isNameOnBookList($name){
    	if($this->database->real_query("SELECT `book` FROM `AtomBook` WHERE `name` = '".$this->adjustForSQL($name)."';")){
            $bookRes = $this->database->store_result();
            $row = $bookRes->fetch_array();
            if(empty($row)) return false;
            $book = $row['book'];
            if(empty($book)) return false;
            else return true;
        }
        else{
            $this->addError("Get Book by name failed: (" . $this->database->errno . ") " . $this->database->error);
            return false;
        }
    }
    
    //PAGE
    function getPageForName($name){
	    if($this->database->real_query("SELECT `page` FROM `AtomPage` WHERE `name` = '".$this->adjustForSQL($name)."';")){
            $pageRes = $this->database->store_result();
            $row = $pageRes->fetch_array();
            if(empty($row)) return null;
            $page = $row['page'];
            return $page;
        }
        else{
            $this->addError("Get Page by name failed: (" . $this->database->errno . ") " . $this->database->error);
            return null;
        }
    }
    
    function getListPage(){
         $namePage = array();
        
        if($this->database->real_query("SELECT `name`,`page` FROM `AtomPage`")){
            $res = $this->database->store_result();
            
            while ($row = $res->fetch_assoc()) {
                $namePage[$row['name']] = $row['page'];
            }

            return $namePage;
        }
        else{
            $this->addError("Get Namepagelist failed: (" . $this->database->errno . ") " . $this->database->error);
            return null;
        }
    }
    
    function isNameOnPageList($name){
    	if($this->database->real_query("SELECT `page` FROM `AtomPage` WHERE `name` = '".$this->adjustForSQL($name)."';")){
            $pageRes = $this->database->store_result();
            $row = $pageRes->fetch_array();
            if(empty($row)) return false;
            $page = $row['page'];
            if(empty($page)) return false;
            else return true;
        }
        else{
            $this->addError("Get Page by name failed: (" . $this->database->errno . ") " . $this->database->error);
            return false;
        }
    }
    
    
    
}  
?>
