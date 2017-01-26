<?php
require_once '../../../php/EPCharacterCreator.php'; //BMD stand for : Bonus Malus Description
include('../other/bonusMalusLayer.php');
include('../other/aILayer.php');
include('../other/bookPageLayer.php');
include('../other/occurencesLayer.php');

session_start();
$currentAi = $_SESSION['cc']->getAisByName($_SESSION['currentAiName']);
?>
<label class="descriptionTitle"><?php echo $currentAi->name; ?></label>
<ul class="mainlist" id="bmdList">
	<?php
		  getBPHtml($currentAi->name);
		  
		  getOccurenceHtml($currentAi,"AI");
		  
		  getBMHtml($currentAi->bonusMalus,$currentAi->name,'ai');
		  getAIHtml($currentAi);
		  echo "<li class='listSection'>";
          echo "Description";
          echo "</li>"; 
          echo "<li>";
          echo "		<label class='bmDesc'>".$currentAi->description."</label>";
          echo "</li>";
	?>
</ul>
