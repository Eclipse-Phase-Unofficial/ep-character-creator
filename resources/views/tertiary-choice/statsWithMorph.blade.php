<?php
declare(strict_types=1);

function printStat($abbreviation){
    $stat = creator()->getStatByAbbreviation($abbreviation);
    echo "<li id='".$abbreviation."' class='statMorph'>";
    echo "  ".$stat->getName();
    echo "  <span class='btnhelp slowTransition' data-icon='&#x2a;' title='".$stat->getDescription()."'></span>";
    echo "  <span class='score'>[".$stat->getValue()."]</span>";
    echo "</li>";

}
?>
<ul class="mainlist" id="statWithMorph">
    <?php
        $morph = creator()->getCurrentMorphsByName((string) session('currentMorph'));
        creator()->activateMorph($morph);
        echo "<li class='listSection'>";
		echo $morph->getName();
		echo "</li>";
		printStat("TT");
		printStat("LUC");
		printStat("IR");
		printStat("WT");
		printStat("DUR");
		printStat("DR");
		printStat("INI");
		printStat("SPD");
		printStat("DB");
	?>
</ul>
