<?php
function getOccurenceHtml($atom,$type){

	if(!$atom->unique){
		  echo "<li>";
		  echo "<label class='listSection'>Buy more of this</label>";
		  echo "</li>";
		  echo "<li>";	
		  $currentOccurence = $atom->occurence;
		  echo "<label class='bmGranted'>Number</label><label class='occurences'>[".$currentOccurence."]</label>";
		  echo "	<span class='iconeAddOccu' id='addOccurence_".$type."' data-icon='&#x3a;'></span>";	
		  echo "	<span class='iconeRemOccu' id='removeOccurence_".$type."' data-icon='&#x3b;'></span>";
		  echo "</li>";	
	}
}




?>