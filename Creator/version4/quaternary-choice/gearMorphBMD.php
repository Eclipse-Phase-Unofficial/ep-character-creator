<ul class="mainlist" id="bmdList">	
	<?php
		  require_once '../../../php/EPCharacterCreator.php'; //BMD stand for : Bonus Malus Description
		  include('../other/bonusMalusLayer.php');
		  include('../other/armorDegatsLayer.php');
		  include('../other/bookPageLayer.php');
		  include('../other/occurencesLayer.php');
		  
		  session_start();
		  // $hint = "--dubug:morph";
		  $morphGears = $_SESSION['cc']->getGearForMorphName($_SESSION['currentMorph']);
		  $currentGear = $_SESSION['cc']->getAtomByName($morphGears,$_SESSION['currentMorphGearName']);
		  if($currentGear == null){
			 $currentGear =  $_SESSION['cc']->getGearByName($_SESSION['currentMorphGearName']);
			// $hint = "--debug:general";
		  }
		 
		  getBPHtml($currentGear->name);
		 
		 getOccurenceHtml($currentGear,"MORPH");
		 
		  getBMHtml($currentGear->bonusMalus,$currentGear->name,'morphGear');
		  getADHtml($currentGear);
		  echo "<li>";
          echo "		<label class='listSection'>Description</label>";
          echo "</li>"; 
          echo "<li>";
          echo "		<label class='bmDesc'>".$currentGear->description."</label>";
          echo "</li>";
	?>
</ul>