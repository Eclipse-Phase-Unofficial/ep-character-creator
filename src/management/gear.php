
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Manage Gear</title>
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
           require_once '../php/EPGear.php';
           
           $epListP = new EPListProvider('../php/config.ini');
           $persistManager = new EPPersistentDataManager('../php/config.ini');
           $_SESSION['gearList'] = $epListP->getListGears();
           $_SESSION['bmList'] = $epListP->getListBonusMalus();           
           
           if(isset($_POST['addGear'])){
               
              $bmlistInject = array();
              if(isset($_POST['selectedBM'])){
	               foreach ($_POST['selectedBM'] as $bm) {
	                   $bmObj = $epListP->getBonusMalusByName($bm);
	                   array_push($bmlistInject, $bmObj);
	               }
               }
               
               $arm_k = "0";if(!empty($_POST['armorKinetic'])){$arm_k = $_POST['armorKinetic'];}
               $arm_e = "0";if(!empty($_POST['armorEnergy'])){$arm_e = $_POST['armorEnergy'];}
               $arm_p = "0";if(!empty($_POST['armorPenetration'])){$arm_p = $_POST['armorPenetration'];}
               $degat = "0";if(!empty($_POST['degat'])){$degat = $_POST['degat'];}
               
               if($_POST['unique'] == "Y") $u = true;
               else $u = false;

                $newGear = new EPGear($_POST['name'], 
                                      $_POST['desc'], 
                                      $_POST['gType'], 
                                      $_POST['cost'],
                                      $arm_k, 
                                      $arm_e, 
                                      $degat, 
                                      $arm_p, 
                                      $bmlistInject,
                                      $_POST['isFor']);
                $newGear->unique = $u;
               
               if(!$persistManager->persistGear($newGear)){
                       echo $persistManager->getLastError();
                       echo "<br><br>";
                } 
                else{
                    $_SESSION['gearList'] = $epListP->getListGears();
                    echo "<b> Gear Added ! </b><br><br>"; 
                }      
           }
           else if(isset($_POST['deleteGear'])){
               
               if(!$persistManager->deleteGear($_POST['deleteGear'])){
                       echo $persistManager->getLastError();
                       echo "<br><br>";
                } 
                else{
                    $_SESSION['gearList'] = $epListP->getListGears();
                    echo "<b> Gear Deleted ! </b><br><br>"; 
                }  
               
           }
           
           
        ?>
        <form action="./gear.php" method="post">
            <input type="hidden" name="addGear" value="yep">
            <label>Gear name<input type="text" name="name"></label>
            <br>
            <label>Description</label>
            <br>
            <textarea name="desc" rows="20" cols="50"></textarea>
            <br>
            <label>Gear Type </label>
            <select name="gType">
                <option value="<?php echo EPGear::$SOFT_GEAR ?>">Software</option> 
                <option value="<?php echo EPGear::$STANDARD_GEAR ?>">Standard</option>
                <option value="<?php echo EPGear::$WEAPON_ENERGY_GEAR ?>">Weapon Energy</option>
                <option value="<?php echo EPGear::$WEAPON_KINETIC_GEAR ?>">Weapon Kinetic</option>
                <option value="<?php echo EPGear::$WEAPON_MELEE_GEAR ?>">Weapon Melee</option>
                <option value="<?php echo EPGear::$WEAPON_AMMUNITION ?>">Weapon Ammunition</option>
                <option value="<?php echo EPGear::$WEAPON_SPRAY_GEAR ?>">Weapon Spray</option>
                <option value="<?php echo EPGear::$WEAPON_EXPLOSIVE_GEAR ?>">Weapon Explosive</option>
                <option value="<?php echo EPGear::$WEAPON_SEEKER_GEAR ?>">Weapon seeker</option>
                <option value="<?php echo EPGear::$WEAPON_ACCESSORY ?>">Weapon Accessory</option>
                <option value="<?php echo EPGear::$ARMOR_GEAR ?>">Armor</option>
                <option value="<?php echo EPGear::$IMPLANT_GEAR ?>">Implants</option>
                <option value="<?php echo EPGear::$DRUG_GEAR ?>">Drug</option>
                <option value="<?php echo EPGear::$POISON_GEAR ?>">Poison</option>
                <option value="<?php echo EPGear::$CHEMICALS_GEAR ?>">Chimical</option>
                <option value="<?php echo EPGear::$PET_GEAR ?>">Pet</option>
                <option value="<?php echo EPGear::$VEHICLES_GEAR ?>">Vehicles</option>
                <option value="<?php echo EPGear::$ROBOT_GEAR ?>">Robot</option>
            </select>
            <br>
             <label>Restriction</label>
            <select name="isFor">
                <option value="<?php echo EPGear::$CAN_USE_EVERYBODY ?>">Everybody</option> 
                <option value="<?php echo EPGear::$CAN_USE_BIO ?>">Biomorph</option> 
                <option value="<?php echo EPGear::$CAN_USE_SYNTH ?>">Synthomorph</option> 
                <option value="<?php echo EPGear::$CAN_USE_POD ?>">Pod</option> 
                <option value="<?php echo EPGear::$CAN_USE_BIO_POD ?>">Biomorph & Pod</option> 
                <option value="<?php echo EPGear::$CAN_USE_SYNTH_POD ?>">Synthomorph & Pod</option> 
                <option value="<?php echo EPGear::$CAN_USE_CREATE_ONLY ?>">Reference Only</option>
            </select>
            <br>
            <label>Unique</label>
            <select name="unique">
                <option value="Y">Yes</option> 
                <option value="N">No</option> 
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
            <label>Cost Type </label>
            <select name="cost">
                <option value="<?php echo EPCreditCost::$TRIVIAL ?>">Trivial</option> 
                <option value="<?php echo EPCreditCost::$MODERATE ?>">Moderate</option>
                <option value="<?php echo EPCreditCost::$LOW ?>">Low</option>
                <option value="<?php echo EPCreditCost::$HIGH ?>">High</option>
                <option value="<?php echo EPCreditCost::$EXPENSIVE ?>">Expensive</option>
                <option value="<?php echo EPCreditCost::$VERY_EXPENSIVE ?>">Very Expensive</option>
                <option value="<?php echo EPCreditCost::$EXTREMELY_EXPENSIVE ?>">Extremely Expensive</option>
            </select>
            <br>
            <label>Armor Value - Kinetic<input type="text" name="armorKinetic"></label>
            <br>
            <label>Armor Value - Energy<input type="text" name="armorEnergy"></label>
            <br>
            <label>Armor Value - Penetration<input type="text" name="armorPenetration"></label>
            <br>
            <label>Degat<input type="text" name="degat"></label>
            <br>
            <br>
            <input type="submit" value="Insert">
        </form>
        
        <p>Gear on database</p>
        <table border = "1">
          <tr>
            <th>Name</th>
            <th>Description</th>
            <th>Type</th>
            <th>Unique</th>
            <th>Cost</th>
            <th>Armor Kinetic</th>
            <th>Armor Energy</th>
            <th>Armor Penetration</th>
            <th>Degat</th>
            <th>Is For</th>
            <th>Bonus Malus</th>
            <th>DELETE FROM DATABASE</th>
          </tr>
          
            <?php    
                  foreach($_SESSION['gearList'] as $m){
                    echo "<tr>";
                    echo "<td>".$m->name."</td>"; 
                    echo "<td>".$m->description."</td>";
                        if($m->gearType == EPGear::$SOFT_GEAR) $type = "Software";
                        else if($m->gearType == EPGear::$STANDARD_GEAR) $type = "Standard";
                        else if($m->gearType == EPGear::$WEAPON_ENERGY_GEAR) $type = "Weapon Energy";
                        else if($m->gearType == EPGear::$WEAPON_KINETIC_GEAR) $type = "Weapon Kinetic";
                        else if($m->gearType == EPGear::$WEAPON_MELEE_GEAR) $type = "Weapon Melee";
                        else if($m->gearType == EPGear::$WEAPON_AMMUNITION) $type = "Weapon Ammunition";
                        else if($m->gearType == EPGear::$WEAPON_ACCESSORY) $type = "Weapon Accessory";
                        else if($m->gearType == EPGear::$IMPLANT_GEAR) $type = "Implants";
                        else if($m->gearType == EPGear::$ARMOR_GEAR) $type = "Armor";
                        else if($m->gearType == EPGear::$DRUG_GEAR) $type = "Drug";
                        else if($m->gearType == EPGear::$POISON_GEAR) $type = "Poison";
                        else if($m->gearType == EPGear::$CHEMICALS_GEAR) $type = "Chemical";
                        else if($m->gearType == EPGear::$PET_GEAR) $type = "Pet";
                        else if($m->gearType == EPGear::$VEHICLES_GEAR) $type = "Vehicles";
                        else if($m->gearType == EPGear::$ROBOT_GEAR) $type = "Robot";
                        else $type = "ERROR !";
                    echo "<td>".$type."</td>";
                    	if($m->unique) $unik = "Y";
                    	else $unik = "N";
                    echo "<td>".$unik."</td>";
                        if($m->cost == EPCreditCost::$LOW) $cost = "Low";
                        else if($m->cost == EPCreditCost::$TRIVIAL) $cost = "Trivial";
                        else if($m->cost == EPCreditCost::$MODERATE) $cost = "Moderate";
                        else if($m->cost == EPCreditCost::$HIGH) $cost = "High";
                        else if($m->cost == EPCreditCost::$EXPENSIVE) $cost = "Expensive";
                        else if($m->cost == EPCreditCost::$VERY_EXPENSIVE) $cost = "Very Expensive";
                        else if($m->cost == EPCreditCost::$EXTREMELY_EXPENSIVE) $cost = "Extremely Expensive";
                        else $cost = "ERROR !";
                    echo "<td>".$cost."</td>";
                    
                    echo "<td>".$m->armorKinetic."</td>";
                    echo "<td>".$m->armorEnergy."</td>";
                    echo "<td>".$m->armorPenetration."</td>";
                    echo "<td>".$m->degat."</td>";
                    echo "<td>".$m->gearRestriction."</td>";
                    
                    $bonusMalus = "";
                    foreach($m->bonusMalus as $bm){
                       $bonusMalus .= $bm->name." | ";  
                     }
                    echo "<td>".$bonusMalus."</td>";
                   
                    
                    echo "<td><form action='./gear.php' method='post'><input type='hidden' name='deleteGear' value='".$m->name."'><input type='submit' value='Delete this line'></form></td>";
                    echo "</tr>";
                  }
             ?>            
        </table>
    </body>
</html>
