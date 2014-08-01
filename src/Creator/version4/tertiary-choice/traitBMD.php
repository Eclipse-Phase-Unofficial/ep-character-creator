<?php
require_once '../../../php/EPCharacterCreator.php'; //BMD stand for : Bonus Malus Description
include('../other/bonusMalusLayer.php');
include('../other/bookPageLayer.php');

session_start();
?>
<ul class="mainlist" id="bmdList">
	<?php
		  $currentTraitsList = $_SESSION['cc']->getCurrentTraits();
		  $currentTrait = $_SESSION['cc']->getAtomByName($currentTraitsList,$_SESSION['currentTraitName']);
		  if($currentTrait == null){
			  $currentTrait = $_SESSION['cc']->getTraitByName($_SESSION['currentTraitName']);
		  }
		  
		  getBPHtml($currentTrait->name);
		  
		  getBMHtml($currentTrait->bonusMalus,$currentTrait->name,'trait');
		  echo "<li>";
          echo "		<label class='listSection'>Description</label>";
          echo "</li>"; 
          echo "<li>";
          echo "		<label class='bmDesc'>".$currentTrait->description."</label>";
          echo "</li>";
	?>
</ul>