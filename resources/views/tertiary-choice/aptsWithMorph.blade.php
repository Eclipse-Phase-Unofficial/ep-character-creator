<?php
declare(strict_types=1);
?>
<ul class="mainlist" id="aptsWithMorph">
    <?php
        $morph = creator()->getCurrentMorphsByName((string) session('currentMorph'));
        creator()->activateMorph($morph);

        echo "<li class='listSection'>";
		echo $morph->getName();
		echo "</li>";
		foreach(creator()->getAptitudes() as $m){
        	echo "<li>";
        	echo $m->getName();
        	echo '		<span class="btnhelp slowTransition" data-icon="&#x2a;" title="'.$m->getDescription().'"></span>';
        	echo "		<span class='score'>[".$m->getValue()."] </span>";
        	echo "</li>";
        	
         }
	?>
</ul>
