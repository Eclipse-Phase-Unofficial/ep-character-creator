<?php
declare(strict_types=1);

require_once (__DIR__ . '/../../../../vendor/autoload.php');

session_start();
?>
<ul class="mainlist" id="aptsWithMorph">
    <?php
        $morph = creator()->getCurrentMorphsByName($_SESSION['currentMorph']);
        creator()->activateMorph($morph);

        echo "<li class='listSection'>";
		echo $morph->name;
		echo "</li>";
		foreach(creator()->getAptitudes() as $m){
        	echo "<li>";
        	echo $m->name;
        	echo '		<span class="btnhelp slowTransition" data-icon="&#x2a;" title="'.$m->description.'"></span>';
        	echo "		<span class='score'>[".$m->getValue()."] </span>";
        	echo "</li>";
        	
         }
	?>
</ul>
