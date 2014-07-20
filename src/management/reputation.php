
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Manage Reputation</title>
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
           require_once '../php/EPReputation.php';
           
           $epListP = new EPListProvider('../php/config.ini');
           $persistManager = new EPPersistentDataManager('../php/config.ini');
           $_SESSION['repList'] = $epListP->getListReputation();
           $_SESSION['groupsList'] = $epListP->getListGroups();
           
           if(isset($_POST['addRep'])){
               
               $glistInject = array();
               foreach ($_POST['selectedGroups'] as $g) {
                   array_push($glistInject, $g);
               }
               
               $arrayNewGroups = explode(";", $_POST['newGroups']);
               foreach ($arrayNewGroups as $h) {
                   array_push($glistInject, $h);
               }

               $newRep = new EPReputation($_POST['name'], 
                                          $_POST['desc'], 
                                          $glistInject);
               
               if(!$persistManager->persistReputation($newRep)){
                       echo $persistManager->getLastError();
                       echo "<br><br>";
                } 
                else{
                    $_SESSION['repList'] = $epListP->getListReputation();
                    echo "<b> Reputation Added ! </b><br><br>"; 
                }      
           }
           else if(isset($_POST['deleteRep'])){
               
               if(!$persistManager->deleteReputation($_POST['deleteRep'])){
                       echo $persistManager->getLastError();
                       echo "<br><br>";
                } 
                else{
                    $_SESSION['repList'] = $epListP->getListReputation();
                    echo "<b> Reputation Deleted ! </b><br><br>"; 
                }  
               
           }
           
           
        ?>
        <form action="reputation.php" method="post">
            <input type="hidden" name="addRep" value="yep">
            <label>Reputation name<input type="text" name="name"></label>
            <br>
            <label>Description</label>
            <br>
            <textarea name="desc" rows="20" cols="50"></textarea>
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
        
        <p>Reputation on database</p>
        <table border = "1">
          <tr>
            <th>Name</th>
            <th>Description</th>
            <th>Group(s)</th>
            <th>DELETE FROM DATABASE</th>
          </tr>
          
            <?php    
                  foreach($_SESSION['repList'] as $m){
                    echo "<tr>";
                    echo "<td>".$m->name."</td>"; 
                    echo "<td>".$m->description."</td>";
                    $groups = "";
                    foreach($m->groups as $g){
                       $groups .= $g." | ";  
                     }
                    echo "<td>".$groups."</td>";                   
                    
                    echo "<td><form action='./reputation.php' method='post'><input type='hidden' name='deleteRep' value='".$m->name."'><input type='submit' value='Delete this line'></form></td>";
                    echo "</tr>";
                  }
             ?>            
        </table>
    </body>
</html>
