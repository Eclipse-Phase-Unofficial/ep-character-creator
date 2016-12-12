
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Manage Bonus / Malus</title>
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
           
           $epListP = new EPListProvider('../php/config.ini');
           $persistManager = new EPPersistentDataManager('../php/config.ini');
           $_SESSION['bonusmalusList'] = $epListP->getListBonusMalus();
           if( $_SESSION['bonusmalusList'] == null) echo $epListP->getLastError();
           $_SESSION['groupsList'] = $epListP->getListGroups();
           
           if(isset($_POST['addBonusmalus'])){
              
              $glistInject = array();
              if(isset($_POST['selectedGroups'])){
		               foreach ($_POST['selectedGroups'] as $g) {
		                   array_push($glistInject, $g);
		               }
		      }
               
               $arrayNewGroups = explode(";", $_POST['newGroups']);
               if($arrayNewGroups != null || $arrayNewGroups != ""){
	               foreach ($arrayNewGroups as $h) {
	               		if(!empty($h)) array_push($glistInject, $h);
	               }
               }
               
               
              $bmlistInject = array();
              if(isset($_POST['selectedBms'])){
		               foreach ($_POST['selectedBms'] as $g) {
		               	$bmToAdd = $epListP->getBonusMalusByName($g);
		                   array_push($bmlistInject, $bmToAdd);
		               }
		      }
              
			   $name = "";
			   if(isset($_POST['name']) &&  $_POST['name'] != null && $_POST['name'] != ""){
				   $name = $_POST['name'];
			   }
			   else{
				   die("Name is mandatory");
			   }
			   
			   $apply = "";
			   if(isset($_POST['apply']) &&  $_POST['apply'] != null && $_POST['apply'] != ""){
				   $apply = $_POST['apply'];
			   }
			   else{
				   die("No Apply !? WTF dude !");
			   }
			   
			   $value = "";
			   if(isset($_POST['value']) &&  $_POST['value'] != null && $_POST['value'] != ""){
				   $value = $_POST['value'];
			   }
			   else{
				   $value = 0;
			   }
			   
			   $target = "";
			   if(isset($_POST['target']) &&  $_POST['target'] != null && $_POST['target'] != ""){
				   $target = $_POST['target'];
			   }
			   else{
				   $target = "";
			   }
			   
			   $desc = "";
			   if(isset($_POST['desc']) &&  $_POST['desc'] != null && $_POST['desc'] != ""){
				   $desc = $_POST['desc'];
			   }
			   else{
				   $desc = "";
			   }
			   
			   $onCost = "0";
			   if(isset($_POST['onCost']) &&  $_POST['onCost'] != null && $_POST['onCost'] != ""){
				   $onCost = $_POST['onCost'];
			   }
			   else{
				   $onCost = "0";
			   }
			   
			   $targetforChoice = "";
			   if(isset($_POST['tarForCh']) &&  $_POST['tarForCh'] != null && $_POST['tarForCh'] != ""){
				   $targetforChoice = $_POST['tarForCh'];
			   }
			   else{
				   $targetforChoice = "";
			   }
			   
			   $typeTar = "";
			   if(isset($_POST['typeTar']) &&  $_POST['typeTar'] != null && $_POST['typeTar'] != ""){
				   $typeTar = $_POST['typeTar'];
			   }
			   else{
				   $typeTar = "";
			   }
			   $multiOccur = "0";
			   if(isset($_POST['multiOcc']) &&  $_POST['multiOcc'] != null && $_POST['multiOcc'] != ""){
				   $multiOccur = $_POST['multiOcc'];
			   }
			   else{
				   $multiOccur = "0";
			   }
                                                
               
                $newBonusMalus = new EPBonusMalus($name, 
                                                  $apply,
                                                  $value,
                                                  $target,
                                                  $desc, 
                                                  $glistInject,
                                                  $onCost,
                                                  $targetforChoice,
                                                  $typeTar,
                                                  $bmlistInject,
                                                  $multiOccur
                                                  );
                                                                 
               if(!$persistManager->persistBonusMalus($newBonusMalus)){
                       echo $persistManager->getLastError();
                       echo "<br><br>";
                } 
                else{
                    $_SESSION['bonusmalusList'] = $epListP->getListBonusMalus();
                    echo "<b> Bonus Malus Added ! </b><br><br>"; 
                }      
           }
           else if(isset($_POST['deleteBonusMalus'])){
               
               if(!$persistManager->deleteBonusMalus($_POST['deleteBonusMalus'])){
                       echo $persistManager->getLastError();
                       echo "<br><br>";
                } 
                else{
                    $_SESSION['bonusmalusList'] = $epListP->getListBonusMalus();
                    echo "<b> Bonus Malus Deleted ! </b><br><br>"; 
                }  
               
           }
           
           
        ?>
        <form action="./bonusmalus.php" method="post">
            <input type="hidden" name="addBonusmalus" value="yep">
            <label>Bonus Malus name<input type="text" name="name"></label>
            <br>
            <label>Description</label>
            <br>
            <textarea name="desc" rows="20" cols="50"></textarea>
            <br>
            <label>Bonus Malus apply </label>
            <select name="apply">
                <option value="<?php echo EPBonusMalus::$ON_APTITUDE ?>">On Aptitude</option>
                <option value="<?php echo EPBonusMalus::$ON_APTITUDE_MORPH_MAX ?>">On Aptitude morph maximum</option>
                <option value="<?php echo EPBonusMalus::$ON_APTITUDE_EGO_MAX ?>">On Aptitude ego maximum</option>
                <option value="<?php echo EPBonusMalus::$ON_APTITUDE_MORPH_MIN ?>">On Aptitude morph minimum</option>
                <option value="<?php echo EPBonusMalus::$ON_APTITUDE_EGO_MIN ?>">On Aptitude ego minimum</option>
                <option value="<?php echo EPBonusMalus::$ON_STAT ?>">On Stat</option>
                <option value="<?php echo EPBonusMalus::$ON_STAT_MULTIPLI ?>">On Stat multiplicator</option>
                <option value="<?php echo EPBonusMalus::$ON_ARMOR ?>">On Armor</option>
                <option value="<?php echo EPBonusMalus::$ON_ENERGY_ARMOR ?>">On Energy Armor</option>
                <option value="<?php echo EPBonusMalus::$ON_ENERGY_WEAPON_DAMAGE ?>">On Energy weapon damage</option>
                <option value="<?php echo EPBonusMalus::$ON_GROUP ?>">On Group</option>
                <option value="<?php echo EPBonusMalus::$ON_KINETIC_ARMOR ?>">On Kinetic armor</option>
                <option value="<?php echo EPBonusMalus::$ON_KINETIC_WEAPON_DAMAGE ?>">On Kinetic weapon damage</option>
                <option value="<?php echo EPBonusMalus::$ON_MELEE_WEAPON_DAMAGE ?>">On Melee weapon damage</option>
                <option value="<?php echo EPBonusMalus::$ON_REPUTATION ?>">On Reputation</option>
                <option value="<?php echo EPBonusMalus::$ON_REPUTATION_MAX ?>">Reputation maximum</option>
                <option value="<?php echo EPBonusMalus::$ON_REPUTATION_POINTS ?>">On Reputations points</option>
                <option value="<?php echo EPBonusMalus::$ON_SKILL ?>">On Skill</option>
                <option value="<?php echo EPBonusMalus::$ON_SKILL_MAX ?>">On Skill maximum</option>
                <option value="<?php echo EPBonusMalus::$ON_SKILL_PREFIX ?>">On Skill prefix</option>
                <option value="<?php echo EPBonusMalus::$ON_SKILL_TYPE ?>">On Skill type</option>
                <option value="<?php echo EPBonusMalus::$ON_CREDIT ?>">On Credit</option>
                <option value="<?php echo EPBonusMalus::$ON_MORPH ?>">On Morph</option>
                <option value="<?php echo EPBonusMalus::$ON_IMPLANT ?>">On Implant</option>
                <option value="<?php echo EPBonusMalus::$DESCRIPTIVE_ONLY ?>">Descriptive Only</option>
                <option value="<?php echo EPBonusMalus::$MULTIPLE ?>">MULTIPLE</option>
            </select>
            <br>
            <label>Bonus Malus target name<input type="text" name="target"></label>
            <br>
            <br>
            <label>Bonus Malus Value<input type="text" name="value"></label>
            <br>
            <label>Group(s)</label>
            <br>
            <select name="selectedGroups[]" multiple>
                <?php    
                     foreach($_SESSION['groupsList'] as $m){
                       echo "<option value='".$m."'>".$m."</option> ";  
                     }
                ?>
            </select>
             <br>
             <label>New group(s) (Separate with ";")</label>
            <br>
            <textarea name="newGroups" rows="1" cols="50"></textarea>
            <br>
            <label>On cost</label>  <select name="onCost">
             							<option value="false">false</option>
             							<option value="true">true</option>
             						</select>
            <br>
            <label>Target for choice</label>
             <select name="tarForCh">
             	<option value="">none</option>
                <option value="<?php echo EPBonusMalus::$ON_APTITUDE ?>">On Aptitude</option>
                <option value="<?php echo EPBonusMalus::$ON_STAT ?>">On Stat</option>
                <option value="<?php echo EPBonusMalus::$ON_ARMOR ?>">On Armor</option>
                <option value="<?php echo EPBonusMalus::$ON_ENERGY_ARMOR ?>">On Energy Armor</option>
                <option value="<?php echo EPBonusMalus::$ON_ENERGY_WEAPON_DAMAGE ?>">On Energy weapon damage</option>
                <option value="<?php echo EPBonusMalus::$ON_GROUP ?>">On Group</option>
                <option value="<?php echo EPBonusMalus::$ON_KINETIC_ARMOR ?>">On Kinetic armor</option>
                <option value="<?php echo EPBonusMalus::$ON_KINETIC_WEAPON_DAMAGE ?>">On Kinetic weapon damage</option>
                <option value="<?php echo EPBonusMalus::$ON_MELEE_WEAPON_DAMAGE ?>">On Melee weapon damage</option>
                <option value="<?php echo EPBonusMalus::$ON_REPUTATION ?>">On Reputation</option>
                <option value="<?php echo EPBonusMalus::$ON_SKILL_WITH_PREFIX ?>">On Skill with prefix</option>
				<option value="<?php echo EPBonusMalus::$ON_SKILL_ACTIVE?>">On Skill active</option>
				<option value="<?php echo EPBonusMalus::$ON_SKILL_KNOWLEDGE?>">On Skill knowlege</option>
				<option value="<?php echo EPBonusMalus::$ON_SKILL_ACTIVE_AND_KNOWLEDGE?>">On Skill active and knowlege</option>
                <option value="<?php echo EPBonusMalus::$ON_CREDIT ?>">On Credit</option>
                <option value="<?php echo EPBonusMalus::$ON_MORPH ?>">On Morph</option>
                <option value="<?php echo EPBonusMalus::$DESCRIPTIVE_ONLY ?>">Descriptive Only</option>
                <option value="<?php echo EPBonusMalus::$ON_REPUTATION_POINTS ?>">On Reputations points</option>
                <option value="<?php echo EPBonusMalus::$MULTIPLE ?>">MULTIPLE</option>
            </select>
            <br>
            <label>Type target<input type="text" name="typeTar"></label>
            <br>
             <label>Multi choice Bonus malus (s)</label>
            <br>
            <select name="selectedBms[]" multiple>
                <?php    
                     foreach($_SESSION['bonusmalusList'] as $m){
                       echo "<option value='".$m->name."'>".$m->name."</option> ";  
                     }
                ?>
            </select>
             <br>
             <label>Multi occur<input type="text" name="multiOcc"></label>
            <br>
            <br><br>
            <input type="submit" value="Insert">
        </form>
        
        <p>Aptitude on database</p>
        <table border = "1">
          <tr>
            <th>Name</th>
            <th>Description</th>
            <th>Apply on</th>
            <th>Target</th>
            <th>Value</th>
            <th>Group(s)</th> 
            <th>On cost</th>
            <th>target for choice</th>
            <th>type target</th>
            <th>multi choice bonus malus</th>
            <th>multi occur</th>
            <th>DELETE FROM DATABASE</th>
          </tr>
          
            <?php    
                  foreach($_SESSION['bonusmalusList'] as $m){
                    echo "<tr>";
                    echo "<td>".$m->name."</td>"; 
                    echo "<td>".$m->description."</td>";
                        if($m->bonusMalusType == EPBonusMalus::$ON_APTITUDE) $type = "Aptitude";
                        else if($m->bonusMalusType == EPBonusMalus::$ON_APTITUDE_MORPH_MAX) $type = "Aptitude morph max";
                        else if($m->bonusMalusType == EPBonusMalus::$ON_APTITUDE_EGO_MAX) $type = "Aptitude ego max";
                        else if($m->bonusMalusType == EPBonusMalus::$ON_APTITUDE_MORPH_MIN) $type = "Aptitude morph min";
                        else if($m->bonusMalusType == EPBonusMalus::$ON_APTITUDE_EGO_MIN) $type = "Aptitude ego min";
                        else if($m->bonusMalusType == EPBonusMalus::$ON_STAT) $type = "Stat";
                        else if($m->bonusMalusType == EPBonusMalus::$ON_ARMOR) $type = "Armor";
                        else if($m->bonusMalusType == EPBonusMalus::$ON_ENERGY_ARMOR) $type = "Energy Armor";
                        else if($m->bonusMalusType == EPBonusMalus::$ON_ENERGY_WEAPON_DAMAGE) $type = "Energy weapon damage";
                        else if($m->bonusMalusType == EPBonusMalus::$ON_GROUP) $type = "Group";
                        else if($m->bonusMalusType == EPBonusMalus::$ON_KINETIC_ARMOR) $type = "Kinetic armor";
                        else if($m->bonusMalusType == EPBonusMalus::$ON_KINETIC_WEAPON_DAMAGE) $type = "Kinetic ewapon damage";
                        else if($m->bonusMalusType == EPBonusMalus::$ON_MELEE_WEAPON_DAMAGE) $type = "Melee weapon damage";
                        else if($m->bonusMalusType == EPBonusMalus::$ON_REPUTATION) $type = "Reputation";
                        else if($m->bonusMalusType == EPBonusMalus::$ON_REPUTATION_MAX) $type = "Reputation maximum";
                        else if($m->bonusMalusType == EPBonusMalus::$ON_REPUTATION_POINTS) $type = "On reputation points";
                        else if($m->bonusMalusType == EPBonusMalus::$ON_SKILL) $type = "Skill";
                        else if($m->bonusMalusType == EPBonusMalus::$ON_SKILL_MAX) $type = "Skill Max";
                        else if($m->bonusMalusType == EPBonusMalus::$ON_SKILL_PREFIX) $type = "Skill Prefix";
                        else if($m->bonusMalusType == EPBonusMalus::$ON_SKILL_TYPE) $type = "Skill Type";
                        else if($m->bonusMalusType == EPBonusMalus::$ON_CREDIT) $type = "On Credit";
                        else if($m->bonusMalusType == EPBonusMalus::$DESCRIPTIVE_ONLY) $type = "Descriptive only";
                        else if($m->bonusMalusType == EPBonusMalus::$ON_MORPH) $type = "Morph";
                        else if($m->bonusMalusType == EPBonusMalus::$ON_IMPLANT) $type = "Implant";
                        else if($m->bonusMalusType == EPBonusMalus::$MULTIPLE) $type = "MULTIPLE";
                        else $type = "ERROR !";
                    echo "<td>".$type."</td>";
                    echo "<td>".$m->forTargetNamed."</td>";
                    echo "<td>".$m->value."</td>";
                    $groups = "";
                    foreach($m->groups as $g){
                       $groups .= $g." | ";  
                     }
                    echo "<td>".$groups."</td>";
                    echo "<td>".$m->onCost."</td>";
                    echo "<td>".$m->targetForChoice."</td>";
                    echo "<td>".$m->typeTarget."</td>";
                     $multibm = "";
                    foreach($m->bonusMalusTypes as $bmg){
                       $multibm .= $bmg->name." | ";  
                     }
                    echo "<td>".$multibm."</td>";
                    echo "<td>".$m->multi_occurence."</td>";
                    echo "<td><form action='./bonusmalus.php' method='post'><input type='hidden' name='deleteBonusMalus' value='".$m->name."'><input type='submit' value='Delete this line'></form></td>";
                    echo "</tr>";
                  }
             ?>            
        </table>
    </body>
</html>
