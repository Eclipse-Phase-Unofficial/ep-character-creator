<?php
declare(strict_types=1);

namespace App\Creator;

use \Illuminate\Support\Facades\DB;
use App\Creator\Atoms\EPAi;
use App\Creator\Atoms\EPAptitude;
use App\Creator\Atoms\EPBackground;
use App\Creator\Atoms\EPBonusMalus;
use App\Creator\Atoms\EPGear;
use App\Creator\Atoms\EPMorph;
use App\Creator\Atoms\EPPsySleight;
use App\Creator\Atoms\EPReputation;
use App\Creator\Atoms\EPSkill;
use App\Creator\Atoms\EPStat;
use App\Creator\Atoms\EPTrait;

/**
 * Provide all list of EPAtom object needed by EPCharacterCreator
 *
 * This is what interacts with the database, and actually creates all the PHP Objects from the db.
 *
 * @author Russell Bewley
 * @author Arthur Moore
 */
class EPListProvider {
    public $errors;
    /**
     * @var \PDO
     */
    private static $database;

    function connect()
    {
        self::$database = DB::connection()->getPdo();
        if (!self::$database->query("SELECT * FROM `aptitudes`")) {
            throw new \PDOException('Aptitude table in Database is empty!  Database connection Error?');
        }
    }

