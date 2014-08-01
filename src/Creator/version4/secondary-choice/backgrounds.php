<?php
require_once '../../../php/EPCharacterCreator.php';
include('../other/bookPageLayer.php');

session_start();
?>
<ul class="mainlist" id="backgrounds">
	<?php
		 $currentBck = $_SESSION['cc']->getCurrentBackground();
		 
         foreach($_SESSION['cc']->getBackgrounds() as $m){
            if($m->backgroundType == EPBackground::$ORIGIN){
            	echo "<li>";
            	if(isset($currentBck) && $currentBck->name == $m->name){
            		echo "		<label class='bck bckSelected' id='".$m->name."'>".$m->name."</label><span class='selectedicone bckSelected' data-icon='&#x2b;'></span>";
            	}
            	else{
            		echo "		<label class='bck' id='".$m->name."'>".$m->name.getListStampHtml($m->name)."</label>";
            	}
            	
            	echo "</li>";
            }
         }
	?>
</ul>









