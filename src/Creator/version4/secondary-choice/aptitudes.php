<?php
declare(strict_types=1);

require_once (__DIR__ . '/../../../../vendor/autoload.php');

session_start();
?>
<ul class="mainlist" id="aptitudes">
	<?php
		 
		 if($_SESSION['cc']->getAptitudePoint() > 0){
			  echo "<li class='listSection'>";
			  echo "Cost : 1 Aptitude point";
			  echo "</li>";
		 }
		 else{
			 echo "<li class='listSection'>";
			  echo "Cost : 10 Creation points";
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
         
         echo "<li class='listSection'>";
		 echo "Aptitudes with morph :";
		 echo "</li>";
		 if(isset($currentMorphs) && count($currentMorphs) > 0){
			 foreach($currentMorphs as $m){
	 			 	echo "		<li><label class='aptMorph' id='".$m->name."'>".$m->name."</label></li>";
	 		 } 
	 	 }

	?>
</ul>
