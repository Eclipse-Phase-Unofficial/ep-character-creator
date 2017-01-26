<?php
require_once '../../../php/EPCharacterCreator.php'; //BMD stand for : Bonus Malus Description
include('../other/bonusMalusLayer.php');
require_once('../other/traitLayer.php');
require_once('../other/bookPageLayer.php');

session_start();

$currentBck = $_SESSION['cc']->getCurrentBackground();
?>
<label class="descriptionTitle"><?php echo $currentBck->name; ?></label>
<ul class="mainlist" id="bmdList">
	<?php
		  getBPHtml($currentBck->name);
		  
		  echo "<li class='listSection'>";
          echo "Description";
          echo "</li>"; 
          echo "<li>";
          echo "		<label class='bmDesc'>".$currentBck->description."</label>";
          echo "</li>";

		  getStaticTraitHtml($currentBck->traits);
		  getBMHtml($currentBck->bonusMalus,$currentBck->name,'origine');
	?>
</ul>
