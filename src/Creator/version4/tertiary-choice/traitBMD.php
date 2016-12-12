<?php
require_once '../../../php/EPCharacterCreator.php'; //BMD stand for : Bonus Malus Description
require_once '../../../php/EPAtom.php';
include('../other/bonusMalusLayer.php');
include('../other/bookPageLayer.php');

session_start();

$currentTraitsList = $_SESSION['cc']->getCurrentTraits();
$currentTrait = getAtomByName($currentTraitsList,$_SESSION['currentTraitName']);
if($currentTrait == null){
    $currentTrait = $_SESSION['cc']->getTraitByName($_SESSION['currentTraitName']);
}
?>
<label class="descriptionTitle"><?php echo $currentTrait->name; ?></label>
<ul class="mainlist" id="bmdList">
	<?php
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
