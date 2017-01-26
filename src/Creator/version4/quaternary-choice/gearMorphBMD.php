<?php
require_once '../../../php/EPCharacterCreator.php'; //BMD stand for : Bonus Malus Description
require_once '../../../php/EPAtom.php';
include('../other/bonusMalusLayer.php');
include('../other/armorDegatsLayer.php');
include('../other/bookPageLayer.php');
include('../other/occurencesLayer.php');

session_start();

// $hint = "--dubug:morph";
$morphGears = $_SESSION['cc']->getGearForMorphName($_SESSION['currentMorph']);
$currentGear = getAtomByName($morphGears,$_SESSION['currentMorphGearName']);
if($currentGear == null){
    $currentGear =  $_SESSION['cc']->getGearByName($_SESSION['currentMorphGearName']);
    // $hint = "--debug:general";
}
?>
<label class="descriptionTitle"><?php echo $currentGear->name; ?></label>
<ul class="mainlist" id="bmdList">
	<?php
		  getBPHtml($currentGear->name);
		 
		  getOccurenceHtml($currentGear,"MORPH");
		 
		  getBMHtml($currentGear->bonusMalus,$currentGear->name,'morphGear');
		  getADHtml($currentGear);
		  echo "<li class='listSection'>";
          echo "Description";
          echo "</li>"; 
          echo "<li>";
          echo "		<label class='bmDesc'>".$currentGear->description."</label>";
          echo "</li>";
	?>
</ul>
