<ul class="mainlist" id="factions">
	<?php
		require_once '../../../php/EPCharacterCreator.php';
		include('../other/bookPageLayer.php');
		session_start();
		
		$currentFac = $_SESSION['cc']->getCurrentFaction();
         foreach($_SESSION['cc']->getBackgrounds() as $m){
            if($m->backgroundType == EPBackground::$FACTION){
            	echo "<li>";
            	if($currentFac != null && $currentFac->name == $m->name){
            		echo "		<label class='fac facSelected' id='".$m->name."'>".$m->name."</label><span class='selectedicone facSelected' data-icon='&#x2b;'></span>";
            	}
            	else{
            		echo "		<label class='fac' id='".$m->name."'>".$m->name.getListStampHtml($m->name)."</label>";
            	}
            	
            	echo "</li>";
            }
         }
	?>
</ul>









