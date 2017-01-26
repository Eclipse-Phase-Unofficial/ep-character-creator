<?php
require_once '../../../php/EPCharacterCreator.php'; //BMD stand for : Bonus Malus Description
include('../other/bonusMalusLayer.php');
include('../other/armorDegatsLayer.php');
include('../other/bookPageLayer.php');
include('../other/occurencesLayer.php');
session_start();
$currentGear = $_SESSION['cc']->getGearByName($_SESSION['currentSoftName']);
?>
<label class="descriptionTitle"><?php echo $currentGear->name; ?></label>
<ul class="mainlist" id="bmdList">
	<?php
		  
		  getBPHtml($currentGear->name);
		  
		  getOccurenceHtml($currentGear,"SOFT");	
		  
		  getBMHtml($currentGear->bonusMalus,$currentGear->name,'soft');
		  getADHtml($currentGear);
		  echo "<li class='listSection'>";
          echo "Description";
          echo "</li>"; 
          echo "<li>";
          echo "		<label class='bmDesc'>".$currentGear->description."</label>";
          echo "</li>";
	?>
</ul>
