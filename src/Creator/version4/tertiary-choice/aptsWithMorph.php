<?php
require_once '../../../php/EPCharacterCreator.php';
session_start();
?>
<ul class="mainlist" id="aptsWithMorph">
    <?php
        $morph = $_SESSION['cc']->getCurrentMorphsByName($_SESSION['currentMorph']);
        $_SESSION['cc']->activateMorph($morph);
        
        echo "<li>";
		echo "		<span class='listSection'>".$morph->name."</span>";
		echo "</li>";
		foreach($_SESSION['cc']->getAptitudes() as $m){
        	echo "<li>";
        	echo "		<span class='paddedLeft'>".$m->name."</span><span class='score'>[".$m->getValue()."] </span>";
        	echo "</li>";
        	
         }
	?>
</ul>
