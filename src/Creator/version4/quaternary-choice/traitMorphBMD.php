<?php
require_once '../../../php/EPCharacterCreator.php'; //BMD stand for : Bonus Malus Description
include('../other/bonusMalusLayer.php');
include('../other/bookPageLayer.php');

session_start();
?>
<ul class="mainlist" id="bmdList">
	<?php
		  //$hint = "--dubug:morph";
		  $currentMorphTraits = $_SESSION['cc']->getCurrentMorphTraits($_SESSION['currentMorph']);
		  $currentTrait = $_SESSION['cc']->getAtomByName($currentMorphTraits,$_SESSION['currentMorphTraitName']);
		  if($currentTrait == null){
			 $currentTrait =  $_SESSION['cc']->getTraitByName($_SESSION['currentMorphTraitName']);
			 //$hint = "--debug:general";
		  }
		  
		  getBPHtml($currentTrait->name);
		  
		  getBMHtml($currentTrait->bonusMalus,$currentTrait->name,'morphTrait');
		  echo "<li>";
          echo "		<label class='listSection'>Description</label>";
          echo "</li>"; 
          echo "<li>";
          echo "		<label class='bmDesc'>".$currentTrait->description."</label>";
          echo "</li>";
	?>
</ul>