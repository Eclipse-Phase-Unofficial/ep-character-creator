
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Manage Skill Prefix</title>
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
           
           $epListP = new EPListProvider('../php/config.ini');
           $persistManager = new EPPersistentDataManager('../php/config.ini');
           $_SESSION['prefixList'] = $epListP->getListPrefix();
           $_SESSION['aptitudesList'] = $epListP->getListAptitudes();
           
           if(isset($_POST['addPrefix'])){
             
               if(!$persistManager->persistSkillPrefix($_POST['name'],$_POST['apt'],$_POST['type'],$_POST['desc'])){
                       echo $persistManager->getLastError();
                       echo "<br><br>";
                } 
                else{
                    $_SESSION['prefixList'] = $epListP->getListPrefix();
                    echo "<b> Skill Prefix Added ! </b><br><br>"; 
                }      
           }
           else if(isset($_POST['deletePrefix'])){
               
               if(!$persistManager->deleteSkillPrefix($_POST['deletePrefix'])){
                       echo $persistManager->getLastError();
                       echo "<br><br>";
                } 
                else{
                    $_SESSION['prefixList'] = $epListP->getListPrefix();
                    echo "<b> Skill Prefix Deleted ! </b><br><br>"; 
                }  
               
           }
           
           
        ?>
        <form action="./skillPrefix.php" method="post">
            <input type="hidden" name="addPrefix" value="yep">
            <label>Prefix name<input type="text" name="name"></label>
             <br>
             <label>Description</label>
            <br>
            <textarea name="desc" rows="20" cols="50"></textarea>
            <br>
            <label>Linked Aptitude</label>
            <select name="apt">
                <?php
                     foreach($_SESSION['aptitudesList'] as $m){
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

            <br>
            <br>
            <input type="submit" value="Insert">
        </form>
        
        <p>Skill Prefix on database</p>
        <table border = "1">
          <tr>
            <th>Name</th>
            <th>Description</th>
            <th>Linked Apt</th>
            <th>Type</th>
            <th>DELETE FROM DATABASE</th>
          </tr>
          
            <?php    
                  foreach($_SESSION['prefixList'] as $m){
                    echo "<tr>";
                    echo "<td>".$m."</td>";
                    echo "<td>".$epListP->getPrefixDescription($m)."</td>";
                    echo "<td>".$epListP->getAptForPrefix($m)."</td>";
                    echo "<td>".$epListP->getTypeForPrefix($m)."</td>";
                    echo "<td><form action='./skillPrefix.php' method='post'><input type='hidden' name='deletePrefix' value='".$m."'><input type='submit' value='Delete this line'></form></td>";
                    echo "</tr>";
                  }
             ?>            
        </table>
    </body>
</html>
