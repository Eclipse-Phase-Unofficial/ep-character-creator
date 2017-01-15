<?php
require_once '../../../php/EPCharacterCreator.php';
session_start();
?>
<ul class="mainlist" id="aptitudes">
	<?php
		 
		 if($_SESSION['cc']->getAptitudePoint() > 0){
			  echo "<li>";
			  echo "		<label class='listSection'>cost : 1 Aptitude point</label>";
			  echo "</li>";
		 }
		 else{
			 echo "<li>";
			  echo "		<label class='listSection'>cost : 10 Creation points</label>";
			  echo "</li>";
		 }
		 
		 
		 $currentMorphs = $_SESSION['cc']->getCurrentMorphs(); 
		 //$_SESSION['cc']->activateMorph(null);
         foreach($_SESSION['cc']->getAptitudes() as $m){
        	echo "<li id='".$m->abbreviation."Def' class='apt'>";
        	echo "		<label>".$m->name."</label>";
        	echo "		<input type='number' min=0 step=5 id='".$m->abbreviation."' value='".$m->value."'/>";
            echo '      <span class="btnhelp slowTransition" data-icon="&#x2a;" title="'.$m->description.'"></span>';
        	echo "</li>";
        	
         }
         
         echo "<li>";
		 echo "		<label class='listSection'>aptitudes with morph : </label>";
		 echo "</li>";
		 if(isset($currentMorphs) && count($currentMorphs) > 0){
			 foreach($currentMorphs as $m){
	 			 	echo "		<li><label class='aptMorph' id='".$m->name."'>".$m->name."</label></li>";
	 		 } 
	 	 }

	?>
</ul>
