<ul class="mainlist" id="bmdList">	
	<?php
		  require_once '../../../php/EPCharacterCreator.php'; //BMD stand for : Bonus Malus Description
		  include('../other/bonusMalusLayer.php');
		  include('../other/bookPageLayer.php');
		  
		  session_start();
		  $currentPsiS = $_SESSION['cc']->getPsySleightsByName($_SESSION['currentPsiSName']);
		  
		  getBPHtml($currentPsiS->name);
		  
		  getBMHtml($currentPsiS->bonusMalus,$currentPsiS->name,'psi');
		  echo "<li>";
          echo "		<label class='listSection'>Description</label>";
          echo "</li>"; 
          echo "<li>";
          echo "		<label class='bmDesc'>".$currentPsiS->description."</label>";
          echo "</li>";
	?>
</ul>