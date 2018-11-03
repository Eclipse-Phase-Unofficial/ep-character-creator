<?php
declare(strict_types=1);
?>
<ul class="mainlist" id="reputations">
	<?php
		
		 if(creator()->getReputationPoints() > 0){
			  echo "<li class='listSection'>";
			  echo "Cost : 1 reputation point";
			  echo "</li>";
		 }
		 else{
			 echo "<li class='listSection'>";
			  echo "Cost : 0.1 Creation point";
			  echo "</li>";
		 }
		
         foreach(creator()->getReputations() as $m){
        	echo "<li id='".$m->getName()."Def' class='rep'>";
        	echo "		<label for='".$m->getName()."'>".$m->getName()." </label><label class='score_rep'>[".$m->getValue()."] </label>";
        	echo "		<input type='number' min=0 step=5  id='".$m->getName()."' value='".$m->value."' />";
            echo '      <span class="btnhelp slowTransition" data-icon="&#x2a;" title="'.$m->getDescription().'"></span>';
        	echo "</li>";
         }
	?>
</ul>
