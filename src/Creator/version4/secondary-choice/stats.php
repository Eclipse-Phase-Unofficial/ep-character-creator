<?php
require_once '../../../php/EPCharacterCreator.php';
session_start();
?>
<ul class="mainlist" id="stats">
	<li>
		<label class='listSection'>cost : 15 creation points</label>
	</li>

	<?php
		 $currentMorphs = $_SESSION['cc']->getCurrentMorphs(); 
		 $currentMoxie = $_SESSION['cc']->getStatByAbbreviation(EPStat::$MOXIE)->getValue();
		 echo "<li class='descMoxie'>";
		 echo "		<span class='paddedLeft'>Moxie</span>";
		 echo "		<span class='iconPlusMinus slowTransition' id='removeMoxie' data-icon='&#x3b;'></span>";
		 echo "		<span class='betweenPlusMinus'>[".$currentMoxie."]</span>";
		 echo "		<span class='iconPlusMinus slowTransition' id='addMoxie' data-icon='&#x3a;'></span>";
		 echo "</li>";
		 
		 echo "<li>";
		 echo "		<label class='listSection'>With morph : </label>";
		 echo "</li>";
		 foreach($currentMorphs as $m){
 			 	echo "		<li class='callStatMorph'><label class='paddedLeft' id='".$m->name."'>".$m->name."</label></li>";
 		 } 
   	?>
</ul>
