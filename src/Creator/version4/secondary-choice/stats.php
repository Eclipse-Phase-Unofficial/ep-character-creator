<?php
declare(strict_types=1);

require_once (__DIR__ . '/../../../../vendor/autoload.php');

use EclipsePhaseCharacterCreator\Backend\EPStat;

session_start();
?>
<ul class="mainlist" id="stats">
	<li class='listSection'>
		Cost : 15 creation points
	</li>

	<?php
		 $currentMorphs = $_SESSION['cc']->getCurrentMorphs(); 
		 $currentMoxie = $_SESSION['cc']->getStatByAbbreviation(EPStat::$MOXIE)->getValue();
		 echo "<li class='descMoxie'>";
		 echo "		Moxie";
		 echo "		<span class='iconPlusMinus slowTransition' id='removeMoxie' data-icon='&#x3b;'></span>";
		 echo "		<span class='betweenPlusMinus'>[".$currentMoxie."]</span>";
		 echo "		<span class='iconPlusMinus slowTransition' id='addMoxie' data-icon='&#x3a;'></span>";
		 echo "</li>";
		 
		 echo "<li class='listSection'>";
		 echo "With morph : ";
		 echo "</li>";
		 foreach($currentMorphs as $m){
 			 	echo "		<li class='callStatMorph'><label id='".$m->name."'>".$m->name."</label></li>";
 		 } 
   	?>
</ul>
