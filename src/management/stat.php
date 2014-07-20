
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Manage Stats</title>
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
           require_once '../php/EPStat.php';
           
           $epListP = new EPListProvider('../php/config.ini');
           $persistManager = new EPPersistentDataManager('../php/config.ini');
           $configValues = new EPConfigFile('./config.ini');
           $_SESSION['statList'] = $epListP->getListStats($configValues,$_SESSION['cc']);
           $_SESSION['groupsList'] = $epListP->getListGroups();
           
           if(isset($_POST['addStat'])){
               
               $glistInject = array();
               foreach ($_POST['selectedGroups'] as $g) {
                   array_push($glistInject, $g);
               }
               
               $arrayNewGroups = explode(";", $_POST['newGroups']);
               foreach ($arrayNewGroups as $h) {
                   array_push($glistInject, $h);
               }

               $newStat = new EPStat($_POST['name'], 
                                     $_POST['desc'], 
                                     $_POST['abr'], 
                                     $glistInject,
                                     0,
                                     $_SESSION['cc']);
               
               if(!$persistManager->persistStat($newStat)){
                       echo $persistManager->getLastError();
                       echo "<br><br>";
                } 
                else{
                    $_SESSION['statList'] = $epListP->getListStats($configValues,$_SESSION['cc']);
                    echo "<b> Stat Added ! </b><br><br>"; 
                }      
           }
           else if(isset($_POST['deleteStat'])){
               
               if(!$persistManager->deleteStat($_POST['deleteStat'])){
                       echo $persistManager->getLastError();
                       echo "<br><br>";
                } 
                else{
                    $_SESSION['statList'] = $epListP->getListStats($configValues,$_SESSION['cc']);
                    echo "<b> Stat Deleted ! </b><br><br>"; 
                }  
               
           }
           
           
        ?>
        <form action="./stat.php" method="post">
            <input type="hidden" name="addStat" value="yep">
            <label>Stat name<input type="text" name="name"></label>
            <br>
            <label>Description</label>
            <br>
            <textarea name="desc" rows="20" cols="50"></textarea>
            <br>
            <label>Stat Abreviation<input type="text" name="abr"></label>
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
            <br>
            <input type="submit" value="Insert">
        </form>
        
        <p>Stats on database</p>
        <table border = "1">
          <tr>
            <th>Name</th>
            <th>Description</th>
            <th>Abreviation</th>
            <th>Group(s)</th>
            <th>DELETE FROM DATABASE</th>
          </tr>
          
            <?php    
                  foreach($_SESSION['statList'] as $m){
                    echo "<tr>";
                    echo "<td>".$m->name."</td>"; 
                    echo "<td>".$m->description."</td>";
                    echo "<td>".$m->abbreviation."</td>";
                    $groups = "";
                    foreach($m->groups as $g){
                       $groups .= $g." | ";  
                     }
                    echo "<td>".$groups."</td>";                   
                    
                    echo "<td><form action='./stat.php' method='post'><input type='hidden' name='deleteStat' value='".$m->name."'><input type='submit' value='Delete this line'></form></td>";
                    echo "</tr>";
                  }
             ?>            
        </table>
    </body>
</html>
