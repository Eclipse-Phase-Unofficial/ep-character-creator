
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Manage Morph</title>
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
           require_once '../php/EPBonusMalus.php';
           require_once '../php/EPMorph.php';
           
           $epListP = new EPListProvider('../php/config.ini');
           $persistManager = new EPPersistentDataManager('../php/config.ini');
           $_SESSION['morphList'] = $epListP->getListMorph();
           $_SESSION['bmList'] = $epListP->getListBonusMalus();
           $_SESSION['traitList'] = $epListP->getListTraits();
           $_SESSION['gearList'] = $epListP->getListGears();
           
           if(isset($_POST['addMorph'])){
               
              $bmlistInject = array();
              if(!empty($_POST['selectedBM'])){
	               foreach ($_POST['selectedBM'] as $bm) {
	                   $bmObj = $epListP->getBonusMalusByName($bm);
	                   array_push($bmlistInject, $bmObj);
	               }
               }
               
               $traitlistInject = array();
               if(!empty($_POST['selectedTrait'])){
	               foreach ($_POST['selectedTrait'] as $trait) {
	                   $traitObj = $epListP->getTraitByName($trait);
	                   array_push($traitlistInject, $traitObj);
	               }
               }
               
               $gearlistInject = array();
               if(!empty($_POST['selectedGear'])){
	               foreach ($_POST['selectedGear'] as $gear) {
	                   $gearObj = $epListP->getGearByName($gear);
	                   array_push($gearlistInject, $gearObj);
	               }
               }

               $newMorph = new EPMorph($_POST['name'],
                                       $_POST['morphType'],
                                       0, 
                                       EPMorph::$GENDER_NONE, 
                                       $_POST['maxApt'], 
                                       $_POST['dur'], 
                                       $_POST['cost'], 
                                       $traitlistInject, 
                                       $gearlistInject, 
                                       $bmlistInject,
                                       $_POST['desc'],
                                       "",
                                       "",
                                       $_POST['credcost']);
               
               if(!$persistManager->persistMorph($newMorph)){
                       echo $persistManager->getLastError();
                       echo "<br><br>";
                } 
                else{
                    $_SESSION['morphList'] = $epListP->getListMorph();
                    echo "<b> Morph Added ! </b><br><br>"; 
                }      
           }
           else if(isset($_POST['deleteMorph'])){
               
               if(!$persistManager->deleteMorph($_POST['deleteMorph'])){
                       echo $persistManager->getLastError();
                       echo "<br><br>";
                } 
                else{
                    $_SESSION['morphList'] = $epListP->getListMorph();
                    echo "<b> Morph Deleted ! </b><br><br>"; 
                }  
               
           }
           
           
        ?>
        <form action="./morph.php" method="post">
            <input type="hidden" name="addMorph" value="yep">
            <label>Morph name<input type="text" name="name"></label>
            <br>
            <label>Description</label>
            <br>
            <textarea name="desc" rows="20" cols="50"></textarea>
            <br>
            <label>Morph Type </label>
            <select name="morphType">
                <option value="<?php echo EPMorph::$BIOMORPH ?>">Biomorph</option> 
                <option value="<?php echo EPMorph::$SYNTHMORPH ?>">Syntomorph</option> 
                <option value="<?php echo EPMorph::$PODMORPH ?>">Pod</option> 
                <option value="<?php echo EPMorph::$INFOMORPH ?>">Infomorph</option> 
            </select>
            <br>
            <label>Max Aptitude<input type="text" name="maxApt"></label>
            <br>
            <label>Durability<input type="text" name="dur"></label>
            <br>
            <label>CP Cost<input type="text" name="cost"></label>
            <br>
            <label>Cost Type </label>
            <select name="credcost">
                <option value="<?php echo EPCreditCost::$TRIVIAL ?>">Trivial</option> 
                <option value="<?php echo EPCreditCost::$MODERATE ?>">Moderate</option>
                <option value="<?php echo EPCreditCost::$LOW ?>">Low</option>
                <option value="<?php echo EPCreditCost::$HIGH ?>">High</option>
                <option value="<?php echo EPCreditCost::$EXPENSIVE ?>">Expensive</option>
                <option value="<?php echo EPCreditCost::$VERY_EXPENSIVE ?>">Very Expensive</option>
                <option value="<?php echo EPCreditCost::$EXTREMELY_EXPENSIVE ?>">Extremely Expensive</option>
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
            <label>Traits</label>
            <br>
            <select name="selectedTrait[]" multiple>
                <?php    
                     foreach($_SESSION['traitList'] as $m){
                       echo "<option value='".$m->name."'>".$m->name."</option> ";  
                     }
                ?>
            </select>
            <br>
            <label>Gear</label>
            <br>
            <select name="selectedGear[]" multiple>
                <?php    
                     foreach($_SESSION['gearList'] as $m){
                       echo "<option value='".$m->name."'>".$m->name."</option> ";  
                     }
                ?>
            </select>
            <br>
            <br>
            <input type="submit" value="Insert">
        </form>
        
        <p>Morph on database</p>
        <table border = "1">
          <tr>
            <th>Name</th>
            <th>Description</th>
            <th>Type</th>
            <th>Max Aptitude</th>
            <th>Durability</th>
            <th>Cost CP</th>
            <th>Cost Credit</th>
            <th>Bonus Malus</th>
            <th>Gears</th>
            <th>Traits</th>
            <th>DELETE FROM DATABASE</th>
          </tr>
          
            <?php    
                  foreach($_SESSION['morphList'] as $m){
                    echo "<tr>";
                    echo "<td>".$m->name."</td>"; 
                    echo "<td>".$m->description."</td>";
                        if($m->morphType == EPMorph::$BIOMORPH) $type = "Biomorph";
                        else if($m->morphType == EPMorph::$SYNTHMORPH) $type = "Syntomorph";
                        else if($m->morphType == EPMorph::$PODMORPH) $type = "Pod";
                        else $type = "ERROR !";
                    echo "<td>".$type."</td>";
                    echo "<td>".$m->maxApptitude."</td>";
                    echo "<td>".$m->durability."</td>";
                      
                    echo "<td>".$m->cpCost."</td>";
                    echo "<td>".$m->getCost()."</td>";
                    
                    $bonusMalus = "";
                    foreach($m->bonusMalus as $bm){
                       $bonusMalus .= $bm->name." | ";  
                     }
                    echo "<td>".$bonusMalus."</td>";
                    
                    $gears = "";
                    foreach($m->gears as $g){
                       $gears .= $g->name." | ";  
                     }
                    echo "<td>".$gears."</td>";
                    
                    $traits = "";
                    foreach($m->traits as $t){
                       $traits .= $t->name." | ";  
                     }
                    echo "<td>".$traits."</td>";
                   
                    
                    echo "<td><form action='./morph.php' method='post'><input type='hidden' name='deleteMorph' value='".$m->name."'><input type='submit' value='Delete this line'></form></td>";
                    echo "</tr>";
                  }
             ?>            
        </table>
    </body>
</html>
