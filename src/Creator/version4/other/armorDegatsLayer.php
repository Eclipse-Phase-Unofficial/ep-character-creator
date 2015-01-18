<?php
function getADHtml($gear){
		 if($gear->armorEnergy != 0 || $gear->armorKinetic != 0){
			  echo "<li>";
			  echo "		<label class='listSection'>Provided armor</label>";
			  echo "</li>"; 
			  echo "<li>";
			  echo "		<label class='bmGranted'>Kinetic <b>".$gear->armorKinetic."</b></label>";
			  echo "</li>";
			  echo "<li>";
			  echo "		<label class='bmGranted'>Energy <b>".$gear->armorEnergy."</b></label>";
			  echo "</li>";
		  }
		  if($gear->degat != 0 || $gear->armorPenetration != 0){
			  echo "<li>";
			  echo "		<label class='listSection'>Offensive capacity</label>";
			  echo "</li>"; 
			  echo "<li>";
			  echo "		<label class='bmGranted'>Damage: <b>".$gear->degat."</b></label>";
			  echo "</li>";
			  echo "<li>";
			  echo "		<label class='bmGranted'>Armor penetration: <b>".$gear->armorPenetration."</b></label>";
			  echo "</li>";
		  }
}
?>