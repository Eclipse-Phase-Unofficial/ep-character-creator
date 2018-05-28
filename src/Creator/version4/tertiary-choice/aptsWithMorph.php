<?php
declare(strict_types=1);

require_once (__DIR__ . '/../../../../vendor/autoload.php');

session_start();
?>
<ul class="mainlist" id="aptsWithMorph">
    <?php
        $morph = $_SESSION['cc']->getCurrentMorphsByName($_SESSION['currentMorph']);
        $_SESSION['cc']->activateMorph($morph);

        echo "<li class='listSection'>";
		echo $morph->name;
		echo "</li>";
		foreach($_SESSION['cc']->getAptitudes() as $m){
        	echo "<li>";
        	echo $m->name;
        	echo '		<span class="btnhelp slowTransition" data-icon="&#x2a;" title="'.$m->description.'"></span>';
        	echo "		<span class='score'>[".$m->getValue()."] </span>";
        	echo "</li>";
        	
         }
	?>
</ul>
