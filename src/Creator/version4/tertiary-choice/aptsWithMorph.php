<?php
require_once '../../../php/EPCharacterCreator.php';
session_start();
?>
<ul class="mainlist" id="aptsWithMorph">
    <?php
        $morph = $_SESSION['cc']->getCurrentMorphsByName($_SESSION['currentMorph']);
        $_SESSION['cc']->activateMorph($morph);
        
        echo "<li>";
		echo "		<label class='listSection'>".$morph->name."</label>";
		echo "</li>";
		foreach($_SESSION['cc']->getAptitudes() as $m){
        	echo "<li>";
        	echo "		<label>".$m->name."</label><label class='score'>[".$m->getValue()."] </label>";
        	echo "</li>";
        	
         }
	?>
</ul>