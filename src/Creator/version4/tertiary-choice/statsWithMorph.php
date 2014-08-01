<?php
require_once '../../../php/EPCharacterCreator.php';
session_start();
?>
<ul class="mainlist" id="statWithMorph">
    <?php
        $morph = $_SESSION['cc']->getCurrentMorphsByName($_SESSION['currentMorph']);
        $_SESSION['cc']->activateMorph($morph);
        echo "<li>";
		echo "		<label class='listSection'>".$morph->name."</label>";
		echo "</li>";
		echo "<li id='TT' class='statMorph'><label>TRAUMA THRESHOLD</label><label class='score'>[".$_SESSION['cc']->getStatByAbbreviation("TT")->getValue()."]</label></li>";				    
		echo "<li id='LUC' class='statMorph'><label>LUCIDITY</label><label class='score'> [".$_SESSION['cc']->getStatByAbbreviation("LUC")->getValue()."]</label></li>";
		echo "<li id='IR' class='statMorph'><label>INSANITY RATING</label><label class='score'> [".$_SESSION['cc']->getStatByAbbreviation("IR")->getValue()."]</label></li>";
		echo "<li id='WT' class='statMorph'><label>WOUND THRESHOLD</label><label class='score'> [".$_SESSION['cc']->getStatByAbbreviation("WT")->getValue()."]</label></li>";
		echo "<li id='DUR' class='statMorph'><label>DURABILITY</label><label class='score'> [".$_SESSION['cc']->getStatByAbbreviation("DUR")->getValue()."]</label></li>";
		echo "<li id='DR' class='statMorph'><label>DEATH RATING</label><label class='score'> [".$_SESSION['cc']->getStatByAbbreviation("DR")->getValue()."]</label></li>";
		echo "<li id='INI' class='statMorph'><label>INITIATIVE</label><label class='score'> [".$_SESSION['cc']->getStatByAbbreviation("INI")->getValue()."]</label></li>";	
		echo "<li id='SPD' class='statMorph'><label>SPEED</label><label class='score'> [".$_SESSION['cc']->getStatByAbbreviation("SPD")->getValue()."]</label></li>";	
		echo "<li id='DB' class='statMorph'><label>DAMAGE BONUS</label><label class='score'> [".$_SESSION['cc']->getStatByAbbreviation("DB")->getValue()."]</label></li>";		
	?>
</ul>