    function __construct() {
        $this->errors = array();
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

    function getBonusMalusByName(string $name): EPBonusMalus
    {
//        TODO:  Rename 'traget'ForCh to 'target'ForChoice
        $res = self::$database->query("SELECT `name`, `desc`, `type`, `target`, `value`, `tragetForCh`, `typeTarget`, `onCost`, `multiOccur` FROM `bonusMalus` WHERE `name` = '".$this->adjustForSQL($name)."';");
        $res->setFetchMode(\PDO::FETCH_ASSOC);
        $row = $res->fetch();
        $groups = $this->getListGroups($row['name']);
        $bmTypes = $this->getBonusMalusTypes($row['name']);
        $epBonMal = new EPBonusMalus($row['name'], $row['type'], floatval($row['value']), $row['target'], $row['desc'],
            $groups, $row['onCost'], $row['tragetForCh'], $row['typeTarget'], $bmTypes, intval($row['multiOccur']));
        return $epBonMal;
    }

    function getBonusMalusTypes($bmName){
        $bmTypeArray = array();
        $res = self::$database->query("SELECT `bmChoices` FROM `BonusMalusTypes` WHERE `bmNameMain` = '".$this->adjustForSQL($bmName)."';");
        $res->setFetchMode(\PDO::FETCH_ASSOC);
        while ($row = $res->fetch()) {
            $assocBm = $this->getBonusMalusByName($row['bmChoices']);
            array_push($bmTypeArray, $assocBm);
        }
        return $bmTypeArray;
    }
    
    // ===== INFOS ======
    function getInfosById($id){   
        $res = self::$database->query("SELECT `id`, `data` FROM `infos` WHERE `id` = '".$this->adjustForSQL($id)."';");
        $res->setFetchMode(\PDO::FETCH_ASSOC);
        $row = $res->fetch();
        $info = $row['data'];
        return $info;
    }
    
    // ===== TRAIT ======

    /**
     * @return EPTrait[]
     */
    function getListTraits(): array
    {
        $traitList = array();
        $traitRes = self::$database->query("SELECT `name`, `desc`, `side`, `onwhat`, `cpCost` , `level` , `JustFor` FROM `traits`");
        $traitRes->setFetchMode(\PDO::FETCH_ASSOC);
        while ($traitRow = $traitRes->fetch()) {
            $bonusMalusTraitList = array();
            $bonusMalus = self::$database->query("SELECT `traitName`, `bonusMalusName`,`occur` FROM `bonusMalus_trait` WHERE `traitName` = '".$this->adjustForSQL($traitRow['name'])."';");
            $bonusMalus->setFetchMode(\PDO::FETCH_ASSOC);
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
            $trait = new EPTrait($traitRow['name'], $traitRow['side'], $traitRow['onwhat'], intval($traitRow['cpCost']),
                $traitRow['desc'], $bonusMalusTraitList, intval($traitRow['level']), $traitRow['JustFor']);
            array_push($traitList, $trait);
        }
        return $traitList;
    }

    function getTraitByName(string $traitName): EPTrait
    {
        $bonusMalusTraitList = array();
        $traitRes = self::$database->query("SELECT `name`, `desc`, `side`, `onwhat`, `cpCost`, `level`, `JustFor` FROM `traits` WHERE `name` = '".$this->adjustForSQL($traitName)."';");
        $traitRes->setFetchMode(\PDO::FETCH_ASSOC);
        $traitRow = $traitRes->fetch();

        $bonusMalus = self::$database->query("SELECT `traitName`, `bonusMalusName`,`occur` FROM `bonusMalus_trait` WHERE `traitName` = '".$this->adjustForSQL($traitRow['name'])."';");
        $bonusMalus->setFetchMode(\PDO::FETCH_ASSOC);
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
        $trait = new EPTrait($traitRow['name'], $traitRow['side'], $traitRow['onwhat'], intval($traitRow['cpCost']),
            $traitRow['desc'], $bonusMalusTraitList, intval($traitRow['level']), $traitRow['JustFor']);
        return $trait;
    }
    
    // ==== APTITUDE ======

    /**
     * @return EPAptitude[]
     */
    function getListAptitudes(): array
    {
        $apt = array();

        $res = self::$database->query("SELECT `name`, `description`, `abbreviation` FROM `aptitudes`");
        $res->setFetchMode(\PDO::FETCH_ASSOC);
        while ($row = $res->fetch()) {
            $groups = $this->getListGroups($row['name']);
            $epAppt = new EPAptitude($row['name'], $row['abbreviation'], $row['description'], $groups);
            //$apt[$epAppt->abbreviation] = $epAppt;
            array_push($apt, $epAppt);
        }
        return $apt;
    }

    function getAptitudeByName(string $aptName): EPAptitude
    {
        $res = self::$database->query("SELECT `name`, `description`, `abbreviation` FROM `aptitudes` WHERE `name` = '".$this->adjustForSQL($aptName)."';");
        $res->setFetchMode(\PDO::FETCH_ASSOC);
        $row = $res->fetch();
        $groups = $this->getListGroups($row['name']);
        $epAppt = new EPAptitude($row['name'], $row['abbreviation'], $row['description'], $groups);
        return $epAppt;
    }

    function getAptitudeByAbbreviation(string $abbrev): EPAptitude
    {
        $res = self::$database->query("SELECT `name`, `description`, `abbreviation` FROM `aptitudes` WHERE `abbreviation` = '".$abbrev."';");
        $res->setFetchMode(\PDO::FETCH_ASSOC);
        $row = $res->fetch();
        $groups = $this->getListGroups($row['name']);
        $epAppt = new EPAptitude($row['name'], $row['abbreviation'], $row['description'], $groups);
        return $epAppt;
    }
    
    //=== STATS ====
    //TODO:  Some do and some don't take EPCharacterCreator. None of them should take it, but that's ongoing.

    /**
     * @param EPCharacterCreator|null $cc
     * @return EPStat[]
     */
    function getListStats(?EPCharacterCreator &$cc=null): array
    {
        $stats = array();
        $res = self::$database->query("SELECT `name`, `description`, `abbreviation` FROM `stats`");
        $res->setFetchMode(\PDO::FETCH_ASSOC);
        while ($row = $res->fetch()) {
            $groups = $this->getListGroups($row['name']);
            $epStats = new EPStat($row['name'], $row['description'], $row['abbreviation'], $groups,$cc);
            if (strcmp($epStats->abbreviation,EPStat::$MOXIE) == 0){
                $epStats->value = config('epcc.MoxieStartValue');
            }
            if (strcmp($epStats->abbreviation,EPStat::$SPEED) == 0){
                $epStats->value = config('epcc.SpeedStartValue');
            }
            //$stats[$row['abbreviation']] = $epStats;
            array_push($stats, $epStats);
        }
        return $stats;
    }
    
    //=== PREFIX ===
    
    function getListPrefix(){
        $prefixes = array();
        $res = self::$database->query("SELECT `prefix` FROM `skillPrefixes`");
        $res->setFetchMode(\PDO::FETCH_ASSOC);

        while ($row = $res->fetch()) {
            array_push($prefixes, $row['prefix']);
        }
        return $prefixes;
    }
    
    
    function getAptForPrefix($prefixName){
        $res = self::$database->query("SELECT `linkedApt` FROM `skillPrefixes` WHERE `prefix` = '".$prefixName."';");
        $res->setFetchMode(\PDO::FETCH_ASSOC);
        $row = $res->fetch();

        return $row['linkedApt'];
    }
    
    function getTypeForPrefix($prefixName){
        $res = self::$database->query("SELECT `skillType` FROM `skillPrefixes` WHERE `prefix` = '".$prefixName."';");
        $res->setFetchMode(\PDO::FETCH_ASSOC);
        $row = $res->fetch();
        return $row['skillType'];
    }
    
    function getPrefixDescription($prefixName){
        $res = self::$database->query("SELECT `desc` FROM `skillPrefixes` WHERE `prefix` = '".$prefixName."';");
        $res->setFetchMode(\PDO::FETCH_ASSOC);
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

    /**
     * @param mixed $listApt TODO:  Determine the type here
     * @return EPSkill[]
     */
    function getListSkills($listApt): array
    {
        $skills = array();
        $res = self::$database->query("SELECT `name`, `desc`, `linkedApt`, `prefix`, `skillType`, `defaultable`  FROM skills");
        $res->setFetchMode(\PDO::FETCH_ASSOC);

        while ($row = $res->fetch()) {
            $groups = $this->getListGroups($row['name']);
            $epSkills = new EPSkill($row['name'], $row['desc'], $row['skillType'], $row['defaultable'],
                $this->getAptByAbreviation($listApt, $row['linkedApt']), $row['prefix'], $groups);
            array_push($skills, $epSkills);
        }
        return $skills;
    }

    /**
     * @param string $name
     * @param string $prefix
     * @param mixed  $listApt TODO:  Determine the type here
     * @return EPSkill
     */
    function getSkillByNamePrefix(string $name, string $prefix, $listApt): EPSkill
    {
        $res = self::$database->query("SELECT `name`, `desc`, `linkedApt`, `prefix`, `skillType`, `defaultable`  FROM skills WHERE `name` = '".$this->adjustForSQL($name)."' AND `prefix` ='".$this->adjustForSQL($prefix)."';");
        $res->setFetchMode(\PDO::FETCH_ASSOC);
        $row = $res->fetch();

        $groups = $this->getListGroups($row['name']);
        $epSkills = new EPSkill($row['name'], $row['desc'], $row['skillType'], $row['defaultable'],
            $this->getAptByAbreviation($listApt, $row['linkedApt']), $row['prefix'], $groups);
        return $epSkills;
    }
    
    // ==== GROUPE =====
    
    function getListGroups($targetName = ""){
        $groupsList = array();
        if(!empty($targetName)){
            $res = self::$database->query("SELECT `groupName`, `targetName` FROM `GroupNames` WHERE `targetName` = '".$this->adjustForSQL($targetName)."';");
            $res->setFetchMode(\PDO::FETCH_ASSOC);
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
            $res = self::$database->query("SELECT DISTINCT `groupName` FROM `GroupNames`;");
            $res->setFetchMode(\PDO::FETCH_ASSOC);
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

    /**
     * @return EPReputation[]
     */
    function getListReputation(): array
    {
        $reputations = array();
        $res = self::$database->query("SELECT `name`, `description` FROM `reputations`");
        $res->setFetchMode(\PDO::FETCH_ASSOC);

        while ($row = $res->fetch()) {
            $groups = $this->getListGroups($row['name']);
            $epReputation = new EPReputation($row['name'], $row['description'], $groups);
            //$reputations[$row['name']] = $epReputation;
            array_push($reputations, $epReputation);
        }
        return $reputations;
    }
    
    //==== BACKGROUND =====

    /**
     * @return EPBackground[]
     */
    function getListBackgrounds(): array
    {
        $backgroundList = array();
        $bckRes = self::$database->query("SELECT `name`, `description`, `type` FROM `backgrounds`");
        $bckRes->setFetchMode(\PDO::FETCH_ASSOC);
        while ($bckRow = $bckRes->fetch()) {
            //Bonus Malus
            $backgroundBonusMalusList = array();
            $bonusMalus = self::$database->query("SELECT `background`, `bonusMalus`, `occurrence` FROM `background_bonusMalus` WHERE `background` = '".$this->adjustForSQL($bckRow['name'])."';");
            $bonusMalus->setFetchMode(\PDO::FETCH_ASSOC);
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
            $traits = self::$database->query("SELECT `background`, `trait` FROM `background_trait` WHERE `background` = '".$this->adjustForSQL($bckRow['name'])."';");
            $traits->setFetchMode(\PDO::FETCH_ASSOC);

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
            $limit = self::$database->query("SELECT `background`, `limitationGroup` FROM `BackgroundLimitations` WHERE `background` = '".$this->adjustForSQL($bckRow['name'])."';");
            $limit->setFetchMode(\PDO::FETCH_ASSOC);

            while ($limitRow = $limit->fetch()) {
                array_push($bckLimitation, $limitRow['limitationGroup']);
            }

            $bck = new EPBackground($bckRow['name'], $bckRow['type'], $backgroundBonusMalusList, $backgroundTraitList, $bckLimitation, $bckRow['description']);
            //$backgroundList[$bckRow['name']] = $bck;
            array_push($backgroundList, $bck);
        }
        return $backgroundList;
    }
    
    // ==== AI =====

    /**
     * @return EPAi[]
     */
    function getListAi(): array
    {
        $aiList = array();
        $aiRes = self::$database->query("SELECT `name`, `desc`, `cost` FROM `muses`");
        $aiRes->setFetchMode(\PDO::FETCH_ASSOC);
        while ($aiRow = $aiRes->fetch()) {
            //aptitudes
            $aptitudeList = array();
            $aptRes = self::$database->query("SELECT `muse`, `aptitude`, `value` FROM `aptitude_muse` WHERE `muse` = '" .$this->adjustForSQL($aiRow['name'])."';");
            $aptRes->setFetchMode(\PDO::FETCH_ASSOC);
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
            $skillRes = self::$database->query("SELECT `muse`, `skillName`, `skillPrefix`, `value` FROM `muse_skill` WHERE `muse` = '".$this->adjustForSQL($aiRow['name'])."';");
            $skillRes->setFetchMode(\PDO::FETCH_ASSOC);
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

            $ai = new EPAi($aiRow['name'], $aptitudeList, intval($aiRow['cost']), $skillList, $aiRow['desc']);
            //$aiList[$aiRow['name']] = $ai;
            array_push($aiList, $ai);
        }
        return $aiList;
    }
    
    
    //==== GEAR ====

    /**
     * @return EPGear[]
     */
    function getListGears(): array
    {
        $gearList = array();
        $gearRes = self::$database->query("SELECT `name`, `description`, `type`, `cost`, `armorKinetic`, `armorEnergy`, `degat`, `armorPene`,`JustFor`, `unique` FROM `gear`");
        $gearRes->setFetchMode(\PDO::FETCH_ASSOC);
        while ($gearRow = $gearRes->fetch()) {
            $bonusMalusGearList = array();
             $bonusMalus = self::$database->query("SELECT `gear`, `bonusMalus`, `occur` FROM `bonusMalus_gear` WHERE `gear` = '".$this->adjustForSQL($gearRow['name'])."';");
            $bonusMalus->setFetchMode(\PDO::FETCH_ASSOC);
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
            $gear = new EPGear($gearRow['name'], $gearRow['description'], $gearRow['type'], intval($gearRow['cost']),
                intval($gearRow['armorKinetic']), intval($gearRow['armorEnergy']), $gearRow['degat'], intval($gearRow['armorPene']),
                $bonusMalusGearList, $gearRow['JustFor'], $gearRow['unique'] !== "N");
            //$gearList[$gearRow['name']] = $gear;
            array_push($gearList, $gear);
        }
        return $gearList;
    }

    /**
     * TODO: This can create duplicate gear from what is in the master EPDatabase collection.
     * If one of those is changed then this copy is not affected.
     * @param string $name
     * @return EPGear
     */
    function getGearByName(string $name): EPGear
    {
        $bonusMalusGearList = array();

        $gRes = self::$database->query("SELECT `name`, `description`, `type`, `cost`, `armorKinetic`, `armorEnergy`, `degat`, `armorPene`,`JustFor`, `unique` FROM `gear` WHERE `name` = '".$this->adjustForSQL($name)."';");
        $gRes->setFetchMode(\PDO::FETCH_ASSOC);
        $gearRow = $gRes->fetch();

        $bonusMalus = self::$database->query("SELECT `gear`, `bonusMalus`, `occur` FROM `bonusMalus_gear` WHERE `gear` = '".$this->adjustForSQL($gearRow['name'])."';");
        $bonusMalus->setFetchMode(\PDO::FETCH_ASSOC);
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

        $gear = new EPGear($gearRow['name'], $gearRow['description'], $gearRow['type'], intval($gearRow['cost']),
            intval($gearRow['armorKinetic']), intval($gearRow['armorEnergy']), $gearRow['degat'],
            intval($gearRow['armorPene']), $bonusMalusGearList, $gearRow['JustFor'], $gearRow['unique'] !== "N");
        return $gear;
    }
    
    //==== MORPH =====

    /**
     * TODO:  Remove Gender and age from the database.  They are no longer used. Also change DB Types to ints where appropriate
     * @return EPMorph[]
     */
    function getListMorph(): array
    {
        $morphList = array();
        $morphRes = self::$database->query("SELECT `name`, `description`, `type`, `gender`, `age`, `maxApptitude`, `durablility`, `cpCost`, `creditCost` FROM `morphs`");
        $morphRes->setFetchMode(\PDO::FETCH_ASSOC);
        while ($morphRow = $morphRes->fetch()) {
            //Bonus Malus
            $morphBonusMalusList = array();
            $bonusMalus = self::$database->query("SELECT `morph`, `bonusMalus`, `occur` FROM `bonusMalus_morph` WHERE `morph` = '".$this->adjustForSQL($morphRow['name'])."';");
            $bonusMalus->setFetchMode(\PDO::FETCH_ASSOC);
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
            $gears = self::$database->query("SELECT `morph`, `gear`, `occur` FROM `gear_morph` WHERE `morph` = '".$this->adjustForSQL($morphRow['name'])."';");
            $gears->setFetchMode(\PDO::FETCH_ASSOC);
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
            $traits = self::$database->query("SELECT `morph`, `trait` FROM `morph_trait` WHERE `morph` = '".$this->adjustForSQL($morphRow['name'])."';");
            $traits->setFetchMode(\PDO::FETCH_ASSOC);
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
            $morph = new EPMorph($morphRow['name'], $morphRow['type'], intval($morphRow['maxApptitude']),
                intval($morphRow['durablility']), intval($morphRow['cpCost']), $morphTraitList, $morphGearsList, $morphBonusMalusList,
                $morphRow['description'], intval($morphRow['creditCost']));
            array_push($morphList, $morph);
            //$morphList[$morphRow['name']] = $morph;
        }
        return $morphList;
    }
    
    
    //PSY SLEIGHT

    /**
     * @return EPPsySleight[]
     */
    function getListPsySleights(): array
     {
        $psyList = array();
        $psyRes = self::$database->query("SELECT `name`, `desc`, `type`, `range`, `duration`, `action`, `strainMod`, `level`,`skillNeeded` FROM `psySleights`");
        $psyRes->setFetchMode(\PDO::FETCH_ASSOC);
        while ($psyRow = $psyRes->fetch())
        {
            $bonusMalusPsyList = array();
            $bonusMalus = self::$database->query("SELECT `psy`, `bonusmalus`, `occur` FROM `bonusMalus_psySleight` WHERE `psy` = '".$this->adjustForSQL($psyRow['name'])."';");
            $bonusMalus->setFetchMode(\PDO::FETCH_ASSOC);
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

    //BOOK 
    function getBookForName(string $name): ?string
    {
        $res = self::$database->query("SELECT `book` FROM `AtomBook` WHERE `name` = '".$this->adjustForSQL($name)."';");
        $res->setFetchMode(\PDO::FETCH_ASSOC);
        $row = $res->fetch();
        if(empty($row)) return null;
        $book = $row['book'];
        return $book;
    }
    
    //PAGE
    function getPageForName(string $name): ?string
    {
        $res = self::$database->query("SELECT `page` FROM `AtomPage` WHERE `name` = '".$this->adjustForSQL($name)."';");
        $res->setFetchMode(\PDO::FETCH_ASSOC);
        $row = $res->fetch();
        if(empty($row)) return null;
        $page = $row['page'];
        return $page;
    }
}  
