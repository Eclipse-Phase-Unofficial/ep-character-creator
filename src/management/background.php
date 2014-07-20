
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Manage Background</title>
    </head>
    <body>
        <?php
            error_reporting(E_ALL);
            error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
            ini_set('display_errors', '1');
            
          // session_start();
           
           require_once '../php/EPConfigFile.php';
           require_once '../php/EPListProvider.php';
           require_once '../php/EPPersistentDataManager.php';
           require_once '../php/EPConfigFile.php';
           require_once '../php/EPBonusMalus.php';
           require_once '../php/EPBackground.php';
           
           $epListP = new EPListProvider('../php/config.ini');
           $persistManager = new EPPersistentDataManager('../php/config.ini');
           $_SESSION['backgroundList'] = $epListP->getListBackgrounds();
           $_SESSION['groupsList'] = $epListP->getListGroups();
           $_SESSION['bmList'] = $epListP->getListBonusMalus();
           $_SESSION['traitsList'] = $epListP->getListTraits();
           
           
           if(isset($_POST['addBackground'])){
               
              $bmlistInject = array();
               foreach ($_POST['selectedBM'] as $bm) {
                   $bmObj = $epListP->getBonusMalusByName($bm);
                   array_push($bmlistInject, $bmObj);
               }
                $traitlistInject = array();
               if(isset($_POST['selectedTraits'])){
	               foreach ($_POST['selectedTraits'] as $t) {
	                   $traitObj = $epListP->getTraitByName($t);
	                   array_push($traitlistInject, $traitObj);
	               }
               }
               $limlistInject = array();
               if(isset($_POST['limitGroups'])){
	               foreach ($_POST['limitGroups'] as $lim) {
	                   array_push($limlistInject, $lim);
	               }
               }
               $obllistInject = array();
               if(isset($_POST['obliGroups'])){
	               foreach ($_POST['obliGroups'] as $obl) {
	                   array_push($obllistInject, $obl);
	               }
               }
               
               
               
                $newBackground = new EPBackground($_POST['name'],
                                                  $_POST['desc'],
                                                  $_POST['type'], 
                                                  $bmlistInject,
                                                  $traitlistInject,
                                                  $limlistInject,
                                                  $obllistInject);
               
               if(!$persistManager->persistBackground($newBackground)){
                       echo $persistManager->getLastError();
                       echo "<br><br>";
                } 
                else{
                    $_SESSION['backgroundList'] = $epListP->getListBackgrounds();
                    echo "<b> Background Added ! </b><br><br>"; 
                }      
           }
           else if(isset($_POST['deleteBackground'])){
               
               if(!$persistManager->deleteBackground($_POST['deleteBackground'])){
                       echo $persistManager->getLastError();
                       echo "<br><br>";
                } 
                else{
                    $_SESSION['backgroundList'] = $epListP->getListBackgrounds();
                    echo "<b> Background Deleted ! </b><br><br>"; 
                }  
               
           }
           
           
        ?>
        <form action="background.php" method="post">
            <input type="hidden" name="addBackground" value="yep">
            <label>Background name<input type="text" name="name"></label>
            <br>
            <label>Description</label>
            <br>
            <textarea name="desc" rows="20" cols="50"></textarea>
            <br>
            <label>Background Type </label>
            <select name="type">
                <option value="<?php echo EPBackground::$FACTION ?>">Faction</option> 
                <option value="<?php echo EPBackground::$ORIGIN ?>">Origin</option>
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
            <label>Trait(s)</label>
            <br>
            <select name="selectedTraits[]" multiple>
                <?php    
                     foreach($_SESSION['traitsList'] as $m){
                       echo "<option value='".$m->name."'>".$m->name."</option> ";  
                     }
                ?>
            </select>
            <br>
            <label>Limitation Group(s)</label>
            <br>
            <select name="limitGroups[]" multiple>
                <?php    
                     foreach($_SESSION['groupsList'] as $m){
                       echo "<option value='".$m."'>".$m."</option> ";  
                     }
                ?>
            </select>
             <br>
            <label>Obligation Group(s)</label>
            <br>
            <select name="obliGroups[]" multiple>
                <?php    
                     foreach($_SESSION['groupsList'] as $m){
                       echo "<option value='".$m."'>".$m."</option> ";  
                     }
                ?>
            </select>
            <br>
            <br>
            <input type="submit" value="Insert">
        </form>
        
        <p>Background on database</p>
        <table border = "1">
          <tr>
            <th>Name</th>
            <th>Description</th>
            <th>Type</th>
            <th>Bonus Malus</th>
            <th>Trait</th>
            <th>Limitation(s)</th>
            <th>Obligation(s)</th>
            <th>DELETE FROM DATABASE</th>
          </tr>
          
            <?php    
                  foreach($_SESSION['backgroundList'] as $m){
                    echo "<tr>";
                    echo "<td>".$m->name."</td>"; 
                    echo "<td>".$m->description."</td>";
                        if($m->backgroundType == EPBackground::$FACTION) $type = "Faction";
                        else if($m->backgroundType == EPBackground::$ORIGIN) $type = "Origine";
                        else $type = "ERROR !";
                    echo "<td>".$type."</td>";
                    $bonusMalus = "";
                    foreach($m->bonusMalus as $bm){
                       $bonusMalus .= $bm->name." | ";  
                     }
                    echo "<td>".$bonusMalus."</td>";
                    $traits = "";
                    foreach($m->traits as $t){
                       $traits .= $t->name." | ";  
                     }
                    
                    echo "<td>".$traits."</td>";
                    $limits = "";
                    foreach($m->limitations as $lim){
                       $limits .= $lim." | ";  
                     }
                    echo "<td>".$limits."</td>";
                    
                    $oblis = "";
                    foreach($m->obligations as $obl){
                       $oblis .= $obl." | ";  
                     }
                    echo "<td>".$oblis."</td>";
                    
                    echo "<td><form action='./background.php' method='post'><input type='hidden' name='deleteBackground' value='".$m->name."'><input type='submit' value='Delete this line'></form></td>";
                    echo "</tr>";
                  }
             ?>            
        </table>
    </body>
</html>
