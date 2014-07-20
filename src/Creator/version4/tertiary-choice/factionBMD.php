<ul class="mainlist" id="bmdList">	
	<?php
		  require_once '../../../php/EPCharacterCreator.php'; //BMD stand for : Bonus Malus Description
		  include('../other/bonusMalusLayer.php');
		  include('../other/bookPageLayer.php');
		  
		  session_start();
		  $currentBck = $_SESSION['cc']->getCurrentFaction();
		  
		  getBPHtml($currentBck->name);
		  
		  echo "<li>";
          echo "		<label class='listSection'>Description</label>";
          echo "</li>"; 
          echo "<li>";
          echo "		<label class='bmDesc'>".$currentBck->description."</label>";
          echo "</li>";

		  getBMHtml($currentBck->bonusMalus,$currentBck->name,'faction');
	?>
</ul>