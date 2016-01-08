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
    private static $database;
    private $configValues;

    function connect(){
        $databasePDO = $this->configValues->getValue('SQLValues','databasePDO');
        $databaseUser = $this->configValues->getValue('SQLValues','databaseUser');
        $databasePassword = $this->configValues->getValue('SQLValues','databasePassword');

        try
        {
            self::$database = new PDO($databasePDO, $databaseUser, $databasePassword);
            if(!self::$database->query("SELECT * FROM `aptitude`"))
                throw new PDOException('Aptitude table in Database is empty!  Database connection Error?');
        }
        catch (PDOException $e)
        {
            error_log('Database connection failed: ');
            error_log('  '.$databasePDO);
            error_log('  '.$e->getMessage());
            error_log('  Current Dir:  '.getcwd());
        }
    }

    function __construct($configPath) {
        $this->errors = array();
        $this->configValues = new EPConfigFile($configPath);
        $this->connect();
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
        $res = self::$database->query("SELECT `name`, `desc`, `type`, `target`, `value`, `tragetForCh`, `typeTarget`, `onCost`, `multiOccur` FROM `bonusMalus`;");
        $res->setFetchMode(PDO::FETCH_ASSOC);
        while ($row = $res->fetch()) {
            $groups = $this->getListGroups($row['name']);
            $bmTypes = $this->getBonusMalusTypes($row['name']);
            $epBonMal = new EPBonusMalus($row['name'],$row['type'],$row['value'],$row['target'],$row['desc'],$groups,$row['onCost'],$row['tragetForCh'], $row['typeTarget'],$bmTypes,$row['multiOccur']);
            //$bmList[$row['name']] = $epBonMal;
            array_push($bmList, $epBonMal);
        }
        return $bmList;
    }
    
    function getBonusMalusByName($name){   
        $res = self::$database->query("SELECT `name`, `desc`, `type`, `target`, `value`, `tragetForCh`, `typeTarget`, `onCost`, `multiOccur` FROM `bonusMalus` WHERE `name` = '".$this->adjustForSQL($name)."';");
        $res->setFetchMode(PDO::FETCH_ASSOC);
        $row = $res->fetch();
        $groups = $this->getListGroups($row['name']);
        $bmTypes = $this->getBonusMalusTypes($row['name']);
        $epBonMal = new EPBonusMalus($row['name'],$row['type'],$row['value'],$row['target'],$row['desc'],$groups,$row['onCost'],$row['tragetForCh'], $row['typeTarget'],$bmTypes,$row['multiOccur']);
        return $epBonMal;
    }
    
    function getBonusMalusTypes($bmName){
        $bmTypeArray = array();
        $res = self::$database->query("SELECT `bmChoices` FROM `BonusMalusTypes` WHERE `bmNameMain` = '".$this->adjustForSQL($bmName)."';");
        $res->setFetchMode(PDO::FETCH_ASSOC);
        while ($row = $res->fetch()) {
            $assocBm = $this->getBonusMalusByName($row['bmChoices']);
            array_push($bmTypeArray, $assocBm);
        }
        return $bmTypeArray;
    }
    
    // ===== INFOS ======
    function getInfosById($id){   
        $res = self::$database->query("SELECT `id`, `data` FROM `infos` WHERE `id` = '".$this->adjustForSQL($id)."';");
        $res->setFetchMode(PDO::FETCH_ASSOC);
        $row = $res->fetch();
        $info = $row['data'];
        return $info;
    }
    
    // ===== TRAIT ======
    
    function getListTraits(){
        $traitList = array();
        $traitRes = self::$database->query("SELECT `name`, `desc`, `side`, `onwhat`, `cpCost` , `level` , `JustFor` FROM `traits`");
        $traitRes->setFetchMode(PDO::FETCH_ASSOC);
        while ($traitRow = $traitRes->fetch()) {
            $bonusMalusTraitList = array();
            $bonusMalus = self::$database->query("SELECT `traitName`, `bonusMalusName`,`occur` FROM `TraitBonusMalus` WHERE `traitName` = '".$this->adjustForSQL($traitRow['name'])."';");
            $bonusMalus->setFetchMode(PDO::FETCH_ASSOC);
            while ($bmRow = $bonusMalus->fetch()) {
                $epBonMal = $this->getBonusMalusByName($bmRow['bonusMalusName']);
                if($epBonMal == null){
                    $this->addError("Get Trait getBonusByName function call failed: (" . $bmRow['bonusMalusName'] . ")");
                    return null;
                }
                else{
                    for($i = 0; $i < $bmRow['occur']; $i++ ){
                        //$bonusMalusTraitList[$bmRow['bonusMalusName']] = $epBonMal;
                        array_push($bonusMalusTraitList, $epBonMal);
                    }
                }
            }
            $trait = new EPTrait($traitRow['name'],$traitRow['desc'],$traitRow['side'],$traitRow['onwhat'],$traitRow['cpCost'],$bonusMalusTraitList,$traitRow['level'],$traitRow['JustFor']);
            array_push($traitList, $trait);
        }
        return $traitList;
    }
    
    function getTraitByName($traitName){
        $bonusMalusTraitList = array();
        $traitRes = self::$database->query("SELECT `name`, `desc`, `side`, `onwhat`, `cpCost`, `level`, `JustFor` FROM `traits` WHERE `name` = '".$this->adjustForSQL($traitName)."';");
        $traitRes->setFetchMode(PDO::FETCH_ASSOC);
        $traitRow = $traitRes->fetch();

        $bonusMalus = self::$database->query("SELECT `traitName`, `bonusMalusName`,`occur` FROM `TraitBonusMalus` WHERE `traitName` = '".$this->adjustForSQL($traitRow['name'])."';");
        $bonusMalus->setFetchMode(PDO::FETCH_ASSOC);
        while ($bmRow = $bonusMalus->fetch()) {
            $epBonMal = $this->getBonusMalusByName($bmRow['bonusMalusName']);
            if($epBonMal == null){
                $this->addError("Get Trait by name getBonusByName function call failed: (" . $bmRow['bonusMalusName'] . ")");
                return null;
            }
            else{
                for($i = 0; $i < $bmRow['occur']; $i++ ){
                    //$bonusMalusTraitList[$bmRow['bonusMalusName']] = $epBonMal;
                    array_push($bonusMalusTraitList, $epBonMal);
                }
            }
        }
        $trait = new EPTrait($traitRow['name'],$traitRow['desc'],$traitRow['side'],$traitRow['onwhat'],$traitRow['cpCost'],$bonusMalusTraitList,$traitRow['level'],$traitRow['JustFor']);
        return $trait;
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
        $res = self::$database->query("SELECT `name`, `description`, `abbreviation` FROM `aptitude`");
        $res->setFetchMode(PDO::FETCH_ASSOC);
        while ($row = $res->fetch()) {
            $groups = $this->getListGroups($row['name']);
            $epAppt = new EPAptitude($row['name'], $row['abbreviation'], $row['description'], $groups,$minValue,$maxValue,$minValue,$absMax);
            //$apt[$epAppt->abbreviation] = $epAppt;
            array_push($apt, $epAppt);
        }
        return $apt;
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
       
       $res = self::$database->query("SELECT `name`, `description`, `abbreviation` FROM `aptitude`");
       $res->setFetchMode(PDO::FETCH_ASSOC);
        while ($row = $res->fetch()) {
            $groups = $this->getListGroups($row['name']);
            $epAppt = new EPAptitude($row['name'], $row['abbreviation'], $row['description'], $groups,$minValue,$maxValue,$minValue,$absMax);
            array_push($apt, $epAppt);
        }
        return $apt;
    }
    
    function getAptitudeByName($aptName,$minValue=0,$maxValue=0){
        if ($minValue == 0){
            $minValue = $this->configValues->getValue('RulesValues', 'AptitudesMinValue');
        }
        if ($maxValue == 0){
            $maxValue = $this->configValues->getValue('RulesValues', 'AptitudesMaxValue');
        }

        $absMax = $this->configValues->getValue('RulesValues', 'AbsoluteAptitudesMaxValue');

        $res = self::$database->query("SELECT `name`, `description`, `abbreviation` FROM `aptitude` WHERE `name` = '".$this->adjustForSQL($aptName)."';");
        $res->setFetchMode(PDO::FETCH_ASSOC);
        $row = $res->fetch();
        $groups = $this->getListGroups($row['name']);
        $epAppt = new EPAptitude($row['name'], $row['abbreviation'], $row['description'], $groups,$minValue,$maxValue,$minValue,$absMax);
        return $epAppt;
    }
    function getAptitudeByAbbreviation($abbrev,$minValue=0,$maxValue=0){
        if ($minValue == 0){
            $minValue = $this->configValues->getValue('RulesValues', 'AptitudesMinValue');
        }
        if ($maxValue == 0){
            $maxValue = $this->configValues->getValue('RulesValues', 'AptitudesMaxValue');
        }
        $absMax = $this->configValues->getValue('RulesValues', 'AbsoluteAptitudesMaxValue');

        $res = self::$database->query("SELECT `name`, `description`, `abbreviation` FROM `aptitude` WHERE `abbreviation` = '".$abbrev."';");
        $res->setFetchMode(PDO::FETCH_ASSOC);
        $row = $res->fetch();
        $groups = $this->getListGroups($row['name']);
        $epAppt = new EPAptitude($row['name'], $row['abbreviation'], $row['description'], $groups,$minValue,$maxValue,$minValue,$absMax);
        return $epAppt;
    }
    
    //=== STATS ====
    
    function getListStats($configValues,&$cc=null){
        $stats = array();
        $res = self::$database->query("SELECT `name`, `description`, `abbreviation` FROM `stat`");
        $res->setFetchMode(PDO::FETCH_ASSOC);
        while ($row = $res->fetch()) {
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
    
    function getStatByName($statName){
        $res = self::$database->query("SELECT `name`, `description`, `abbreviation` FROM `stat` WHERE `name`='".$this->adjustForSQL($statName)."';");
        $res->setFetchMode(PDO::FETCH_ASSOC);
        $row = $res->fetch();
        $groups = $this->getListGroups($row['name']);
        $epStats = new EPStat($row['name'], $row['description'], $row['abbreviation'], $groups);
        return $epStats;
    }
    
    //=== PREFIX ===
    
    function getListPrefix(){
        $prefixes = array();
        $res = self::$database->query("SELECT `prefix` FROM `skillPrefixes`");
        $res->setFetchMode(PDO::FETCH_ASSOC);

        while ($row = $res->fetch()) {
            array_push($prefixes, $row['prefix']);
        }
        return $prefixes;
    }
    
    
    function getAptForPrefix($prefixName){
        $res = self::$database->query("SELECT `linkedApt` FROM `skillPrefixes` WHERE `prefix` = '".$prefixName."';");
        $res->setFetchMode(PDO::FETCH_ASSOC);
        $row = $res->fetch();

        return $row['linkedApt'];
    }
    
    function getTypeForPrefix($prefixName){
        $res = self::$database->query("SELECT `skillType` FROM `skillPrefixes` WHERE `prefix` = '".$prefixName."';");
        $res->setFetchMode(PDO::FETCH_ASSOC);
        $row = $res->fetch();
        return $row['skillType'];
    }
    
    function getPrefixDescription($prefixName){
        $res = self::$database->query("SELECT `desc` FROM `skillPrefixes` WHERE `prefix` = '".$prefixName."';");
        $res->setFetchMode(PDO::FETCH_ASSOC);
        $row = $res->fetch();

        return $row['desc'];
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
        $res = self::$database->query("SELECT `name`, `desc`, `linkedApt`, `prefix`, `skillType`, `defaultable`  FROM skills");
        $res->setFetchMode(PDO::FETCH_ASSOC);

        while ($row = $res->fetch()) {
            $groups = $this->getListGroups($row['name']);
            $epSkills = new EPSkill($row['name'],$row['desc'], $this->getAptByAbreviation($listApt,$row['linkedApt']),$row['skillType'],$row['defaultable'],$row['prefix'],$groups);
            array_push($skills, $epSkills);
        }
        return $skills;
    }
    
    function getSkillByNamePrefix($name,$prefix,$listApt){
        $res = self::$database->query("SELECT `name`, `desc`, `linkedApt`, `prefix`, `skillType`, `defaultable`  FROM skills WHERE `name` = '".$this->adjustForSQL($name)."' AND `prefix` ='".$this->adjustForSQL($prefix)."';");
        $res->setFetchMode(PDO::FETCH_ASSOC);
        $row = $res->fetch();

        $groups = $this->getListGroups($row['name']);
        $epSkills = new EPSkill($row['name'],$row['desc'],$this->getAptByAbreviation($listApt,$row['linkedApt']),$row['skillType'],$row['defaultable'],$row['prefix'],$groups);
        return $epSkills;
    }
    
    function getSkillByName($name,$listApt){
        $res = self::$database->query("SELECT `name`, `desc`, `linkedApt`, `prefix`, `skillType`, `defaultable`  FROM skills WHERE `name` = '".$this->adjustForSQL($name)."';");
        $res->setFetchMode(PDO::FETCH_ASSOC);
        $row = $res->fetch();

        $groups = $this->getListGroups($row['name']);
        if ($row['defaultable']== 'Y'){
            $defaultTable = EPSkill::$DEFAULTABLE;
        }else{
            $defaultTable = EPSkill::$NO_DEFAULTABLE;
        }
        $epSkills = new EPSkill($row['name'],$row['desc'],$this->getAptByAbreviation($listApt,$row['linkedApt']),$row['skillType'],$defaultTable,$row['prefix'],$groups);
        return $epSkills;
    }
    
    // ==== GROUPE =====
    
    function getListGroups($targetName = ""){
        $groupsList = array();
        if(!empty($targetName)){
            $res = self::$database->query("SELECT `groupName`, `targetName` FROM `GroupName` WHERE `targetName` = '".$this->adjustForSQL($targetName)."';");
            $res->setFetchMode(PDO::FETCH_ASSOC);
            while ($groupRow = $res->fetch()) {
                    if($groupRow == null){
                        $this->addError("Get group list  failed: ( ".$targetName." not found in database )");
                        //error_log($this->getLastError());
                        return null;
                    }
                    else{
                    array_push($groupsList, $groupRow['groupName']);
                    }
            }
        }
        else{
            $res = self::$database->query("SELECT DISTINCT `groupName` FROM `GroupName`;");
            $res->setFetchMode(PDO::FETCH_ASSOC);
            while ($groupRow = $res->fetch()) {
                if($groupRow == null){
                    $this->addError("Get group list for Skill  failed: ( groupName not found in database )");
                    // error_log($this->getLastError());
                    return null;
                }
                else{
                    array_push($groupsList, $groupRow['groupName']);
                }
            }
        }
        return $groupsList;
    }
    
    //==== REPUTATION ====
    
    function getListReputation(){
        $reputations = array();
        $res = self::$database->query("SELECT `name`, `description` FROM `reputation`");
        $res->setFetchMode(PDO::FETCH_ASSOC);

        while ($row = $res->fetch()) {
            $groups = $this->getListGroups($row['name']);
            $epReputation = new EPReputation($row['name'],$row['description'],$groups,0,$this->configValues->getValue('RulesValues', 'RepMaxPoint'));
            //$reputations[$row['name']] = $epReputation;
            array_push($reputations, $epReputation);
        }
        return $reputations;
    }
    
    //==== BACKGROUND =====
    
    function getListBackgrounds(){
        $backgroundList = array();
        $bckRes = self::$database->query("SELECT `name`, `description`, `type` FROM `background`");
        $bckRes->setFetchMode(PDO::FETCH_ASSOC);
        while ($bckRow = $bckRes->fetch()) {
            //Bonus Malus
            $backgroundBonusMalusList = array();
            $bonusMalus = self::$database->query("SELECT `background`, `bonusMalus`, `occurrence` FROM `BackgroundBonusMalus` WHERE `background` = '".$this->adjustForSQL($bckRow['name'])."';");
            $bonusMalus->setFetchMode(PDO::FETCH_ASSOC);
            while ($bmRow = $bonusMalus->fetch()) {
                for($i = 0; $i < $bmRow['occurrence']; $i++ ){
                    $epBonMal = $this->getBonusMalusByName($bmRow['bonusMalus']);
                    if($epBonMal == null){
                        $this->addError("Get Background getBonusByName function call failed: (" . $bmRow['bonusMalus'] . ")");
                        return null;
                    }
                    array_push($backgroundBonusMalusList, $epBonMal);
                }

            }
            //Traits
            $backgroundTraitList = array();
            $traits = self::$database->query("SELECT `background`, `trait` FROM `BackgroundTrait` WHERE `background` = '".$this->adjustForSQL($bckRow['name'])."';");
            $traits->setFetchMode(PDO::FETCH_ASSOC);

            while ($traitRow = $traits->fetch()) {
                $epTraits = $this->getTraitByName($traitRow['trait']);
                if($epTraits == null){
                    $this->addError("Get Background getTraitByName function call failed: (" . $traitRow['trait'] . ")");
                    return null;
                }
                else{
                    array_push($backgroundTraitList, $epTraits);
                }
            }
            //limitations
            $bckLimitation = array();
            $limit = self::$database->query("SELECT `background`, `limitationGroup` FROM `BackgroundLimitation` WHERE `background` = '".$this->adjustForSQL($bckRow['name'])."';");
            $limit->setFetchMode(PDO::FETCH_ASSOC);

            while ($limitRow = $limit->fetch()) {
                array_push($bckLimitation, $limitRow['limitationGroup']);
            }

            //obligations
            $bckObligation = array();
            $obl = self::$database->query("SELECT `background`, `obligationGroup` FROM `BackgroundObligation` WHERE `background` = '".$this->adjustForSQL($bckRow['name'])."';");
            $obl->setFetchMode(PDO::FETCH_ASSOC);

            while ($oblRow = $obl->fetch()) {
                array_push($bckObligation, $oblRow['obligationGroup']);
            }
            $bck = new EPBackground($bckRow['name'],$bckRow['description'],$bckRow['type'],$backgroundBonusMalusList,$backgroundTraitList,$bckLimitation,$bckObligation);
            //$backgroundList[$bckRow['name']] = $bck;
            array_push($backgroundList, $bck);
        }
        return $backgroundList;
    }
    
    // ==== AI =====
    function getListAi(){
        $aiList = array();
        $aiRes = self::$database->query("SELECT `name`, `desc`, `cost`, `unique` FROM `ai`");
        $aiRes->setFetchMode(PDO::FETCH_ASSOC);
        while ($aiRow = $aiRes->fetch()) {
            //aptitudes
            $aptitudeList = array();
            $aptRes = self::$database->query("SELECT `ai`, `aptitude`, `value` FROM `AiAptitude` WHERE `ai` = '".$this->adjustForSQL($aiRow['name'])."';");
            $aptRes->setFetchMode(PDO::FETCH_ASSOC);
            while ($aptRow = $aptRes->fetch()) {
                $epApt = $this->getAptitudeByName($aptRow['aptitude']);
                $epApt->value = $aptRow['value'];
                if($epApt == null){
                    $this->addError("Get Ai getAptitudeByName function call failed: (" . $aptRow['aptitude'] . ")");
                    return null;
                }
                else{
                    //$aptitudeList[$epApt->abbreviation] = $epApt;
                    array_push($aptitudeList, $epApt);
                }
            }

            //skills
            $skillList = array();
            $skillRes = self::$database->query("SELECT `ai`, `skillName`, `skillPrefix`, `value` FROM `AiSkill` WHERE `ai` = '".$this->adjustForSQL($aiRow['name'])."';");
            $skillRes->setFetchMode(PDO::FETCH_ASSOC);
            while ($skillRow = $skillRes->fetch()) {
                $epSkill = $this->getSkillByNamePrefix($skillRow['skillName'],$skillRow['skillPrefix'],$aptitudeList);
                $epSkill->baseValue = $skillRow['value'];
                if($epSkill == null){
                    $this->addError("Get Ai getSkillByNamePrefix function call failed: (" . $skillRow['skillName'].", ".$skillRow['skillPrefix'].", ".$aptitudeList . ")");
                    return null;
                }
                else{
                    //$skillList[$epSkill->prefix.$epSkill->name] = $epSkill;
                    array_push($skillList, $epSkill);
                }
            }

            //stats
            $statList = array();
            $statRes = self::$database->query("SELECT `ai`, `stat`, `value` FROM `AiStat` WHERE `ai` = '".$this->adjustForSQL($aiRow['name'])."';");
            $statRes->setFetchMode(PDO::FETCH_ASSOC);
            while ($statRow = $statRes->fetch()) {
                $epStat = $this->getStatByName($statRow['stat']);
                $epStat->value = $statRow['value'];
                if($epApt == null){
                    $this->addError("Get Ai getStatByName function call failed: (" . $statRow['stat'] . ")");
                    return null;
                }
                else{
                    //$statList[$epStat->name] = $epStat;
                    array_push($statList, $epStat);
                }
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
    
    
    //==== GEAR ====
    
    function getListGears(){
        $gearList = array();
        $gearRes = self::$database->query("SELECT `name`, `description`, `type`, `cost`, `armorKinetic`, `armorEnergy`, `degat`, `armorPene`,`JustFor`, `unique` FROM `Gear`");
        $gearRes->setFetchMode(PDO::FETCH_ASSOC);
        while ($gearRow = $gearRes->fetch()) {
            $bonusMalusGearList = array();
             $bonusMalus = self::$database->query("SELECT `gear`, `bonusMalus`, `occur` FROM `GearBonusMalus` WHERE `gear` = '".$this->adjustForSQL($gearRow['name'])."';");
            $bonusMalus->setFetchMode(PDO::FETCH_ASSOC);
            while ($bmRow = $bonusMalus->fetch()) {
                $epBonMal = $this->getBonusMalusByName($bmRow['bonusMalus']);
                if($epBonMal == null){
                    $this->addError("Get Gear getBonusByName function call failed: (" . $bmRow['bonusMalus'] . ")");
                    return null;
                }
                else{
                    for($i = 0; $i < $bmRow['occur']; $i++ ){
                        array_push($bonusMalusGearList, $epBonMal);
                    }
                }
            }
            $gear = new EPGear($gearRow['name'],$gearRow['description'],$gearRow['type'],  intval($gearRow['cost']),$gearRow['armorKinetic'],$gearRow['armorEnergy'],$gearRow['degat'],$gearRow['armorPene'],$bonusMalusGearList,$gearRow['JustFor']);
            if($gearRow['unique'] == "N") $gear->unique = false;
            //$gearList[$gearRow['name']] = $gear;
            array_push($gearList, $gear);
        }
        return $gearList;
    }
    
    function getGearByName($name){
        $bonusMalusGearList = array();

        $gRes = self::$database->query("SELECT `name`, `description`, `type`, `cost`, `armorKinetic`, `armorEnergy`, `degat`, `armorPene`,`JustFor`, `unique` FROM `Gear` WHERE `name` = '".$this->adjustForSQL($name)."';");
        $gRes->setFetchMode(PDO::FETCH_ASSOC);
        $gearRow = $gRes->fetch();

        $bonusMalus = self::$database->query("SELECT `gear`, `bonusMalus`, `occur` FROM `GearBonusMalus` WHERE `gear` = '".$this->adjustForSQL($gearRow['name'])."';");
        $bonusMalus->setFetchMode(PDO::FETCH_ASSOC);
        while ($bmRow = $bonusMalus->fetch()) {
            $epBonMal = $this->getBonusMalusByName($bmRow['bonusMalus']);
            if($epBonMal == null){
                $this->addError("Get Gear getBonusByName function call failed: (" . $bmRow['bonusMalus'] . ")");
                return null;
            }
            else{
                for($i = 0; $i < $bmRow['occur']; $i++ ){
                    array_push($bonusMalusGearList, $epBonMal);
                }
            }
        }

        $gear = new EPGear($gearRow['name'],$gearRow['description'],$gearRow['type'],  intval($gearRow['cost']),$gearRow['armorKinetic'],$gearRow['armorEnergy'],$gearRow['degat'],$gearRow['armorPene'],$bonusMalusGearList,$gearRow['JustFor']);
        if($gearRow['unique'] == "N") $gear->unique = false;
        return $gear;
    }
    
    //==== MORPH =====
    
    function getListMorph(){
        $morphList = array();
        $morphRes = self::$database->query("SELECT `name`, `description`, `type`, `gender`, `age`, `maxApptitude`, `durablility`, `cpCost`, `creditCost` FROM `morph`");
        $morphRes->setFetchMode(PDO::FETCH_ASSOC);
        while ($morphRow = $morphRes->fetch()) {
            //Bonus Malus
            $morphBonusMalusList = array();
            $bonusMalus = self::$database->query("SELECT `morph`, `bonusMalus`, `occur` FROM `MorphBonusMalus` WHERE `morph` = '".$this->adjustForSQL($morphRow['name'])."';");
            $bonusMalus->setFetchMode(PDO::FETCH_ASSOC);
            while ($bmRow = $bonusMalus->fetch()) {
                for($i = 0; $i < $bmRow['occur']; $i++ ){
                    $epBonMal = $this->getBonusMalusByName($bmRow['bonusMalus']);
                    if($epBonMal == null){
                        $this->addError("Get Morph getBonusByName function call failed: (" . $bmRow['bonusMalus'] . ")");
                        return null;
                    }
                    array_push($morphBonusMalusList, $epBonMal);
                }
            }
            //Gear
            $morphGearsList = array();
            $gears = self::$database->query("SELECT `morph`, `gear`, `occur` FROM `MorphGears` WHERE `morph` = '".$this->adjustForSQL($morphRow['name'])."';");
            $gears->setFetchMode(PDO::FETCH_ASSOC);
            while ($gRow = $gears->fetch()) {
                $epGear = $this->getGearByName($gRow['gear']);
                if($epGear == null){
                    $this->addError("Get Morph getGearByName function call failed: (" . $gRow['gear'] . ")");
                    return null;
                }
                else{
                    for($i = 0; $i < $gRow['occur']; $i++ ){
                        array_push($morphGearsList, $epGear);
                    }
                }
            }
            //Traits
            $morphTraitList = array();
            $traits = self::$database->query("SELECT `morph`, `trait` FROM `MorphTrait` WHERE `morph` = '".$this->adjustForSQL($morphRow['name'])."';");
            $traits->setFetchMode(PDO::FETCH_ASSOC);
            while ($traitRow = $traits->fetch()) {
                $epTraits = $this->getTraitByName($traitRow['trait']);
                if($epTraits == null){
                    $this->addError("Get Background getTraitByName function call failed: (" . $traitRow['trait'] . ")");
                    return null;
                }
                else{
                    array_push($morphTraitList, $epTraits);
                }
            }
            $morph = new EPMorph($morphRow['name'],$morphRow['type'],$morphRow['age'],$morphRow['gender'],$morphRow['maxApptitude'],$morphRow['durablility'],$morphRow['cpCost'],$morphTraitList,$morphGearsList,$morphBonusMalusList,$morphRow['description'],"","",  intval($morphRow['creditCost']));
            array_push($morphList, $morph);
            //$morphList[$morphRow['name']] = $morph;
        }
        return $morphList;
    }
    
    
    //PSY SLEIGHT
     function getListPsySleights(){
        $psyList = array();
        $psyRes = self::$database->query("SELECT `name`, `desc`, `type`, `range`, `duration`, `action`, `strainMod`, `level`,`skillNeeded` FROM `psySleight`");
        $psyRes->setFetchMode(PDO::FETCH_ASSOC);
        while ($psyRow = $psyRes->fetch())
        {
            $bonusMalusPsyList = array();
            $bonusMalus = self::$database->query("SELECT `psy`, `bonusmalus`, `occur` FROM `PsySleightBonusMalus` WHERE `psy` = '".$this->adjustForSQL($psyRow['name'])."';");
            $bonusMalus->setFetchMode(PDO::FETCH_ASSOC);
            while ($bmRow = $bonusMalus->fetch())
            {
                $epBonMal = $this->getBonusMalusByName($bmRow['bonusmalus']);
                if($epBonMal == null)
                {
                    $this->addError("Get Psy getBonusByName function call failed: (" . $bmRow['bonusmalus'] . ")");
                    return null;
                }
                else{
                    for($i = 0; $i < $bmRow['occur']; $i++ )
                    {
                        array_push($bonusMalusPsyList, $epBonMal);
                    }
                }
            }
            $psy = new EPPsySleight($psyRow['name'],$psyRow['desc'],$psyRow['type'],$psyRow['range'],$psyRow['duration'],$psyRow['action'],$psyRow['strainMod'],$psyRow['level'],$bonusMalusPsyList,$psyRow['skillNeeded']);
            array_push($psyList, $psy);
            }
        return $psyList;
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
        $res = self::$database->query("SELECT `book` FROM `AtomBook` WHERE `name` = '".$this->adjustForSQL($name)."';");
        $res->setFetchMode(PDO::FETCH_ASSOC);
        $row = $res->fetch();
        if(empty($row)) return null;
        $book = $row['book'];
        return $book;
    }
    
    function getListBook(){
        $nameBook = array();
        $res = self::$database->query("SELECT `name`,`book` FROM `AtomBook`");
        $res->setFetchMode(PDO::FETCH_ASSOC);
        while ($row = $res->fetch()) {
            $nameBook[$row['name']] = $row['book'];
        }
        return $nameBook;
    }
    
    function isNameOnBookList($name){
        $res = self::$database->query("SELECT `book` FROM `AtomBook` WHERE `name` = '".$this->adjustForSQL($name)."';");
        $res->setFetchMode(PDO::FETCH_ASSOC);
        $row = $res->fetch();
        if(empty($row)) return false;
        $book = $row['book'];
        if(empty($book)) return false;
        else return true;
    }
    
    //PAGE
    function getPageForName($name){
        $res = self::$database->query("SELECT `page` FROM `AtomPage` WHERE `name` = '".$this->adjustForSQL($name)."';");
        $res->setFetchMode(PDO::FETCH_ASSOC);
        $row = $res->fetch();
        if(empty($row)) return null;
        $page = $row['page'];
        return $page;
    }
    
    function getListPage(){
        $namePage = array();
        $res = self::$database->query("SELECT `name`,`page` FROM `AtomPage`");
        $res->setFetchMode(PDO::FETCH_ASSOC);
        while ($row = $res->fetch()) {
            $namePage[$row['name']] = $row['page'];
        }
        return $namePage;
    }
    
    function isNameOnPageList($name){
        $res = self::$database->query("SELECT `page` FROM `AtomPage` WHERE `name` = '".$this->adjustForSQL($name)."';");
        $res->setFetchMode(PDO::FETCH_ASSOC);
        $row = $res->fetch();
        if(empty($row)) return false;
        $page = $row['page'];
        if(empty($page)) return false;
        else return true;
    }
    
    
    
}  
?>
