<?php
function getAIHtml($ai){

	if(!empty($ai->aptitudes)){
		echo "<li>";
		echo "		<label class='listSection'>Aptitudes</label>";
		echo "</li>"; 
	}
	
	foreach($ai->aptitudes as $apt){
		 echo "<li>";
		 echo "		<label class='bmGranted'>".$apt->abbreviation." <b>".$apt->value."</b></label>";
		 echo "</li>";
	}
	
	if(!empty($ai->skills)){
		echo "<li>";
		echo "		<label class='listSection'>Skills</label>";
		echo "</li>"; 
	}
	
	foreach($ai->skills as $apt){
		if(!empty($apt->prefix)){
			 echo "<li>";
			 echo "		<label class='bmGranted'>".$apt->prefix." :  ".$apt->name." <b>".$apt->baseValue."</b></label>";
			 echo "</li>";
		 }
		 else{
			  echo "<li>";
			  echo "		<label class='bmGranted'>".$apt->name." <b>".$apt->baseValue."</b></label>";
			  echo "</li>";
		 }
	}
	 
}
?>