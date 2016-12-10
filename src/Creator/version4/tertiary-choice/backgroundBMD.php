<?php
require_once '../../../php/EPCharacterCreator.php'; //BMD stand for : Bonus Malus Description
include('../other/bonusMalusLayer.php');
include('../other/traitLayer.php');
include('../other/bookPageLayer.php');

session_start();

$currentBck = $_SESSION['cc']->getCurrentBackground();
?>
<label class="descriptionTitle"><?php echo $currentBck->name; ?></label>
<ul class="mainlist" id="bmdList">
	<?php
		  getBPHtml($currentBck->name);
		  
		  echo "<li>";
          echo "		<label class='listSection'>Description</label>";
          echo "</li>"; 
          echo "<li>";
          echo "		<label class='bmDesc'>".$currentBck->description."</label>";
          echo "</li>";

		  getTraitHtml($currentBck->traits);
		  getBMHtml($currentBck->bonusMalus,$currentBck->name,'origine');
	?>
</ul>
