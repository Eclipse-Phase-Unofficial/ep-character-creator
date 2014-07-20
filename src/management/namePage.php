
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Manage Page</title>
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
           
           require_once '../php/EPBackground.php';
           require_once '../php/EPTrait.php';
           require_once '../php/EPGear.php';
           require_once '../php/EPPsySleight.php';
           
           $epListP = new EPListProvider('../php/config.ini');
           $persistManager = new EPPersistentDataManager('../php/config.ini');
           
           $_SESSION['backgroundList'] = $epListP->getListBackgrounds();
           $_SESSION['traitList'] = $epListP->getListTraits();
           $_SESSION['gearList'] = $epListP->getListGears();
           $_SESSION['psyList'] = $epListP->getListPsySleights();
           $_SESSION['morphList'] = $epListP->getListMorph();
           $_SESSION['aiList'] = $epListP->getListAi();
           
           
           if(isset($_POST['addPage'])){
               
               $totalInject = array();
               
              if(isset($_POST['selectedBackground'])){
               	foreach ($_POST['selectedBackground'] as $b) {
                   array_push($totalInject, $b);
               	}
               }
              
               
               if(isset($_POST['selectedTraits'])){
	               foreach ($_POST['selectedTraits'] as $t) {
	                  array_push($totalInject, $t);
	               }
               }
               
               if(isset($_POST['selectedGear'])){
	               foreach ($_POST['selectedGear'] as $g) {
	                   array_push($totalInject, $g);
	               }
               }
               
               if(isset($_POST['psyList'])){
	               foreach ($_POST['psyList'] as $psy) {
	                   array_push($totalInject, $psy);
	               }
               }
               
                if(isset($_POST['mList'])){
	               foreach ($_POST['mList'] as $morph) {
	                   array_push($totalInject, $morph);
	               }
               }
               
                if(isset($_POST['aiSelList'])){
	               foreach ($_POST['aiSelList'] as $ai) {
	                   array_push($totalInject, $ai);
	               }
               }
             
               foreach($totalInject as $a){             
	               if(!$persistManager->persistAtomePage($a,$_POST['page'])){
	                       echo $persistManager->getLastError();
	                       echo "<br><br>";
	                } 
	                else{
	                    echo "<b> Page Added ! </b>"; 
	                } 
	            }  
	            echo "<br>";
                
                
                   
           }
         
           
           
        ?>
        <form action="./namePage.php" method="post">
            <input type="hidden" name="addPage" value="yep">
    
             <label>Page<input type="text" name="page"></label>
            <br>
            <label>Background - faction</label>
            <br>
            <select name="selectedBackground[]" multiple>
                <?php    
                     foreach($_SESSION['backgroundList'] as $m){
                     	if(!$epListP->isNameOnPageList($m->name)) echo "<option value='".$m->name."'>".$m->name."</option> ";  
                     }
                ?>
            </select>
            <br>
            <label>Trait pos neg</label>
            <br>
            <select name="selectedTraits[]" multiple>
                <?php    
                     foreach($_SESSION['traitList'] as $m){
                       if(!$epListP->isNameOnPageList($m->name)) echo "<option value='".$m->name."'>".$m->name."</option> ";  
                     }
                ?>
            </select>
            <br>
            <label>Gear</label>
            <br>
            <select name="selectedGear[]" multiple>
                <?php    
                     foreach($_SESSION['gearList'] as $m){
                       if(!$epListP->isNameOnPageList($m->name)) echo "<option value='".$m->name."'>".$m->name."</option> ";  
                     }
                ?>
            </select>
             <br>
            <label>PsySleight</label>
            <br>
            <select name="psyList[]" multiple>
                <?php    
                     foreach($_SESSION['psyList'] as $m){
                       if(!$epListP->isNameOnPageList($m->name)) echo "<option value='".$m->name."'>".$m->name."</option> ";  
                     }
                ?>
            </select>
             <br>
             <label>Morph</label>
            <br>
            <select name="mList[]" multiple>
                <?php    
                     foreach($_SESSION['morphList'] as $mo){
                       if(!$epListP->isNameOnPageList($mo->name)) echo "<option value='".$mo->name."'>".$mo->name."</option> ";  
                     }
                ?>
            </select>
			 <br>
             <label>AI</label>
            <br>
            <select name="aiSelList[]" multiple>
                <?php    
                     foreach($_SESSION['aiList'] as $ai){
                       if(!$epListP->isNameOnPageList($ai->name)) echo "<option value='".$ai->name."'>".$ai->name."</option> ";  
                     }
                ?>
            </select>
            <br>
            <br>
            <input type="submit" value="Insert">
        </form>
       
    </body>
</html>
