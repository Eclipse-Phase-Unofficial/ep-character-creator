<?php
declare(strict_types=1);
?>
<ul class="mainlist" id="aptitudes">
	<?php

		 if(creator()->getAptitudePoint() > 0){
			  echo "<li class='listSection'>";
			  echo "Cost : 1 Aptitude point";
			  echo "</li>";
		 }
		 else{
			 echo "<li class='listSection'>";
			  echo "Cost : 10 Creation points";
			  echo "</li>";
		 }


		 $currentMorphs = creator()->getCurrentMorphs();
		 //creator()->activateMorph(null);
         foreach(creator()->getAptitudes() as $m){
        	echo "<li id='".$m->getAbbreviation()."Def' class='apt'>";
        	echo "		<label>".$m->getName()."</label>";
        	echo "		<input type='number' min=0 step=5 id='".$m->getAbbreviation()."' value='".$m->value."'/>";
            echo '      <span class="btnhelp slowTransition" data-icon="&#x2a;" title="'.$m->getDescription().'"></span>';
        	echo "</li>";

         }

         echo "<li class='listSection'>";
		 echo "Aptitudes with morph :";
		 echo "</li>";
		 if(isset($currentMorphs) && count($currentMorphs) > 0){
			 foreach($currentMorphs as $m){
	 			 	echo "		<li><label class='aptMorph' id='".$m->getName()."'>".$m->getName()."</label></li>";
	 		 }
	 	 }

	?>
</ul>
