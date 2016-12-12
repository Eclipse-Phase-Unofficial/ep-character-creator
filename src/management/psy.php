
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Manage PsySleight</title>
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
           require_once '../php/EPBonusMalus.php';
           require_once '../php/EPPsySleight.php';
           
           $epListP = new EPListProvider('../php/config.ini');
           $persistManager = new EPPersistentDataManager('../php/config.ini');
           $_SESSION['psyList'] = $epListP->getListPsySleights();
           $_SESSION['bmList'] = $epListP->getListBonusMalus();           
           
           if(isset($_POST['addPsy'])){
               
              $bmlistInject = array();
              if(isset($_POST['selectedBM'])&& !empty($_POST['selectedBM'])){
	               foreach ($_POST['selectedBM'] as $bm) {
	                   $bmObj = $epListP->getBonusMalusByName($bm);
	                   array_push($bmlistInject, $bmObj);
	               }
               }

                $newPsy = new EPPsySleight( $_POST['name'], 
                                            $_POST['desc'], 
                                            $_POST['pType'], 
                                            $_POST['range'],
                                            $_POST['duration'], 
                                            $_POST['action'], 
                                            $_POST['strain'],
                                            $_POST['level'],  
                                            $bmlistInject,
                                            $_POST['needed']);
               
               if(!$persistManager->persistPsySleight($newPsy)){
                       echo $persistManager->getLastError();
                       echo "<br><br>";
                } 
                else{
                    $_SESSION['psyList'] = $epListP->getListPsySleights();
                    echo "<b> Psy Sleight Added ! </b><br><br>"; 
                }      
           }
           else if(isset($_POST['deletePsy'])){
               
               if(!$persistManager->deletePsy($_POST['deletePsy'])){
                       echo $persistManager->getLastError();
                       echo "<br><br>";
                } 
                else{
                    $_SESSION['psyList'] = $epListP->getListPsySleights();
                    echo "<b> Psy Sleight Deleted ! </b><br><br>"; 
                }  
               
           }
           
           
        ?>
        <form action="./psy.php" method="post">
            <input type="hidden" name="addPsy" value="yep">
            <label>Psy name<input type="text" name="name"></label>
            <br>
            <label>Description</label>
            <br>
            <textarea name="desc" rows="20" cols="50"></textarea>
            <br>
            <label>Psy Type </label>
            <select name="pType">
                <option value="<?php echo EPPsySleight::$ACTIVE_PSY ?>">Active</option> 
                <option value="<?php echo EPPsySleight::$PASSIVE_PSY ?>">Passive</option>
            </select>
            <br>
            <label>Bonus Malus</label>
            <br>
            <select name="selectedBM[]" multiple>
                <?php    
                     foreach($_SESSION['bmList'] as $m){
                       echo "<option value='".$m->name."'>".$m->name."</option> ";  
                     }
                ?>
            </select>
            <br>
            <label>Range Type </label>
            <select name="range">
                <option value="<?php echo EPPsySleight::$RANGE_CLOSE ?>">Close</option>
                <option value="<?php echo EPPsySleight::$RANGE_PSY ?>">Psy</option>
                <option value="<?php echo EPPsySleight::$RANGE_SELF ?>">Self</option>
                <option value="<?php echo EPPsySleight::$RANGE_TOUCH ?>">Touch</option>
            </select>
            <br>
            <label>Duration </label>
            <select name="duration">
                <option value="<?php echo EPPsySleight::$DURATION_CONSTANT ?>">Constant</option>
                <option value="<?php echo EPPsySleight::$DURATION_INSTANT ?>">Instant</option>
                <option value="<?php echo EPPsySleight::$DURATION_SUSTAINED ?>">Sustained</option>
                <option value="<?php echo EPPsySleight::$DURATION_TEMPORARY ?>">Temporary</option>
            </select>
            <br>
            <label>Action </label>
            <select name="action">
                <option value="<?php echo EPPsySleight::$ACTION_AUTOMATIC ?>">Automatic</option>
                <option value="<?php echo EPPsySleight::$ACTION_COMPLEX ?>">Complex</option>
                <option value="<?php echo EPPsySleight::$ACTION_QUICK ?>">Quick</option>
                <option value="<?php echo EPPsySleight::$ACTION_TASK ?>">Task</option>
            </select>
            <br>
            <br>
            <label>Level </label>
            <select name="level">
                <option value="<?php echo EPPsySleight::$LEVEL_CHI_PSY ?>">Chi</option>
                <option value="<?php echo EPPsySleight::$LEVEL_GAMMA_PSY ?>">Gamma</option>
            </select>
            <br>
            <label>Strain Modificator<input type="text" name="strain"></label>
            <br>
             <br>
            <label>Skill needed<input type="text" name="needed"></label>
            <br>
            <br>
            <input type="submit" value="Insert">
        </form>
        
        <p>Psy Sleight on database</p>
        <table border = "1">
          <tr>
            <th>Name</th>
            <th>Description</th>
            <th>Type</th>
            <th>Range</th>
            <th>Duration</th>
            <th>Action</th>
            <th>Level</th>
            <th>Strain Modif.</th>
            <th>Skill needed</th>
            <th>Bonus Malus</th>
            <th>DELETE FROM DATABASE</th>
          </tr>
          
            <?php   
                  foreach($_SESSION['psyList'] as $m){
                    echo "<tr>";
                    echo "<td>".$m->name."</td>"; 
                    echo "<td>".$m->description."</td>";
                        if($m->psyType == EPPsySleight::$ACTIVE_PSY) $type = "Active";
                        else if($m->psyType == EPPsySleight::$PASSIVE_PSY) $type = "Passive";
                        else $type = "ERROR !";
                    echo "<td>".$type."</td>";
                    
                        if($m->range == EPPsySleight::$RANGE_CLOSE) $range = "Close";
                        else if($m->range == EPPsySleight::$RANGE_PSY) $range = "Psy";
                        else if($m->range == EPPsySleight::$RANGE_SELF) $range = "Self";
                        else if($m->range == EPPsySleight::$RANGE_TOUCH) $range = "Touch";
                        else $range = "ERROR !";
                    echo "<td>".$range."</td>";
                    
                        if($m->duration == EPPsySleight::$DURATION_CONSTANT) $duration = "Constant";
                        else if($m->duration == EPPsySleight::$DURATION_INSTANT) $duration = "Instant";
                        else if($m->duration == EPPsySleight::$DURATION_SUSTAINED) $duration = "Sustaind";
                        else if($m->duration == EPPsySleight::$DURATION_TEMPORARY) $duration = "Temporary";
                        else $duration = "ERROR !";
                    echo "<td>".$duration."</td>";
                    
                        if($m->action == EPPsySleight::$ACTION_AUTOMATIC) $action = "Automatic";
                        else if($m->action == EPPsySleight::$ACTION_COMPLEX) $action = "Complex";
                        else if($m->action == EPPsySleight::$ACTION_QUICK) $action = "Quick";
                        else if($m->action == EPPsySleight::$ACTION_TASK) $action = "Task";
                        else $action = "ERROR !";
                    echo "<td>".$action."</td>";
                    
                     if($m->psyLevel == EPPsySleight::$LEVEL_CHI_PSY) $psyLevel = "Chi";
                        else if($m->psyLevel == EPPsySleight::$LEVEL_GAMMA_PSY) $psyLevel = "Gamma";
                        else $psyLevel = "ERROR !";
                    echo "<td>".$psyLevel."</td>";

                    
                    
                    echo "<td>".$m->strainMod."</td>";
                     echo "<td>".$m->skillNeeded."</td>";
                    
                    $bonusMalus = "";
                    foreach($m->bonusMalus as $bm){
                       $bonusMalus .= $bm->name." | ";  
                     }
                    echo "<td>".$bonusMalus."</td>";
                   
                    
                    echo "<td><form action='./psy.php' method='post'><input type='hidden' name='deletePsy' value='".$m->name."'><input type='submit' value='Delete this line'></form></td>";
                    echo "</tr>";
                  }
             ?>            
        </table>
    </body>
</html>
