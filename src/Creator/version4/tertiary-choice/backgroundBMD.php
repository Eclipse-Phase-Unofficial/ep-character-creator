<?php
require_once '../../../php/EPCharacterCreator.php'; //BMD stand for : Bonus Malus Description
include('../other/bonusMalusLayer.php');
include('../other/bookPageLayer.php');

session_start();
?>
<ul class="mainlist" id="bmdList">
	<?php
		  $currentBck = $_SESSION['cc']->getCurrentBackground();
		  
		  getBPHtml($currentBck->name);
		  
		  echo "<li>";
          echo "		<label class='listSection'>Description</label>";
          echo "</li>"; 
          echo "<li>";
          echo "		<label class='bmDesc'>".$currentBck->description."</label>";
          echo "</li>";

		  getBMHtml($currentBck->bonusMalus,$currentBck->name,'origine');
		  
		  if(!empty($currentBck->traits)){
			  echo "<li>";
	          echo "		<label class='listSection'>Traits</label>";
	          echo "</li>"; 
	          foreach($currentBck->traits as $t){
		          echo "<li>";
		          echo "		<label class='bmGranted'>".$t->name."</label>";
		          echo "</li>";
	          }
          }
         
          
	?>
</ul>