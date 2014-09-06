<?php
require_once '../../../php/EPCharacterCreator.php'; //BMD stand for : Bonus Malus Description
include('../other/bonusMalusLayer.php');
include('../other/bookPageLayer.php');

session_start();

$currentMorphTraits = $_SESSION['cc']->getCurrentMorphTraits($_SESSION['currentMorph']);
$currentTrait = $_SESSION['cc']->getAtomByName($currentMorphTraits,$_SESSION['currentMorphTraitName']);
if($currentTrait == null){
    $currentTrait =  $_SESSION['cc']->getTraitByName($_SESSION['currentMorphTraitName']);
}
?>
<label class="descriptionTitle"><?php echo $currentTrait->name; ?></label>
<ul class="mainlist" id="bmdList">
	<?php
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