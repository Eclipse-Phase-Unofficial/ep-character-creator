
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Manage Trait</title>
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
           require_once '../php/EPTrait.php';
           
           $epListP = new EPListProvider('../php/config.ini');
           $persistManager = new EPPersistentDataManager('../php/config.ini');
           $_SESSION['traitList'] = $epListP->getListTraits();
           $_SESSION['bmList'] = $epListP->getListBonusMalus();           
           
           if(isset($_POST['addTrait'])){
               
              $bmlistInject = array();
              if(isset($_POST['selectedBM'])){
	               foreach ($_POST['selectedBM'] as $bm) {
	                   $bmObj = $epListP->getBonusMalusByName($bm);
	                   array_push($bmlistInject, $bmObj);
	               }
               }

               $newTrait = new EPTrait($_POST['name'], 
                                       $_POST['desc'], 
                                       $_POST['side'], 
                                       $_POST['applyTo'], 
                                       $_POST['cost'], 
                                       $bmlistInject,
                                       $_POST['level'],
                                       $_POST['isFor']);
               
               if(!$persistManager->persistTrait($newTrait)){
                       echo $persistManager->getLastError();
                       echo "<br><br>";
                } 
                else{
                    $_SESSION['traitList'] = $epListP->getListTraits();
                    echo "<b> Trait Added ! </b><br><br>"; 
                }      
           }
           else if(isset($_POST['deleteTrait'])){
               
               if(!$persistManager->deleteTrait($_POST['deleteTrait'])){
                       echo $persistManager->getLastError();
                       echo "<br><br>";
                } 
                else{
                    $_SESSION['traitList'] = $epListP->getListTraits();
                    echo "<b> Trait Deleted ! </b><br><br>"; 
                }  
               
           }
           
           
        ?>
        <form action="trait.php" method="post">
            <input type="hidden" name="addTrait" value="yep">
            <label>Trait name<input type="text" name="name"></label>
            <br>
            <label>Description</label>
            <br>
            <textarea name="desc" rows="20" cols="50"></textarea>
            <br>
            <label>Trait Side </label>
            <select name="side">
                <option value="<?php echo EPTrait::$POSITIVE_TRAIT ?>">Positive</option> 
                <option value="<?php echo EPTrait::$NEGATIVE_TRAIT ?>">Negative</option> 
            </select>
             <br>
            <label>Trait Apply to </label>
            <select name="applyTo">
                <option value="<?php echo EPTrait::$MORPH_TRAIT ?>">Morph</option> 
                <option value="<?php echo EPTrait::$EGO_TRAIT ?>">Ego</option> 
            </select>
            <br>
             <label>If no ego trait is for </label>
            <select name="isFor">
                <option value="<?php echo EPTrait::$CAN_USE_EVERYBODY ?>">Everybody</option> 
                <option value="<?php echo EPTrait::$CAN_USE_BIO ?>">Biomorph</option> 
                <option value="<?php echo EPTrait::$CAN_USE_SYNTH ?>">Synthomorpn</option> 
                <option value="<?php echo EPTrait::$CAN_USE_POD ?>">Pod</option> 
            </select>
            <br>
            <label>Cost (CP)<input type="text" name="cost"></label>
            <br>
            <br>
            <label>Level<input type="number" name="level" min=1></label>
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
            <br>
            <input type="submit" value="Insert">
        </form>
        
        <p>Traits on database</p>
        <table border = "1">
          <tr>
            <th>Name</th>
            <th>Description</th>
            <th>Side</th>
            <th>Apply To</th>
            <th>Cost</th>
            <th>Level</th>
            <th>is For</th>
            <th>Bonus Malus</th>
            <th>DELETE FROM DATABASE</th>
          </tr>
          
            <?php    
                  foreach($_SESSION['traitList'] as $m){
                    echo "<tr>";
                    echo "<td>".$m->name."</td>"; 
                    echo "<td>".$m->description."</td>";
                        if($m->traitPosNeg == EPTrait::$POSITIVE_TRAIT) $type = "Positive";
                        else if($m->traitPosNeg == EPTrait::$NEGATIVE_TRAIT) $type = "Negative";
                        else $type = "ERROR !";
                    echo "<td>".$type."</td>";
                    
                        if($m->traitEgoMorph == EPTrait::$EGO_TRAIT) $cost = "Ego";
                        else if($m->traitEgoMorph == EPTrait::$MORPH_TRAIT) $cost = "Morph";
                        else $cost = "ERROR !";
                    echo "<td>".$cost."</td>";
                    
                    echo "<td>".$m->cpCost."</td>";
                    echo "<td>".$m->level."</td>";
                    echo "<td>".$m->canUse."</td>";
                    
                    $bonusMalus = "";
                    foreach($m->bonusMalus as $bm){
                       $bonusMalus .= $bm->name." | ";  
                     }
                    echo "<td>".$bonusMalus."</td>";
                   
                    
                    echo "<td><form action='./trait.php' method='post'><input type='hidden' name='deleteTrait' value='".$m->name."'><input type='submit' value='Delete this line'></form></td>";
                    echo "</tr>";
                  }
             ?>            
        </table>
    </body>
</html>
