
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Manage Skills</title>
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
           require_once '../php/EPSkill.php';
           require_once '../php/EPAptitude.php';
           
           $epListP = new EPListProvider('../php/config.ini');
           $persistManager = new EPPersistentDataManager('../php/config.ini');
           $_SESSION['prefixList'] = $epListP->getListPrefix();
           $_SESSION['groupsList'] = $epListP->getListGroups();
           $_SESSION['skillList'] = $epListP->getListSkills($epListP->getListAptitudes());
           
           if(isset($_POST['addSkill'])){
               
               $glistInject = array();
               if(isset($_POST['selectedGroups'])){
	               foreach ($_POST['selectedGroups'] as $g) {
	                   array_push($glistInject, $g);
	               }
               }
               
               $arrayNewGroups = explode(";", $_POST['newGroups']);
               foreach ($arrayNewGroups as $h) {
                   array_push($glistInject, $h);
               }
               
               $skill = new EPSkill($_POST['name'], 
                                    $_POST['desc'], 
                                    $epListP->getAptitudeByAbbreviation($_POST['linkedApt']), 
                                    $_POST['type'], 
                                    $_POST['defaultable'], 
                                    $_POST['prefix'], 
                                    $glistInject);
               
               if(!$persistManager->persistSkill($skill)){
                       echo $persistManager->getLastError();
                       echo "<br><br>";
                } 
                else{
                    $_SESSION['skillList'] = $epListP->getListSkills($epListP->getListAptitudes());
                    echo "<b> Skill Added ! </b><br><br>"; 
                }      
           }
           else if(isset($_POST['deleteSkill'])){
               
               if(!$persistManager->deleteSkill($_POST['deleteSkill'])){
                       echo $persistManager->getLastError();
                       echo "<br><br>";
                } 
                else{
                    $_SESSION['skillList'] = $epListP->getListSkills($epListP->getListAptitudes());
                    echo "<b> Skill Deleted ! </b><br><br>"; 
                }  
               
           }
           
           
        ?>
        <form action="skills.php" method="post">
            <input type="hidden" name="addSkill" value="yep">
            <label>Skill name<input type="text" name="name"></label>
            <br>
            <label>Description</label>
            <br>
            <textarea name="desc" rows="20" cols="50"></textarea>
            <br>
            <label>Linked Aptitude</label>
            <select name="linkedApt">
                <?php
                     foreach($epListP->getListAptitudes() as $m){
                       echo "<option value='".$m->abbreviation."'>".$m->name."</option> ";  
                     }
                ?>
            </select>
            <br>
            <label>Skill type </label>
            <select name="type">
                <option value="<?php echo EPSkill::$ACTIVE_SKILL_TYPE ?>">Active</option> 
                <option value="<?php echo EPSkill::$KNOWLEDGE_SKILL_TYPE ?>">Knowledge</option>
            </select>
            <br>
            <label>Defaultable</label>
            <input type="radio" name="defaultable" id ="DefY" value="Y" checked><label for = "DefY">Yes</label>
            <input type="radio" name="defaultable" id ="DefN" value="N"><label for = "DefN">No</label>
            <br>
            <label>Prefix</label>
            <select name="prefix">
                <option value="" selected>No prefix</option> 
                <?php    
                     foreach($_SESSION['prefixList'] as $m){
                       echo "<option value='".$m."'>".$m."</option> ";  
                     }
                ?>
            </select>
            <br>
            <label>Existing Group(s)</label>
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
            <br><br>
            <input type="submit" value="Insert">
        </form>
        
        <p>Skill on database</p>
        <table border = "1">
          <tr>
            <th>Name</th>
            <th>Description</th>
            <th>Linked Aptitude</th>
            <th>Type</th>
            <th>Is Defaultable</th>
            <th>Skill Prefix</th>
            <th>Group(s)</th>
            <th>DELETE FROM DATABASE</th>
          </tr>
          
            <?php    
                  foreach($_SESSION['skillList'] as $m){
                    echo "<tr>";
                    echo "<td>".$m->name."</td>"; 
                    echo "<td>".$m->description."</td>";
                    echo "<td>".$m->linkedApt->name."</td>";
                        if($m->skillType == EPSkill::$ACTIVE_SKILL_TYPE) $type = "Active";
                        else if($m->skillType == EPSkill::$KNOWLEDGE_SKILL_TYPE) $type = "Knowledge";
                        else $type = "ERROR !";
                    echo "<td>".$type."</td>";
                        if($m->defaultable == EPSkill::$DEFAULTABLE) $def = "Yes";
                        else if($m->defaultable == EPSkill::$NO_DEFAULTABLE) $def = "No";
                        else $def = "ERROR !";
                    echo "<td>".$def."</td>";
                    echo "<td>".$m->prefix."</td>";
                    $groups = "";
                    foreach($m->groups as $g){
                       $groups .= $g." | ";  
                     }
                    echo "<td>".$groups."</td>";
                    echo "<td><form action='./skills.php' method='post'><input type='hidden' name='deleteSkill' value='".$m->name."'><input type='submit' value='Delete this line'></form></td>";
                    echo "</tr>";
                  }
             ?>            
        </table>
    </body>
</html>
