<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>EP Test</title>
    </head>
    <body>
        <?php
            // Report all PHP errors (see changelog)
            error_reporting(E_ALL);
            error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
            ini_set('display_errors', '1');
            
            //$cibleTest = 'Delete';
            //$cibleTest = 'Simplex';
            $cibleTest = 'Jigé';
            if (strcmp($cibleTest,'Jigé') == 0){
                //===================   Jige Test   ============================
                require_once 'EPCharacterCreator.php';
                require_once 'EPAptitude.php';
                require_once 'EPStat.php';
                require_once 'EPTestTools.php';
                require_once 'EPConfigFile.php';
                require_once 'EPPsySleight.php';
                
                $testFailed = array();

                $cc = new EPCharacterCreator('.\config.ini');
                $tools = new EPTestTools();
                //===================== EPCharacterCreator TEST =========================
                //-------------- init() -------------
                if ($cc->aptitudePoints != $cc->configValues->getValue('RulesValues','AptitudesPoint') - 35){
                    array_push($testFailed, '-- Aptitudes start points points FAILL!</br>');
                }   
                if ($cc->creationPoints != $cc->configValues->getValue('RulesValues','CreationPoint')){
                    array_push($testFailed, '-- Creation start points FAILL!</br>');
                }    
                if ($cc->getCredit() != $cc->configValues->getValue('RulesValues','CreditStart')){
                    array_push($testFailed, '-- Credits start FAILL!</br>');
                }                 
                if ($cc->getReputationPoints() != $cc->configValues->getValue('RulesValues','RepStart')){
                    array_push($testFailed, '-- Reputations start points FAILL!</br>');
                }   
                //---------------- aptitudes ----------------
                $res = $cc->setAptitudeValue('SOM', 25);
                if ($cc->getAptitudeByAbbreviation('SOM')->getValue() != 25){
                    array_push($testFailed, '-- setAptitudeValue(SOM,25) FAILL!</br>');
                }
                $aptpoint = $cc->configValues->getValue('RulesValues','AptitudesPoint') - 55;
                if ($cc->aptitudePoints != $aptpoint){
                    array_push($testFailed, '-- setAptitudeValue(SOM,25) Apt Point Rest FAILL!</br>');
                }
                $res = $cc->setAptitudeValue('SOM', 35);
                if ($res == true){
                    array_push($testFailed, ' -- setAptitudeValue(SOM,35) FAILL !</br>');
                }
                if ($cc->aptitudePoints != $aptpoint){
                    array_push($testFailed, '-- setAptitudeValue(SOM,25) Apt Point Rest 2 FAILL!</br>');
                } 
                $res = $cc->setAptitudeValue('COG', 15);
                $res = $cc->setAptitudeValue('COO', 15);
                $res = $cc->setAptitudeValue('INT', 15);
                $res = $cc->setAptitudeValue('REF', 15);
                $res = $cc->setAptitudeValue('SAV', 15);
                $res = $cc->setAptitudeValue('SOM', 15);
                $res = $cc->setAptitudeValue('WIL', 15);
                if ($cc->aptitudePoints != 0){
                    array_push($testFailed, ' -- setAptitudeValue() all 15 FAILL !</br>');
                }
                $res = $cc->setAptitudeValue('WIL', 20);
                if ($cc->aptitudePoints != 0){
                    array_push($testFailed, ' -- setAptitudeValue() all 15 FAILL !</br>');
                }    
                if ($cc->creationPoints != 950){
                    array_push($testFailed, ' -- setAptitudeValue() over 5 FAILL !</br>');
                }
                $res = $cc->setAptitudeValue('WIL', 10);
                if ($cc->aptitudePoints != 5){
                    array_push($testFailed, ' -- setAptitudeValue() down to 10 FAILL !</br>');
                }    
                if ($cc->creationPoints != 1000){
                    array_push($testFailed, ' -- setAptitudeValue() down to 10 FAILL !</br>');
                }
                $res = $cc->setAptitudeValue('SOM', 20);
                if ($cc->aptitudePoints != 0){
                    array_push($testFailed, ' -- setAptitudeValue() up to 20 FAILL !</br>');
                }
                //------------------- stats ----------------------
                $stats = $cc->character->ego->stats;                
                if (is_array($stats)){
                    foreach ($stats as $sts){
                        if (!($sts->value == 1 || $sts->value == 0)){
                            array_push($testFailed, ' -- Stat base value FAILL !</br>');
                        }
                    }   
                }
                $res = $cc->setStat(EPStat::$MOXIE, 2);
                if ($res == false){
                    array_push($testFailed, ' -- setStat() 1 to 2 FAILL !</br>');
                    array_push($testFailed, ' -- -- '.$cc->getLastError().'</br>');
                }else{
                    if ($cc->getStatByAbbreviation(EPStat::$MOXIE)->getValue() != 2){
                        array_push($testFailed, ' -- getStat() == 2 FAILL !</br>');
                    }
                }
                if ($cc->creationPoints != 985){                    
                    array_push($testFailed, ' -- setStat() 1 to 2 FAILL !</br>');
                }               
                $res = $cc->setStat(EPStat::$MOXIE, 9);
                if ($res == true){
                    array_push($testFailed, ' -- setStat() 2 to 9 FAILL !</br>');
                }
                $res = $cc->setStat(EPStat::$MOXIE, 1);
                if ($cc->creationPoints != 1000){
                    array_push($testFailed, ' -- setStat() 2 to 1 FAILL !</br>');
                }else{
                    if ($cc->getStatByAbbreviation(EPStat::$MOXIE)->getValue() != 1){
                        array_push($testFailed, ' -- getStat() == 1 FAILL !</br>');
                    }
                }    
                //------------- skills --------------------
                $res = $cc->setSkillValue('Blades', 0, '');
                if ($cc->getRealCPCostForSkill($cc->getSkillByName('Blades')) != 0){
                    array_push($testFailed, ' -- setSkillValue() 15 to 0 FAILL !</br>');
                }  
                $res = $cc->setSkillValue('Blades', 40, '');
                if ($cc->getRealCPCostForSkill($cc->getSkillByName('Blades')) != 40){
                    array_push($testFailed, ' -- setSkillValue() 0 to 40 FAILL !</br>');
                } 
                $res = $cc->setSkillValue('Blades', 60, '');
                if ($cc->getRealCPCostForSkill($cc->getSkillByName('Blades')) != 80){
                    array_push($testFailed, ' -- setSkillValue() 0 to 60 FAILL ! ('.$cc->getRealCPCostForSkill($cc->getSkillByName('Blades')).') </br>');
                }
                //--------------- reputation ----------------------
                $res = $cc->setReputation('@-Rep', 40);
                if ($cc->getReputationPoints() != 10){
                    array_push($testFailed, ' -- setReputation() @-Rep to 40 FAILL !</br>');
                }  
                $res = $cc->setReputation('G-Rep', 60);
                if ($cc->getReputationPoints() != 0){
                    array_push($testFailed, ' -- setReputation() G-Rep to 60 FAILL !</br>');
                }  
                if ($cc->creationPoints != 915){
                    array_push($testFailed, ' -- setReputation() G-Rep to 60 for CP FAILL ! ('.$cc->creationPoints.') </br>');
                }
                $res = $cc->setReputation('G-Rep', 0);
                if ($cc->getReputationPoints() != 10){
                    array_push($testFailed, ' -- setReputation() G-Rep 60 to 0 FAILL !</br>');
                }  
                if ($cc->creationPoints != 920){
                    array_push($testFailed, ' -- setReputation() G-Rep 60 to 0 for CP FAILL ! ('.$cc->creationPoints.') </br>');
                }
                $cc->setBackground($cc->getBackgroundByName('Hyperelite'));
                
                $bmp = new EPBonusMalus('SOM10',  EPBonusMalus::$ON_APTITUDE,10,'SOM','descript',array());       
                $skl = new EPPsySleight('Titan Strength','descript', EPPsySleight::$ACTIVE_PSY,  EPPsySleight::$RANGE_SELF,  EPPsySleight::$DURATION_CONSTANT,  EPPsySleight::$ACTION_AUTOMATIC,0,array($bmp));
                array_push($cc->character->ego->psySleights, $skl);
                
                $bmp = new EPBonusMalus('Blade+20',EPBonusMalus::$ON_SKILL,20,':Blades','descript',array());
                $skl = new EPPsySleight('Blades velocity','descript', EPPsySleight::$ACTIVE_PSY,  EPPsySleight::$RANGE_SELF,  EPPsySleight::$DURATION_CONSTANT,  EPPsySleight::$ACTION_AUTOMATIC,0,array($bmp));
                array_push($cc->character->ego->psySleights, $skl);
                
                $bmp = new EPBonusMalus('Wepons +5',EPBonusMalus::$ON_SKILL,5,'','descript',array('Weapons'));
                $skl = new EPPsySleight('Weapons loving','descript', EPPsySleight::$ACTIVE_PSY,  EPPsySleight::$RANGE_SELF,  EPPsySleight::$DURATION_CONSTANT,  EPPsySleight::$ACTION_AUTOMATIC,0,array($bmp));
                array_push($cc->character->ego->psySleights, $skl);
                $cc->adjustWithPsyBonus(); 
                
                $cc->addMotivation('Motiv_001 sample');
                $cc->addMotivation('Motiv_002 sample');
                $cc->addMotivation('Motiv_003 sample');
                $cc->addMotivation('Motiv_001 sample');
                $cc->removeMotivation('Motiv_002 sample');
                $cc->addMotivation('Motiv_004 sample');
                $cc->removeMotivation('Motiv_004 sample');
                $cc->removeMotivation('Motiv_002 sample');
                
                //--------------- traits ----------------------
               
                $cc->addTrait($cc->getTraitByName('Adaptability I'));
                $cc->removeTrait($cc->getTraitByName('Adaptability I'));
                $cc->addTrait($cc->getTraitByName('Allies'));
                $cc->addTrait($cc->getTraitByName('Ambidextrous'));
                $cc->addTrait($cc->getTraitByName('Brave'));
                $cc->addTrait($cc->getTraitByName('Danger Sense'));
                                
                $cc->addMorph($cc->getMorphByName('Exalts'));
                $cc->addMorph($cc->getMorphByName('Ghosts'));
                $cc->removeMorph($cc->getMorphByName('Exalts'));
                $cc->addMorph($cc->getMorphByName('Exalts'));

                $cc->addTrait($cc->getTraitByName('Limber I'),$cc->getCurrentMorphsByName('Ghosts'));
                $cc->addTrait($cc->getTraitByName('Second Skin'),$cc->getCurrentMorphsByName('Ghosts'));
                $cc->addTrait($cc->getTraitByName('Zoosemiotics'),$cc->getCurrentMorphsByName('Ghosts'));
                $cc->addTrait($cc->getTraitByName('Social stigma morph'),$cc->getCurrentMorphsByName('Ghosts'));               
                $cc->addTrait($cc->getTraitByName('Uncanny Valley morph'),$cc->getCurrentMorphsByName('Exalts'));
                $cc->addTrait($cc->getTraitByName('Rapid Healer'),$cc->getCurrentMorphsByName('Exalts'));
                
                echo '-------------------------------------------------------</br>';
                echo 'After Add Traits On Morphs : </br>';
                if (is_array($cc->character->morphs)){
                    foreach ($cc->character->morphs as $m){
                        echo 'Morph : '.$m->name.'</br>';
                        $tools->showTraits($m->traits);
                    }
                }                
                echo '-------------------------------------------------------</br>';
                
                $cc->removeTrait($cc->getTraitByName('Limber I'),$cc->getCurrentMorphsByName('Ghosts'));
                $cc->removeTrait($cc->getTraitByName('Zoosemiotics'),$cc->getCurrentMorphsByName('Ghosts'));
                
                echo '-------------------------------------------------------</br>';
                echo 'After Remove Traits On Morphs : </br>';
                if (is_array($cc->character->morphs)){
                    foreach ($cc->character->morphs as $m){
                        echo 'Morph : '.$m->name.'</br>';
                        $tools->showTraits($m->traits);
                    }
                }                
                echo '-------------------------------------------------------</br>';                
                
                $tools->showAptitudes($cc->character->ego->aptitudes);
                $tools->showStats($cc->character->ego->stats); 
                $tools->showReputations($cc->character->ego->reputations);  
                $tools->showSkills($cc->getActiveSkills());
                $tools->showSkills($cc->getKnowledgeSkills());
                $tools->showGeneralValues($cc);
                $tools->showBackgrounds($cc->getBackgrounds());
                $tools->showMorphs($cc->morphs);
                $tools->showMorphs($cc->character->morphs);
                $tools->showGears($cc->gears);
                $tools->showMotivations($cc->character->ego->motivations);
                $tools->showTraits($cc->traits);
                echo 'Ego traits : </br>';
                $tools->showTraits($cc->character->ego->traits);
                
                if (is_array($cc->character->morphs)){
                    foreach ($cc->character->morphs as $m){
                        echo 'Morph : '.$m->name.'</br>';
                        $tools->showTraits($m->traits);
                    }
                }
                
                $tools->showValidations($cc);
                
                echo '</br>';
                echo 'List of fails:</br>';
                echo '===============</br>';                
                foreach ($testFailed as $fail){
                        echo $fail;
                }    
                echo '</br>';

                echo 'List of Costs:</br>';
                echo '===============</br>';                
                echo 'Cost Aptitudes   = '.$cc->getCostForApts().'</br>';
                echo 'Cost Stats       = '.$cc->getCostForStats().'</br>';
                echo 'Cost Traits      = '.$cc->getCostForTraits().'</br>';
                echo 'Cost Morphs      = '.$cc->getCostForMorphs().'</br>';
                echo 'Cost Reputations = '.$cc->getCostForReputation().'</br>';
                echo 'Cost Skills      = '.$cc->getCostForSkills().'</br>';
                echo '</br>';
                echo '===============</br>';
                echo 'Rest need for Actives skills = '.$cc->getActiveRestNeed().'</br>';
                echo 'Rest need for Knowledges skills = '.$cc->getKnowledgeRestNeed().'</br>';
                echo '===============</br>';
                echo '</br>';
                echo '===============</br>';
                echo 'Clubs = '.$cc->getSkillByName('Clubs')->getValue().'</br>';
                $cc->setSkillValue('Clubs', 50);
                echo 'Set Clubs to 50 </br>';
                echo 'Clubs = '.$cc->getSkillByName('Clubs')->getValue().'</br>';
                echo 'Rest need for Actives skills = '.$cc->getActiveRestNeed().'</br>';
                echo '===============</br>';
                echo '</br>';
                echo 'List of errors:</br>';
                echo '===============</br>';
                foreach ($cc->getErrorList() as $e) {
                    echo $e->typeError.' : '.$e->textError.'</br>';
                    echo $e->getLigneNumber().'</br>';
                    echo $e->getTextOnly().'</br>';
                }
                unset($cc);
            }
            
            if (strcmp($cibleTest,'Simplex') == 0){
                //===================  Simplex Test ================================                       
                require_once 'EPPersistentDataManager.php';
                require_once 'EPListProvider.php';
                require_once 'EPConfigFile.php';

                $persistManager = new EPPersistentDataManager('./config.ini');
                $listProvider = new EPListProvider('./config.ini');
                $configValues = new EPConfigFile('./config.ini');         
                
                //echo infos by id 
                $infos = $listProvider->getInfosById("backgrounds");
                echo "<br>";
                echo "Infos : <br>";
                echo $infos;
                
                //Add Aptitudes
                echo "<br><br>ADDING APTITUDE------------------------------------- <br><br>";
                $ap = array();
                $ap[0] =  new EPAptitude('Cognition',  EPAptitude::$COGNITION,"",array("Cognition group","Apt test group 2"));
                $ap[1] =  new EPAptitude('Coordination',  EPAptitude::$COORDINATION);
                $ap[2] =  new EPAptitude('Intuition',  EPAptitude::$INTUITION);
                $ap[3] =  new EPAptitude('Reflex',  EPAptitude::$REFLEXS);
                $ap[4] =  new EPAptitude('Savvy',  EPAptitude::$SAVVY);
                $ap[5] =  new EPAptitude('Somatic',  EPAptitude::$SOMATICS);
                $ap[6] =  new EPAptitude('Willpower',  EPAptitude::$WILLPOWER);
                foreach ($ap as $a){
                    if(!$persistManager->persistAptitude($a)){
                       echo $persistManager->getLastError();
                       echo "<br>";
                    } 
                    else{
                         echo "DONE ! <br>";
                    }                    
                }
                //Echo Aptitude
                echo "<br><br>APTITUDE LIST------------------------------------------ <br><br>";
                $aptitudeList = $listProvider->getListAptitudes($configValues->getValue('RulesValues','AptitudesMinValue'),
                                                      $configValues->getValue('RulesValues','AptitudesMaxValue'));
                if($aptitudeList == null){
                    echo $listProvider->getLastError();
                    echo "<br>";
                }
                else{
                    foreach($aptitudeList as $m){
                        echo $m->name." Desc : ".$m->description." Abbr. : ".$m->abbreviation."<br>";
                        echo "Aptitudes on groups : <br>";
                        $groupsList = $m->groups;
                        foreach($groupsList as $g){
                           echo "-> ".$g;
                           echo '<br>';
                        }
                        echo '<br>';
                    }
                }
                
                //Add  skill
                echo "<br><br>ADDING SKILL  --------------------------------------------- <br><br>";
                $sk = array();
                $sk[0] = new EPSkill("Music",
                                  "Concern all about music theory",
                                  $aptitudeList[EPAptitude::$INTUITION],
                                  EPSkill::$KNOWLEDGE_SKILL_TYPE,
                                  EPSkill::$NO_DEFAULTABLE,
                                  "Art",
                                  array("Musician Base Knowledge","Rocker Pack","Priest base"));
                $sk[1] = new EPSkill("Blades",
                                  "Hand weapon",
                                  $aptitudeList[EPAptitude::$SOMATICS],
                                  EPSkill::$ACTIVE_SKILL_TYPE,
                                  EPSkill::$NO_DEFAULTABLE,
                                  "",
                                  array("Weapons","Weapon Melee"));
                $sk[2] = new EPSkill("Beam Weapons",
                                  "All energy weapons",
                                  $aptitudeList[EPAptitude::$COORDINATION],
                                  EPSkill::$ACTIVE_SKILL_TYPE,
                                  EPSkill::$NO_DEFAULTABLE,
                                  "",
                                  array("Weapons","Weapon Ranged"));
                $sk[3] = new EPSkill("Climbing",
                                  "Spider man",
                                  $aptitudeList[EPAptitude::$SOMATICS],
                                  EPSkill::$ACTIVE_SKILL_TYPE,
                                  EPSkill::$NO_DEFAULTABLE,
                                  "",
                                  array("Physical","Acrobatic"));
                foreach ($sk as $s){
                    if(!$persistManager->persistSkill($s)){
                       echo $persistManager->getLastError();
                       echo "<br>";
                    } 
                    else{
                         echo "DONE ! <br>";
                    }                    
                }
                 //Echo skill list
                $skillList = $listProvider->getListSkills($aptitudeList);
                if($skillList == null){
                      echo $listProvider->getLastError();
                      echo "<br>";
                }
                else{
                    echo "<br><br>SKILL LIST --------------------------------------------- <br><br>";
                    foreach($skillList as $m){
                        echo $m->prefix.":".$m->name." - ".$m->description." - Apt : ".$m->linkedApt->abbreviation." - Skill Type : ".$m->skillType." - Defaultable : ".$m->defaultable."<br>";
                        echo "Skill on groups : <br>";
                        $groupsList = $m->groups;
                        foreach($groupsList as $g){
                           echo "-> ".$g;
                           echo '<br>';
                        }
                    }
                }
                //Add  bonus Malus
                echo "<br><br>ADDING BONUS MALUS  --------------------------------------------- <br><br>";
                $bm = array();
                $bm[0] = new EPBonusMalus("COG 5 Bonus", EPBonusMalus::$ON_APTITUDE, 5);
                $bm[1] = new EPBonusMalus("Free Networking 20 Bonus", EPBonusMalus::$ON_SKILL_PREFIX, 20,"Networking","",array("Hacker pack","Scientist base tech"));
                $bm[2] = new EPBonusMalus("Circadian Regulation", EPBonusMalus::$DESCRIPTIVE_ONLY, 0,"","The character dreams constantly while asleep and can both fall asleep and wake up almost instantly. In addition, the character can easily and with no ill-effects shift to a 2-day cycle, where they are awake for 44 hours and sleep for 4.");
                foreach ($bm as $b){
                    if(!$persistManager->persistBonusMalus($b)){
                       echo $persistManager->getLastError();
                       echo "<br>";
                    } 
                    else{
                         echo "DONE ! <br>";
                    }                    
                }             
                //Echo Bonnus Malus List
                echo "<br><br>BONUS MALUS LIST  --------------------------------------------- <br><br>";
                $bonusMalusList = $listProvider->getListBonusMalus();
                if($bonusMalusList == null){
                    echo $listProvider->getLastError();
                    echo "<br>";
                }
                else{
                 foreach($bonusMalusList as $m){
                     echo $m->name." - ".$m->description." - Type : ".$m->bonusMalusType." - for target : ".$m->forTargetNamed." - value : ".$m->value."<br>";
                     echo "Bonus Malus on groups : <br>";
                        $groupsList = $m->groups;
                        foreach($groupsList as $g){
                           echo "-> ".$g;
                           echo '<br>';
                        }
                 }
                }
                //Add Trait
                echo "<br><br>ADDING TRAIT  --------------------------------------------- <br><br>";
                $bm1_1 = new EPBonusMalus("COG 5 Bonus", EPBonusMalus::$ON_APTITUDE, 5);
                $bm1_2 = new EPBonusMalus("COG 5 Bonus", EPBonusMalus::$ON_APTITUDE, 5);
                $bm1_3 = new EPBonusMalus("COG 5 Bonus", EPBonusMalus::$ON_APTITUDE, 5);
                $bm2_1 = new EPBonusMalus("Free Networking 20 Bonus", EPBonusMalus::$ON_SKILL_PREFIX, 20,"Networking","",array("Hacker pack","Scientist base tech"));
                $bm2_2 = new EPBonusMalus("Free Networking 20 Bonus", EPBonusMalus::$ON_SKILL_PREFIX, 20,"Networking","",array("Hacker pack","Scientist base tech"));
                $bm3_1 = new EPBonusMalus("Circadian Regulation", EPBonusMalus::$DESCRIPTIVE_ONLY, 0,"","The character dreams constantly while asleep and can both fall asleep and wake up almost instantly. In addition, the character can easily and with no ill-effects shift to a 2-day cycle, where they are awake for 44 hours and sleep for 4.");
                $bonusMalusArray = array($bm1_1,$bm1_2,$bm1_3,$bm2_1,$bm2_2,$bm3_1);
                array_merge($bonusMalusArray,$listProvider->getListBonusMalus());
                $tr = array();
                $tr[0] = new EPTrait("Test Trait 1", "Not existing in the rule, just for test", EPTrait::$POSITIVE_TRAIT, EPTrait::$MORPH_TRAIT, 10, $bonusMalusArray);
                $tr[1] = new EPTrait("Test Trait 2", "Not existing in the rule, just for test", EPTrait::$POSITIVE_TRAIT, EPTrait::$MORPH_TRAIT, 10, $bonusMalusArray);
                foreach ($tr as $t){
                    if(!$persistManager->persistTrait($t)){
                       echo $persistManager->getLastError();
                       echo "<br>";
                    } 
                    else{
                         echo "DONE ! <br>";
                    }                    
                }             
                //Echo Trait list
                echo "<br><br>TRAIT LIST------------------------------------------ <br><br>";
                $traitsList = $listProvider->getListTraits();
                if($traitsList == null){
                    echo $listProvider->getLastError();
                    echo "<br>";
                }
                else{
                    foreach($traitsList as $m){
                        echo $m->name." : ".$m->description." Types : ".$m->traitPosNeg." On : ".$m->traitEgoMorph." cp cost : ".$m->cpCost;
                        echo '<br>';
                        $malusBonus = $m->bonusMalus;
                        echo "With BonusMalus : <br>";
                        foreach($malusBonus as $o){       
                           echo $o->name." - ".$o->description." - Type : ".$o->bonusMalusType." - for target : ".$o->forTargetNamed." - value : ".$o->value."<br>";
                        }
                    }
                }
                //Echo Group list
                echo "<br><br>GROUP LIST------------------------------------------ <br><br>";
                $groupsList = $listProvider->getListGroups();
                if($groupsList == null){
                   echo $listProvider->getLastError();
                   echo "<br>";
                }
                else{
                   foreach($groupsList as $m){
                       echo $m."<br>";
                   }
                }  
                //Add Stats
                echo "<br><br>ADDING STATS------------------------------------- <br><br>";
                $st = array();
                $st[0] =  new EPStat('Moxie', "", EPStat::$MOXIE,array("stat test group 1","stat test group 2"));
                $st[1] =  new EPStat('Trauma threshold',"",  EPStat::$TRAUMATHRESHOLD);
                $st[2] =  new EPStat('Insanity rating',"",  EPStat::$INSANITYRATING);
                $st[3] =  new EPStat('Lucidity',"",  EPStat::$LUCIDITY);
                $st[4] =  new EPStat('Death rating', "", EPStat::$DEATHRATING);
                $st[5] =  new EPStat('Wound threshold', "", EPStat::$WOUNDTHRESHOLD);
                $st[6] =  new EPStat('Durability',"",  EPStat::$DURABILITY);
                $st[7] =  new EPStat('Speed',"",  EPStat::$SPEED);
                $st[8] =  new EPStat('Initiative', "", EPStat::$INITIATIVE);
                $st[9] =  new EPStat('Damage bonus', "", EPStat::$DAMAGEBONUS);
                foreach ($st as $s){
                    if(!$persistManager->persistStat($s)){
                       echo $persistManager->getLastError();
                       echo "<br>";
                    } 
                    else{
                         echo "DONE ! <br>";
                    }                    
                }
                //Echo Stats
                 echo "<br><br>STATS LIST------------------------------------------ <br><br>";
                $statList = $listProvider->getListStats($configValues);
                if($statList == null){
                    echo $listProvider->getLastError();
                    echo "<br>";
                }
                else{
                    foreach($statList as $m){
                        echo $m->name." Desc : ".$m->description." Abbr. : ".$m->abbreviation."<br>";
                        echo "Stat on groups : <br>";
                        $groupsList = $m->groups;
                        foreach($groupsList as $g){
                           echo "-> ".$g;
                           echo '<br>';
                        }
                        echo '<br>';
                    }
                }
                //Add prefixes
                echo "<br><br>ADDING PREFIXES------------------------------------- <br><br>";
                $prefixs = array();
                array_push($prefixs, 'Exotic Melee');
                array_push($prefixs, 'Exotic Ranged');
                array_push($prefixs, 'Hardware');
                array_push($prefixs, 'Medecine');
                array_push($prefixs, 'Networking');
                array_push($prefixs, 'Pilot');
                array_push($prefixs, 'Academics');
                array_push($prefixs, 'Interest');
                array_push($prefixs, 'Language');
                array_push($prefixs, 'Profession');
                foreach($prefixs as $m){
                   if(!$persistManager->persistSkillPrefix($m,"COO","AST")){
                      echo $persistManager->getLastError();
                      echo "<br>";
                   } 
                   else{
                        echo "DONE ! <br>";
                   }
                }
                //Echo prefixes list
                echo "<br><br>PREFIXES LIST------------------------------------------ <br><br>";
                $preList = $listProvider->getListPrefix();
                if($preList == null){
                    echo $listProvider->getLastError();
                    echo "<br>";
                }
                else{
                    foreach($preList as $m){
                        echo $m."<br>";
                    }
                }
                //Add Reputation
                echo "<br><br>ADDING REPUTATIONS------------------------------------- <br><br>";
                $reps = array();
                array_push($reps, new EPReputation('@-Rep', ''));
                array_push($reps, new EPReputation('C-Rep', '',array("test group rep 1","test group rep 2")));
                array_push($reps, new EPReputation('E-Rep', ''));
                array_push($reps, new EPReputation('F-Rep', ''));
                array_push($reps, new EPReputation('G-Rep', ''));
                array_push($reps, new EPReputation('I-Rep', ''));
                array_push($reps, new EPReputation('R-Rep', '',array("test group rep 1")));
                foreach($reps as $m){
                   if(!$persistManager->persistReputation($m)){
                      echo $persistManager->getLastError();
                      echo "<br>";
                   } 
                   else{
                        echo "DONE ! <br>";
                   }
                }
                //Echo Reputation
                echo "<br><br>REPUTATIONS LIST------------------------------------------ <br><br>";
                $repList = $listProvider->getListReputation();
                if($repList == null){
                    echo $listProvider->getLastError();
                    echo "<br>";
                }
                else{
                    foreach($repList as $m){
                        echo $m->name." - Desc :".$m->description."<br>";
                        echo "Reputation on groups : <br>";
                        $groupsList = $m->groups;
                        foreach($groupsList as $g){
                           echo "-> ".$g;
                           echo '<br>';
                        }
                        echo '<br>';
                    }
                }
                //Add Background
                 echo "<br><br>ADDING BACKGROUND ORIGINE AND FACTION------------------------------------- <br><br>";
                $backgrounds = array();
                $bm1_1 = new EPBonusMalus("COG 5 Bonus", EPBonusMalus::$ON_APTITUDE, 5);
                $bm1_2 = new EPBonusMalus("COG 5 Bonus", EPBonusMalus::$ON_APTITUDE, 5);
                $bm1_3 = new EPBonusMalus("COG 5 Bonus", EPBonusMalus::$ON_APTITUDE, 5);
                $bm2_1 = new EPBonusMalus("Free Networking 20 Bonus", EPBonusMalus::$ON_SKILL_PREFIX, 20,"Networking","",array("Hacker pack","Scientist base tech"));
                $bm2_2 = new EPBonusMalus("Free Networking 20 Bonus", EPBonusMalus::$ON_SKILL_PREFIX, 20,"Networking","",array("Hacker pack","Scientist base tech"));
                $trait = new EPTrait("Test Trait 1", "Not existing in the rule, just for test", EPTrait::$POSITIVE_TRAIT, EPTrait::$MORPH_TRAIT, 10, $bonusMalusArray);
                array_push($backgrounds, new EPBackground('Origine Test ',
                                                          'Bla bla bla bla', 
                                                           EPBackground::$ORIGIN,
                                                           array($bm1_1,$bm1_2,$bm1_3,$bm2_2),
                                                           array($trait),
                                                           array('Limitation group 1 ','Limitation group 2'),
                                                           array('Obligation group 1','Obligation group 2')));
                array_push($backgrounds, new EPBackground('Faction Test ',
                                                          'Fla fla fla fla', 
                                                           EPBackground::$FACTION,
                                                           array($bm2_1,$bm2_2,$bm1_2),
                                                           array($trait),
                                                           array('Limitation faction group 1 ','Limitation  factiongroup 2'),
                                                           array('Obligation  faction group 1','Obligation faction group 2')));

                foreach($backgrounds as $m){
                   if(!$persistManager->persistBackground($m)){
                      echo $persistManager->getLastError();
                      echo "<br>";
                   } 
                   else{
                        echo "DONE ! <br>";
                   }
                }
                //Echo Background list
                echo "<br><br>BACKGROUND LIST------------------------------------------ <br><br>";
                $bcksList = $listProvider->getListBackgrounds();
                if($bcksList == null){
                    echo $listProvider->getLastError();
                    echo "<br>";
                }
                else{
                    foreach($bcksList as $m){
                        echo $m->name." : ".$m->description." Types : ".$m->backgroundType ;
                        echo '<br>';
                        $malusBonus = $m->bonusMalus;
                        echo "With BonusMalus : <br>";
                        foreach($malusBonus as $o){       
                           echo $o->name." - ".$o->description." - Type : ".$o->bonusMalusType." - for target : ".$o->forTargetNamed." - value : ".$o->value."<br>";
                        }
                        echo '<br>';
                        $traits = $m->traits;
                        echo "With Traits : <br>";
                        foreach($traits as $t){ 
                            echo $t->name." : ".$t->description." Types : ".$t->traitPosNeg." On : ".$t->traitEgoMorph." cp cost : ".$t->cpCost;
                            echo '<br>';
                            $malusBonus = $t->bonusMalus;
                            echo "And this trait with BonusMalus : <br>";
                            foreach($malusBonus as $u){       
                               echo $u->name." - ".$u->description." - Type : ".$u->bonusMalusType." - for target : ".$u->forTargetNamed." - value : ".$u->value."<br>";
                            }
                        }
                        echo '<br>';
                        $limit = $m->limitations;
                        echo "With Limitation group name : <br>";
                        foreach($limit as $v){       
                           echo $v."<br>";
                        }
                        echo '<br>';
                        $obl = $m->obligations;
                        echo "With Obligation group name : <br>";
                        foreach($obl as $w){       
                           echo $w."<br>";
                        }
                        echo '<br>';
                        echo '<br>';
                    }
                }
                //Add Gear
                echo "<br><br>ADDING GEAR  --------------------------------------------- <br><br>";
                $bonusMalusArray = array($bm1_1,$bm1_2,$bm1_3,$bm2_1,$bm2_2,$bm3_1);
                array_merge($bonusMalusArray,$listProvider->getListBonusMalus());
                $gear[0] = new EPGear("Test Gear 1", "Gear Not existing in the rule, just for test",  EPGear::$SOFT_GEAR, EPCreditCost::$HIGH,2, 2,0,0, $bonusMalusArray);
                $gear[1] = new EPGear("Test Gear 2", "Gear Not existing in the rule, just for test", EPGear::$STANDARD_GEAR,EPCreditCost::$MODERATE,0,0,3,2, $bonusMalusArray);
                foreach($gear as $g){
                   if(!$persistManager->persistGear($g)){
                      echo $persistManager->getLastError();
                      echo "<br>";
                   } 
                   else{
                        echo "DONE ! <br>";
                   }
                }                
                //Echo Gear list
                echo "<br><br>GEAR LIST------------------------------------------ <br><br>";
                $gearList = $listProvider->getListGears();
                if($gearList == null){
                    echo $listProvider->getLastError();
                    echo "<br>";
                }
                else{
                    foreach($gearList as $m){
                        echo $m->name." : ".$m->description.
                                        " Type : ".$m->gearType.
                                        " Cost : ".$m->getCost().
                                        " Armor kinetic : ".$m->armorKinetic.
                                        " Armor energy : ".$m->armorEnergy.
                                        " Degats : ".$m->degat.
                                        " Armor penetration : ".$m->armorPenetration;
                        echo '<br>';
                        $malusBonus = $m->bonusMalus;
                        echo "With BonusMalus : <br>";
                        foreach($malusBonus as $o){       
                           echo $o->name." - ".$o->description." - Type : ".$o->bonusMalusType." - for target : ".$o->forTargetNamed." - value : ".$o->value."<br>";
                        }
                        echo "<br>";
                        echo "<br>";
                    }
                }
                //Adding Morph
                echo "<br><br>ADDING MORPH  --------------------------------------------- <br><br>";
                $traitList = $listProvider->getListTraits();
                $gearListMorph = $listProvider->getListGears();
                $bonusMalusList = $listProvider->getListBonusMalus();
                $morph[0] = new EPMorph("Test Morph", EPMorph::$BIOMORPH, 25, EPMorph::$GENDER_MALE, 30, 8, 30, $traitList, $gearListMorph, $bonusMalusList, "Test morph description");
                foreach($morph as $m){
                   if(!$persistManager->persistMorph($m)){
                      echo $persistManager->getLastError();
                      echo "<br>";
                   } 
                   else{
                        echo "DONE ! <br>";
                   }
                }
                //Echo Morph list
                echo "<br><br>MORPH LIST------------------------------------------ <br><br>";
                $morphList = $listProvider->getListMorph();
                if($morphList == null){
                    echo $listProvider->getLastError();
                    echo "<br>";
                }
                else{
                    foreach($morphList as $m){
                        echo $m->name." : ".$m->description." Types : ".$m->morphType." Age : ".$m->age." Gender : ".$m->gender." Max Apt : ".$m->maxApptitude." DUR : ".$m->durability." CP Cost : ".$m->cpCost ;
                        echo '<br>';
                        echo '<br>';
                        $malusBonus = $m->bonusMalus;
                        echo "With BonusMalus : <br>";
                        foreach($malusBonus as $o){       
                           echo $o->name." - ".$o->description." - Type : ".$o->bonusMalusType." - for target : ".$o->forTargetNamed." - value : ".$o->value."<br>";
                        }
                        echo '<br>';
                        echo '<br>';
                        $traits = $m->traits;
                        echo "With Traits : <br>";
                        foreach($traits as $t){ 
                            echo $t->name." : ".$t->description." Types : ".$t->traitPosNeg." On : ".$t->traitEgoMorph." cp cost : ".$t->cpCost;
                            echo '<br>';
                            $malusBonus = $t->bonusMalus;
                            echo "And this trait with BonusMalus : <br>";
                            foreach($malusBonus as $u){       
                               echo $u->name." - ".$u->description." - Type : ".$u->bonusMalusType." - for target : ".$u->forTargetNamed." - value : ".$u->value."<br>";
                            }
                        }
                        echo '<br>';
                        echo '<br>';
                        $gears = $m->gears;
                        echo "With Gear : <br>";
                        foreach($gears as $g){       
                           echo $g->name." : ".$g->description.
                                        " Cost : ".$g->getCost().
                                        " Armor kinetic : ".$g->armorKinetic.
                                        " Armor energy : ".$g->armorEnergy.
                                        " Degats : ".$g->degat.
                                        " Armor penetration : ".$g->armorPenetration;
                                echo '<br>';
                                $malusBonus = $g->bonusmalus;
                                echo "Gear With BonusMalus : <br>";
                                foreach($malusBonus as $bmg){       
                                   echo $bmg->name." - ".$bmg->description." - Type : ".$bmg->bonusMalusType." - for target : ".$bmg->forTargetNamed." - value : ".$bmg->value."<br>";
                                }
                        }

                        echo '<br>';
                        echo '<br>';
                    }
                }
                //Adding Ai
                echo "<br><br>ADDING AI  --------------------------------------------- <br><br>";
                $aptAiList = $listProvider->getListAptitudes();
                foreach ($aptAiList as $apt){
                    $apt->value = 10;
                }
                $statAiList = $listProvider->getListStats($configValues);
                foreach ($statAiList as $stat){
                    $stat->value = 8;
                }
                $skillAiList = $listProvider->getListSkills($aptAiList);
                foreach ($skillAiList as $skl){
                    $skl->baseValue = 60;
                }
                $ai[0] = new EPAi("Ai Test", $aptAiList, EPCreditCost::$EXPENSIVE, $skillAiList, $statAiList, "test description");
                foreach($ai as $a){
                   if(!$persistManager->persistAi($a)){
                      echo $persistManager->getLastError();
                      echo "<br>";
                   } 
                   else{
                        echo "DONE ! <br>";
                   }
                }
                //Echo Ai
                echo "<br><br>AI LIST------------------------------------------ <br><br>";
                $aiList = $listProvider->getListAi();
                if($aiList == null){
                    echo $listProvider->getLastError();
                    echo "<br>";
                }
                else{
                    foreach($aiList as $m){
                        echo $m->name." : ".$m->description." Cost : ".$m->getCost() ;
                        echo '<br>';
                        echo '<br>';
                        $aptitudes = $m->aptitudes;
                        echo "With Aptitude : <br>";
                        foreach($aptitudes as $o){       
                            echo $o->name." Desc : ".$o->description." Abbr. : ".$o->abbreviation."<br>";
                            echo "Aptitudes on groups : <br>";
                            $groupsList = $o->groups;
                            foreach($groupsList as $g){
                               echo "-> ".$g;
                               echo '<br>';
                            }
                            echo '<br>';
                        }
                        echo '<br>';
                        echo '<br>';

                        $stat = $m->stats;
                        echo "With Stats : <br>";
                        foreach($stat as $p){       
                            echo $p->name." Desc : ".$p->description." Abbr. : ".$p->abbreviation."<br>";
                            echo "Stat on groups : <br>";
                            $groupsList = $p->groups;
                            foreach($groupsList as $g){
                               echo "-> ".$g;
                               echo '<br>';
                            }
                            echo '<br>';
                        }
                        echo '<br>';
                        echo '<br>';

                        $skills = $m->skills;
                        echo "With Skills : <br>";
                        foreach($skills as $q){       
                            echo $q->prefix.":".$q->name." - ".$q->description." - Apt : ".$q->linkedApt->abbreviation." - Skill Type : ".$q->skillType." - Defaultable : ".$q->defaultable."<br>";
                            echo "Skill on groups : <br>";
                            $groupsList = $q->groups;
                            foreach($groupsList as $g){
                               echo "-> ".$g;
                               echo '<br>';
                            }
                        }
                        echo '<br>';
                        echo '<br>';
                    }
                }  
                
               //Adding PsySleight
                echo "<br><br>ADDING PSY SLEIGHT  ---------------------------------- <br><br>";
                
                $bonusMalusListPsy = $listProvider->getListBonusMalus();
                $psy[0] = new EPPsySleight("Psy Sleight Test", "desc desc desc desc", 
                                   EPPsySleight::$ACTIVE_PSY, 
                                   EPPsySleight::$RANGE_SELF,
                                   EPPsySleight::$DURATION_SUSTAINED,
                                   EPPsySleight::$ACTION_COMPLEX,
                                   42,$bonusMalusListPsy);
                foreach($psy as $a){
                   if(!$persistManager->persistPsySleight($a)){
                      echo $persistManager->getLastError();
                      echo "<br>";
                   } 
                   else{
                        echo "DONE ! <br>";
                   }
                }
            
            
            //Eco PsySleight
            echo "<br><br>PSY SLEIGHT LIST------------------------------------------ <br><br>";
                $psyList = $listProvider->getListPsySleights();
                if($psyList == null){
                    echo $listProvider->getLastError();
                    echo "<br>";
                }
                else{
                    foreach($psyList as $m){
                        echo $m->name." : ".$m->description." data : ".$m->range." -- ".$m->psyType." -- ".$m->duration." -- ".$m->action." -- ".$m->strainMod   ;
                        echo '<br>';
                        echo '<br>';
                        $bonusMalus = $m->bonusMalus;
                        echo "With Bonus malus : <br>";
                        foreach($bonusMalus as $o){       
                            echo $o->name." Desc : ".$o->description."<br>";
                        }
                        echo '<br>';
                        echo '<br>';
                       
                    }
                }  
            }
            
            if (strcmp($cibleTest,'Delete') == 0){
                //=====DELETE TESTS ================================================
                require_once 'EPPersistentDataManager.php';
                require_once 'EPAptitude.php';
                require_once 'EPListProvider.php';

                $listProvider = new EPListProvider('./config.ini');
                $persistManager = new EPPersistentDataManager('./config.ini');
                
                //Aptitude
                echo "<br><br>APTITUDE DELETION------------------------------------------ <br><br>";

                $apdel =  new EPAptitude('APTITUDE_TODELETE',  EPAptitude::$COGNITION,"",array("TODELETE1","TODELETE2"));
                if(!$persistManager->persistAptitude($apdel)){
                   echo $persistManager->getLastError();
                   echo "<br>";
                } 
                else{
                     echo "Aptitude added ! <br>";
                }

                if(!$persistManager->deleteAptitude($apdel->name)){
                   echo $persistManager->getLastError();
                   echo "<br>";
                } 
                else{
                     echo "Aptitude Deleted ! <br>";
                }

                //Background
                echo "<br><br>BACKGROUND DELETION------------------------------------------ <br><br>";

                $bm1 = new EPBonusMalus("TODELETE1", EPBonusMalus::$ON_APTITUDE, 5);
                $bm2 = new EPBonusMalus("TODELETE2", EPBonusMalus::$ON_SKILL_PREFIX, 20,"Networking","",array("Hacker pack","Scientist base tech"));
                
                $bm1_1 = new EPBonusMalus("COG 5 Bonus", EPBonusMalus::$ON_APTITUDE, 5);
                $bm1_2 = new EPBonusMalus("COG 5 Bonus", EPBonusMalus::$ON_APTITUDE, 5);
                $bm1_3 = new EPBonusMalus("COG 5 Bonus", EPBonusMalus::$ON_APTITUDE, 5);
                $bm2_1 = new EPBonusMalus("Free Networking 20 Bonus", EPBonusMalus::$ON_SKILL_PREFIX, 20,"Networking","",array("Hacker pack","Scientist base tech"));
                $bm2_2 = new EPBonusMalus("Free Networking 20 Bonus", EPBonusMalus::$ON_SKILL_PREFIX, 20,"Networking","",array("Hacker pack","Scientist base tech"));
                $bm3_1 = new EPBonusMalus("Circadian Regulation", EPBonusMalus::$DESCRIPTIVE_ONLY, 0,"","The character dreams constantly while asleep and can both fall asleep and wake up almost instantly. In addition, the character can easily and with no ill-effects shift to a 2-day cycle, where they are awake for 44 hours and sleep for 4.");
                $bonusMalusArray = array($bm1_1,$bm1_2,$bm1_3,$bm2_1,$bm2_2,$bm3_1);
                array_merge($bonusMalusArray,$listProvider->getListBonusMalus());

                $trait1 = new EPTrait("TODELETE3","sting in the rule, just for test", EPTrait::$POSITIVE_TRAIT, EPTrait::$MORPH_TRAIT, 10, $bonusMalusArray);


                $toDelBackgrounds = new EPBackground('BACKGROUND_TODELETE',
                                                     'Bla bla bla bla', 
                                                     EPBackground::$ORIGIN,
                                                     array($bm1_1,$bm1_2,$bm1_3,$bm2_2),
                                                     array($trait1),
                                                     array('TODELETE4','TODELETE5'),
                                                     array('TODELETE5','TODELETE6'));

                if(!$persistManager->persistBackground($toDelBackgrounds)){
                   echo $persistManager->getLastError();
                   echo "<br>";
                } 
                else{
                     echo "Background added ! <br>";
                }

                if(!$persistManager->deleteBackground($toDelBackgrounds->name)){
                   echo $persistManager->getLastError();
                   echo "<br>";
                } 
                else{
                     echo "Background Deleted ! <br>";
                }

                //BonusMalus
                echo "<br><br>BONUS MALUS DELETION------------------------------------------ <br><br>";

                $delBm = new EPBonusMalus("TODELETE", EPBonusMalus::$ON_SKILL_PREFIX, 20,"Networking","",array("TODELETE1","TODELETE2"));



                if(!$persistManager->persistBonusMalus($delBm)){
                   echo $persistManager->getLastError();
                   echo "<br>";
                } 
                else{
                     echo "Bonus Malus added ! <br>";
                }

                if(!$persistManager->deleteBonusMalus($delBm->name)){
                   echo $persistManager->getLastError();
                   echo "<br>";
                } 
                else{
                     echo "Bonus Malus Deleted ! <br>";
                }

                //Gear
                echo "<br><br>GEAR DELETION------------------------------------------ <br><br>";
                $delbm = new EPBonusMalus("COG 5 Bonus", EPBonusMalus::$ON_APTITUDE, 5);

                $delbonusMalusArray = array($delbm);
                $toDelGear = new EPGear("TODELETE", "Gear Not existing in the rule, just for test",  EPGear::$SOFT_GEAR, EPCreditCost::$HIGH,2, 2,0,0, $delbonusMalusArray);


                if(!$persistManager->persistGear($toDelGear)){
                   echo $persistManager->getLastError();
                   echo "<br>";
                 } 
                 else{
                     echo "Gear added ! <br>";
                 }

                 if(!$persistManager->deleteGear($toDelGear->name)){
                   echo $persistManager->getLastError();
                   echo "<br>";
                 } 
                 else{
                     echo "Gear Deleted ! <br>";
                 }

                 //Morph
                echo "<br><br>MORPH DELETION------------------------------------------ <br><br>";
                $todeletetraitList = array(new EPTrait("TODELETE1", "", EPTrait::$POSITIVE_TRAIT,  EPTrait::$EGO_TRAIT, 11));
                $todeletegearListMorph = array(new EPGear("TODELETE2", "",  EPGear::$SOFT_GEAR, EPCreditCost::$HIGH));
                $todeletebonusMalusList = array(new EPBonusMalus("TODELETE3", EPBonusMalus::$ON_APTITUDE, 9));

                $todeletemorph = new EPMorph("MORPH_TODELETE", EPMorph::$BIOMORPH, 25, EPMorph::$GENDER_MALE, 30, 8, 30, $todeletetraitList, $todeletegearListMorph, $todeletebonusMalusList, "Test morph description");

                if(!$persistManager->persistMorph($todeletemorph)){
                   echo $persistManager->getLastError();
                   echo "<br>";
                 } 
                 else{
                     echo "Morph added ! <br>";
                 } 

                 if(!$persistManager->deleteMorph($todeletemorph->name)){
                   echo $persistManager->getLastError();
                   echo "<br>";
                 } 
                 else{
                     echo "Morph Deleted ! <br>";
                 }

                 //reputation
                echo "<br><br>REPUTATION DELETION------------------------------------------ <br><br>";
                $todeletereps = new EPReputation('REP_TODELETE', '',array("TODELETE1"));

                if(!$persistManager->persistReputation($todeletereps)){
                   echo $persistManager->getLastError();
                   echo "<br>";
                } 
                else{
                     echo "Reputation added ! <br>";
                }

                if(!$persistManager->deleteReputation($todeletereps->name)){
                   echo $persistManager->getLastError();
                   echo "<br>";
                } 
                else{
                     echo "Reputation Deleted ! <br>";
                }

                //Skills
                echo "<br><br>SKILL DELETION------------------------------------------ <br><br>";
                $apt = $listProvider->getListAptitudes();
                $todelsk = new EPSkill("SKILL_TODELETE",
                                  "",
                                  $apt[EPAptitude::$INTUITION],
                                  EPSkill::$KNOWLEDGE_SKILL_TYPE,
                                  EPSkill::$NO_DEFAULTABLE,
                                  "",
                                  array("TODELETE1","TODELETE2","TODELETE3"));


                if(!$persistManager->persistSkill($todelsk)){
                   echo $persistManager->getLastError();
                   echo "<br>";
                }
                else{
                    echo "Skill added ! <br>";
                }

                if(!$persistManager->deleteSkill($todelsk->name)){
                   echo $persistManager->getLastError();
                   echo "<br>";
                } 
                else{
                     echo "Skill Deleted ! <br>";
                }

                //Skill prefix
                echo "<br><br>SKILL PREFIX DELETION------------------------------------------ <br><br>";
                 $todelprefixs = 'PREFIX_TODELETE';

                if(!$persistManager->persistSkillPrefix($todelprefixs)){
                   echo $persistManager->getLastError();
                   echo "<br>";
                } 
                else{
                     echo "Skill prefix added ! <br>";
                }

                if(!$persistManager->deleteSkillPrefix($todelprefixs)){
                   echo $persistManager->getLastError();
                   echo "<br>";
                } 
                else{
                     echo "Skill prefix Deleted ! <br>";
                }

                //Stat
                echo "<br><br>STAT DELETION------------------------------------------ <br><br>";
                $todelstat=  new EPStat('STAT_TODELETE', "", EPStat::$DAMAGEBONUS,array("TODELETE1"));

                if (isset($todelstat)){
                    if(!$persistManager->persistStat($todelstat)){
                       echo $persistManager->getLastError();
                       echo "<br>";
                    } 
                    else{
                         echo "Stat added ! <br>";
                    }

                    if(!$persistManager->deleteStat($todelstat->name)){
                       echo $persistManager->getLastError();
                       echo "<br>";
                    } 
                    else{
                         echo "Stat Deleted ! <br>";
                    }                    
                }else{
                    echo "Stat To Deleted not created ! <br>";
                }

                //Trait
                echo "<br><br>TRAIT DELETION------------------------------------------ <br><br>";

                $todelbm = new EPBonusMalus("TODELETE1", EPBonusMalus::$ON_APTITUDE, 5);
                $todeltrait = new EPTrait("TRAIT_TODELETE", "", EPTrait::$POSITIVE_TRAIT, EPTrait::$MORPH_TRAIT, 10, array($todelbm));

                if(!$persistManager->persistTrait($todeltrait)){
                   echo $persistManager->getLastError();
                   echo "<br>";
                 } 
                 else{
                     echo "Trait added ! <br>";
                 }

                 if(!$persistManager->deleteTrait($todeltrait->name)){
                   echo $persistManager->getLastError();
                   echo "<br>";
                 } 
                 else{
                     echo "Trait Deleted ! <br>";
                 }


                 //Ai
                echo "<br><br>AI DELETION------------------------------------------ <br><br>";

                $delaptAiList = array(new EPAptitude("TODELETE1", ""));
                $delstatAiList = array(new EPStat("TODELETE2", "", ""));
                $delskillAiList = array(new EPSkill("TODELETE3", "", null, "", ""));

                $todelai = new EPAi("AI_TODELETE", $delaptAiList, EPCreditCost::$EXPENSIVE, $delskillAiList, $delstatAiList, "DELETEDELETE");

                if(!$persistManager->persistAi($todelai)){
                   echo $persistManager->getLastError();
                   echo "<br>";
                 } 
                 else{
                     echo "Ai added ! <br>";
                 }

                 if(!$persistManager->deleteAi($todelai->name)){
                   echo $persistManager->getLastError();
                   echo "<br>";
                 } 
                 else{
                     echo "Ai Deleted ! <br>";
                 }    
                 
                  //Psy
                echo "<br><br>PSY DELETION------------------------------------------ <br><br>";
                $bonusMalusListPsy = $listProvider->getListBonusMalus();
                $psyToDelete = new EPPsySleight("TODELETE1", "MUST BE DELETED", 
                                   EPPsySleight::$ACTIVE_PSY, 
                                   EPPsySleight::$RANGE_SELF,
                                   EPPsySleight::$DURATION_SUSTAINED,
                                   EPPsySleight::$ACTION_COMPLEX,
                                   66,$bonusMalusListPsy);
                
                if(!$persistManager->persistPsySleight($psyToDelete)){
                   echo $persistManager->getLastError();
                   echo "<br>";
                 } 
                 else{
                     echo "Psy added ! <br>";
                 }

                 if(!$persistManager->deletePsy($psyToDelete->name)){
                   echo $persistManager->getLastError();
                   echo "<br>";
                 } 
                 else{
                     echo "Psy Deleted ! <br>";
                 }
            }           
        ?>
    </body>
</html>
