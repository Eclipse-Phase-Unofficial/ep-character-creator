<?php
require_once '../../../php/EPCharacterCreator.php';
session_start();
?>
<ul class="mainlist" id="statWithMorph">
    <?php
        $morph = $_SESSION['cc']->getCurrentMorphsByName($_SESSION['currentMorph']);
        $_SESSION['cc']->activateMorph($morph);
        echo "<li>";
		echo "		<span class='listSection'>".$morph->name."</span>";
		echo "</li>";
		echo "<li id='TT' class='statMorph'><span class='paddedLeft'>TRAUMA THRESHOLD</span><span class='score'>[".$_SESSION['cc']->getStatByAbbreviation("TT")->getValue()."]</span></li>";
		echo "<li id='LUC' class='statMorph'><span class='paddedLeft'>LUCIDITY</span><span class='score'>[".$_SESSION['cc']->getStatByAbbreviation("LUC")->getValue()."]</span></li>";
		echo "<li id='IR' class='statMorph'><span class='paddedLeft'>INSANITY RATING</span><span class='score'>[".$_SESSION['cc']->getStatByAbbreviation("IR")->getValue()."]</span></li>";
		echo "<li id='WT' class='statMorph'><span class='paddedLeft'>WOUND THRESHOLD</span><span class='score'>[".$_SESSION['cc']->getStatByAbbreviation("WT")->getValue()."]</span></li>";
		echo "<li id='DUR' class='statMorph'><span class='paddedLeft'>DURABILITY</span><span class='score'>[".$_SESSION['cc']->getStatByAbbreviation("DUR")->getValue()."]</span></li>";
		echo "<li id='DR' class='statMorph'><span class='paddedLeft'>DEATH RATING</span><span class='score'>[".$_SESSION['cc']->getStatByAbbreviation("DR")->getValue()."]</span></li>";
		echo "<li id='INI' class='statMorph'><span class='paddedLeft'>INITIATIVE</span><span class='score'>[".$_SESSION['cc']->getStatByAbbreviation("INI")->getValue()."]</span></li>";
		echo "<li id='SPD' class='statMorph'><span class='paddedLeft'>SPEED</span><span class='score'>[".$_SESSION['cc']->getStatByAbbreviation("SPD")->getValue()."]</span></li>";
		echo "<li id='DB' class='statMorph'><span class='paddedLeft'>DAMAGE BONUS</span><span class='score'>[".$_SESSION['cc']->getStatByAbbreviation("DB")->getValue()."]</span></li>";
	?>
</ul>
