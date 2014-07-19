<ul class="mainlist" id="stats">
	<li>
		<label class='listSection'>cost : 15 creation points</label>
	</li>

	<?php
		 require_once '../../../php/EPCharacterCreator.php';
		 session_start();
		 $currentMorphs = $_SESSION['cc']->getCurrentMorphs(); 
		 $currentMoxie = $_SESSION['cc']->getStatByAbbreviation(EPStat::$MOXIE)->getValue();
		 echo "<li id='moxie' class='descMoxie'>";
		 echo "		<label>Moxie [".$currentMoxie."]</label>";
		 echo "		<span class='iconeAdd' id='addMoxie' data-icon='&#x3a;'></span>";	
		 echo "		<span class='iconeRem' id='removeMoxie' data-icon='&#x3b;'></span>";
		 echo "</li>";
		 
		 echo "<li>";
		 echo "		<label class='listSection'>With morph : </label>";
		 echo "</li>";
		 foreach($currentMorphs as $m){
 			 	echo "		<li><label class='callStatMorph' id='".$m->name."'>".$m->name."</label></li>";
 		 } 
   	?>
</ul>









