
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Manage Aptitude</title>
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
           require_once '../php/EPAptitude.php';
           
           $epListP = new EPListProvider('../php/config.ini');
           $persistManager = new EPPersistentDataManager('../php/config.ini');
           $_SESSION['aptitudesList'] = $epListP->getListAptitudesComplete();
           $_SESSION['groupsList'] = $epListP->getListGroups();
           
           if(isset($_POST['addAptitude'])){
               
              $glistInject = array();
               foreach ($_POST['selectedGroups'] as $g) {
                   array_push($glistInject, $g);
               }
               
                $arrayNewGroups = explode(";", $_POST['newGroups']);
               foreach ($arrayNewGroups as $h) {
                   array_push($glistInject, $h);
               }
               
                $newAptitude = new EPAptitude($_POST['name'], 
                                        $_POST['abr'],        
                                        $_POST['desc'], 
                                        $glistInject,
                                        0,
                                        0,
                                        0,
                                        $epListP->configValues->getValue('RulesValues','AbsoluteAptitudesMaxValue'));
               
               if(!$persistManager->persistAptitude($newAptitude)){
                       echo $persistManager->getLastError();
                       echo "<br><br>";
                } 
                else{
                    $_SESSION['aptitudesList'] = $epListP->getListAptitudesComplete();
                    echo "<b> Aptitude Added ! </b><br><br>"; 
                }      
           }
           else if(isset($_POST['deleteAptitude'])){
               
               if(!$persistManager->deleteAptitude($_POST['deleteAptitude'])){
                       echo $persistManager->getLastError();
                       echo "<br><br>";
                } 
                else{
                    $_SESSION['aptitudesList'] = $epListP->getListAptitudesComplete();
                    echo "<b> Aptitude Deleted ! </b><br><br>"; 
                }  
               
           }
           
           
        ?>
        <form action="./aptitude.php" method="post">
            <input type="hidden" name="addAptitude" value="yep">
            <label>Aptitude name<input type="text" name="name"></label>
            <br>
            <label>Description</label>
            <br>
            <textarea name="desc" rows="20" cols="50"></textarea>
            <br>
             <label>Abreviation (3 char.)<input type="text" name="abr"></label>
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
            <br><br>
            <input type="submit" value="Insert">
        </form>
        
        <p>Aptitude on database</p>
        <table border = "1">
          <tr>
            <th>Name</th>
            <th>Description</th>
            <th>Abreviation</th>
            <th>Group(s)</th>
            <th>DELETE FROM DATABASE</th>
          </tr>
          
            <?php    
                  foreach($_SESSION['aptitudesList'] as $m){
                    echo "<tr>";
                    echo "<td>".$m->name."</td>"; 
                    echo "<td>".$m->description."</td>";
                    echo "<td>".$m->abbreviation."</td>";
                    $groups = "";
                    foreach($m->groups as $g){
                       $groups .= $g." | ";  
                     }
                    echo "<td>".$groups."</td>";
                    echo "<td><form action='./aptitude.php' method='post'><input type='hidden' name='deleteAptitude' value='".$m->name."'><input type='submit' value='Delete this line'></form></td>";
                    echo "</tr>";
                  }
             ?>            
        </table>
    </body>
</html>
