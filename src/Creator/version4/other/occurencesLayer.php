<?php
function getOccurenceHtml($atom,$type){

	if(!$atom->unique){
		  echo "<li>";
		  echo "	<label class='listSection'>Buy more of this</label>";
		  echo "</li>";
		  echo "<li>";
		  echo "	<span class='bmGranted'>Number</span>";
		  echo "	<span class='iconPlusMinus slowTransition' id='removeOccurence_".$type."' data-icon='&#x3b;'></span>";
		  echo "	<span class='betweenPlusMinus'>[".$atom->occurence."]</span>";
		  echo "	<span class='iconPlusMinus slowTransition' id='addOccurence_".$type."' data-icon='&#x3a;'></span>";
		  echo "</li>";	
	}
}




?>
