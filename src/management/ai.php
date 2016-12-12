
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Manage AI</title>
    </head>
    <body>
        <?php
            error_reporting(E_ALL);
            error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
            ini_set('display_errors', '1');
            
          // session_start();
           
           require_once 'EPPersistentDataManager.php';
           require_once '../php/EPConfigFile.php';
           require_once '../php/EPListProvider.php';
           require_once '../php/EPConfigFile.php';
           require_once '../php/EPAptitude.php';
           require_once '../php/EPSkill.php';
           require_once '../php/EPAi.php';
           require_once '../php/EPStat.php';
           
           $epListP = new EPListProvider('../php/config.ini');
           $persistManager = new EPPersistentDataManager('../php/config.ini');
           $configValues = new EPConfigFile('./config.ini');
           $_SESSION['aiList'] = $epListP->getListAi();
           $_SESSION['aptList'] = $epListP->getListAptitudes();
           $_SESSION['skillList'] = $epListP->getListSkills($_SESSION['aptList']);
           $_SESSION['statList'] = $epListP->getListStats($configValues,$_SESSION['cc']);           
           
           if(isset($_POST['addAi'])){
               
               $aptListInject = array();
               foreach($_SESSION['aptList'] as $m){
                   if(isset($_POST['check_'.$m->abbreviation])){
                       $apatObj = $epListP->getAptitudeByName($_POST['check_'.$m->abbreviation]);
                       $apatObj->value = $_POST['val_'.$m->abbreviation];
                       array_push($aptListInject, $apatObj);
                   }
               }
               
               $sklListInject = array();
               foreach($_SESSION['skillList'] as $n){
                   $name_spaceless = str_replace(" ", "_", $n->name);
                   if(isset($_POST['check_'.$name_spaceless])){
                       $skillObj = $epListP->getSkillByNamePrefix($_POST['check_'.$name_spaceless],$_POST['prefix_'.$name_spaceless],$_SESSION['aptList']);
                       $skillObj->baseValue = $_POST['val_'.$name_spaceless];
                       array_push($sklListInject, $skillObj);
                   }
               }
               
               $statListInject = array();
               foreach($_SESSION['statList'] as $o){
                   if(isset($_POST['check_'.$o->abbreviation])){
                       $statObj = $epListP->getStatByName($_POST['check_'.$o->abbreviation]);
                       $statObj->value = $_POST['val_'.$o->abbreviation];
                       array_push($statListInject, $statObj);
                   }
               }
               
               if($_POST['unique'] == "Y") $u = true;
               else $u = false;
               
               $aiObj = new EPAi($_POST['name'], 
                                 $aptListInject, 
                                 $_POST['costType'], 
                                 $sklListInject, 
                                 $statListInject, 
                                 $_POST['desc']);
               
               $aiObj->unique = $u;
               
               if(!$persistManager->persistAi($aiObj)){
                       echo $persistManager->getLastError();
                       echo "<br><br>";
                } 
                else{
                    $_SESSION['aiList'] = $epListP->getListAi();
                    echo "<b> Ai Added ! </b><br><br>"; 
                }   
               
             
           }
           else if(isset($_POST['deleteAi'])){
               
               if(!$persistManager->deleteAi($_POST['deleteAi'])){
                       echo $persistManager->getLastError();
                       echo "<br><br>";
                } 
                else{
                    $_SESSION['aiList'] = $epListP->getListAi();
                    echo "<b> Ai Deleted ! </b><br><br>"; 
                }  
               
           }
           
           
        ?>
        <form action="./ai.php" method="post">
            <input type="hidden" name="addAi" value="yep">
            <label>Ai name<input type="text" name="name"></label>
            <br>
            <label>Description</label>
            <br>
            <textarea name="desc" rows="20" cols="50"></textarea>
            <br>
            <label>Unique</label>
            <select name="unique">
                <option value="Y">Yes</option> 
                <option value="N">No</option> 
            </select>
            <br>
            <label>Cost Type </label>
            <select name="costType">
                <option value="<?php echo EPCreditCost::$TRIVIAL ?>">Trivial</option> 
                <option value="<?php echo EPCreditCost::$MODERATE ?>">Moderate</option>
                <option value="<?php echo EPCreditCost::$LOW ?>">Low</option>
                <option value="<?php echo EPCreditCost::$HIGH ?>">High</option>
                <option value="<?php echo EPCreditCost::$EXPENSIVE ?>">Expensive</option>
                <option value="<?php echo EPCreditCost::$VERY_EXPENSIVE ?>">Very Expensive</option>
                <option value="<?php echo EPCreditCost::$EXTREMELY_EXPENSIVE ?>">Extermely Expensive</option>
            </select>
            <br>
            <label>Aptitude(s)</label>
            <br>
                <?php 
                    foreach($_SESSION['aptList'] as $p){
                       echo "<input type='checkbox' name='check_".$p->abbreviation."' value='".$p->name."'><label>".$p->name."<input type='text' name='val_".$p->abbreviation."'></label><br>";  
                     }
                ?>
                
            <br>
            <label>Skill(s)</label>
            <br>
                 <?php 
                    foreach($_SESSION['skillList'] as $q){
                       $name_spaceless = str_replace(" ", "_", $q->name);
                       echo "<input type='checkbox' name='check_".$name_spaceless."' value='".$q->name."'><input type='hidden' name ='prefix_".$name_spaceless."' value='".$q->prefix."'><label>".$q->name.":".$q->prefix."<input type='text' name='val_".$name_spaceless."'></label><br>";  
                     }
                ?>
            <br>
            <label>Stats(s)</label>
            <br>
                 <?php 
                    foreach($_SESSION['statList'] as $r){
                       echo "<input type='checkbox' name='check_".$r->abbreviation."' value='".$r->name."'><label>".$r->name."<input type='text' name='val_".$r->abbreviation."'></label><br>";  
                     }
                ?>
            <br>
            <br>
            <input type="submit" value="Insert">
        </form>
        
        <p>Ai on database</p>
        <table border = "1">
          <tr>
            <th>Name</th>
            <th>Description</th>
            <th>Cost Type</th>
            <th>Unique</th>
            <th>Aptitude(s)</th>
            <th>Skill(s)</th>
            <th>Stats (s)</th>
            <th>DELETE FROM DATABASE</th>
          </tr>
          
            <?php    
                  foreach($_SESSION['aiList'] as $s){
                    echo "<tr>";
                    echo "<td>".$s->name."</td>"; 
                    echo "<td>".$s->description."</td>";
                        if($s->cost == EPCreditCost::$TRIVIAL) $type = "Trivial";
                        else if($s->cost == EPCreditCost::$MODERATE) $type = "Moderate";
                        else if($s->cost == EPCreditCost::$LOW) $type = "Low";
                        else if($s->cost == EPCreditCost::$HIGH) $type = "High";
                        else if($s->cost == EPCreditCost::$EXPENSIVE) $type = "Expensive";
                        else if($s->cost == EPCreditCost::$VERY_EXPENSIVE) $type = "Very Expensive";
                        else if($s->cost == EPCreditCost::$EXTREMELY_EXPENSIVE) $type = "Extr. Expensive";
                        else $type = "ERROR !";
                    echo "<td>".$type."</td>";   
                    if($s->unique) $unik = "Y";
                    	else $unik = "N";
                    echo "<td>".$unik."</td>";
                    $aptitudes = "";
                    foreach($s->aptitudes as $apt){
                       $aptitudes .= $apt->name." = ".$apt->value." <br>";  
                     }
                    echo "<td>".$aptitudes."</td>";
                    
                    $skills = "";
                    foreach($s->skills as $ski){
                       $skills .= $ski->name." = ".$ski->baseValue." <br>";  
                     }
                    echo "<td>".$skills."</td>";
                    
                    $stats = "";
                    foreach($s->stats as $sta){
                       $stats .= $sta->name." = ".$sta->value." <br>";  
                     }
                    echo "<td>".$stats."</td>";
                    
                    echo "<td><form action='./ai.php' method='post'><input type='hidden' name='deleteAi' value='".$s->name."'><input type='submit' value='Delete this line'></form></td>";
                    echo "</tr>";
                  }
             ?>            
        </table>
    </body>
</html>
