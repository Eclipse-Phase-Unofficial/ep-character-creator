<?php
declare(strict_types=1);

require_once (__DIR__ . '/../../../../vendor/autoload.php');

session_start();
?>
<ul class="mainlist" id="reputations">
	<?php
		
		 if($_SESSION['cc']->getReputationPoints() > 0){
			  echo "<li class='listSection'>";
			  echo "Cost : 1 reputation point";
			  echo "</li>";
		 }
		 else{
			 echo "<li class='listSection'>";
			  echo "Cost : 0.1 Creation point";
			  echo "</li>";
		 }
		
         foreach($_SESSION['cc']->getReputations() as $m){
        	echo "<li id='".$m->name."Def' class='rep'>";
        	echo "		<label for='".$m->name."'>".$m->name." </label><label class='score_rep'>[".$m->getvalue()."] </label>";
        	echo "		<input type='number' min=0 step=5  id='".$m->name."' value='".$m->value."' />";
            echo '      <span class="btnhelp slowTransition" data-icon="&#x2a;" title="'.$m->description.'"></span>';
        	echo "</li>";
         }
	?>
</ul>
