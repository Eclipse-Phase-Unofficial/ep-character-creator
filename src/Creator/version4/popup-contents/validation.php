<?php
require_once '../../../php/EPCharacterCreator.php';
session_start();
?>
<table class="popup_table" id="table_validation" align="center">
	<?php
		
		$_SESSION['cc']->checkValidation();
		
		$aptPoint = $_SESSION['cc']->validation->items[EPValidation::$APTITUDE_POINT_USE];
        $repPoint = $_SESSION['cc']->validation->items[EPValidation::$REPUTATION_POINT_USE];
        $bck 	  = $_SESSION['cc']->validation->items[EPValidation::$BACKGROUND_CHOICE];
        $fac      = $_SESSION['cc']->validation->items[EPValidation::$FACTION_CHOICE];
        $charName = $_SESSION['cc']->validation->items[EPValidation::$CHARACTER_NAME_CHOICE];
        $morph    = $_SESSION['cc']->validation->items[EPValidation::$MORPH_CHOICE];
        $mot      = $_SESSION['cc']->validation->items[EPValidation::$MOTIVATION_THREE_CHOICE];
        $acSkill  = $_SESSION['cc']->validation->items[EPValidation::$ACTIVE_SKILLS_MIN];
        $knSkill  = $_SESSION['cc']->validation->items[EPValidation::$KNOWLEDGE_SKILLS_MIN];
        
        $AP = $_SESSION['cc']->getAptitudePoint();
        $CP = $_SESSION['cc']->getCreationPoint();
        $RP = $_SESSION['cc']->getReputationPoints();
        $cpActivRestNeed = $_SESSION['cc']->getActiveRestNeed();
        $cpKnowRestNeed = $_SESSION['cc']->getKnowledgeRestNeed();     
        		
		echo "<tr>";	
			if($bck){
				echo " 	<td><span class='valideIcone' data-icon='&#x2b;'></span><b>Background</b></td>";
			}
			else{
				echo " 	<td><span class='invalidIcone' remMotiv' data-icon='&#x39;'></span><b>Background</b>
				<label>
					You have to choose a background.
				</label></td>";
			}
		echo "</tr>";
		
		echo "<tr>";	
			if($fac){
				echo " 	<td><span class='valideIcone' data-icon='&#x2b;'></span><b>Faction</b></td>";
			}
			else{
				echo " 	<td><span class='invalidIcone' remMotiv' data-icon='&#x39;'></span><b>Faction</b>
				<label>
					You have to choose a faction.
				</label></td>";
			}
		echo "</tr>";
		
		echo "<tr>";	
			if($mot){
				echo " 	<td><span class='valideIcone' data-icon='&#x2b;'></span><b>Motivations</b></td>";
			}
			else{
				echo " 	<td><span class='invalidIcone' remMotiv' data-icon='&#x39;'></span><b>Motivations</b>
				<label>
					You have to choose at least three motivations.
				</label></td>";
			}
		echo "</tr>";
		
		echo "<tr>";	
			if($acSkill){
				echo " 	<td><span class='valideIcone' data-icon='&#x2b;'></span><b>Active skills</b></td>";
			}
			else{
				echo " 	<td><span class='invalidIcone' remMotiv' data-icon='&#x39;'></span><b>Active skills (Need : ".$cpActivRestNeed." CP)</b>
				<label>
					You have to spend more points on your active skills.
				</label></td>";
			}
		echo "</tr>";
		
		echo "<tr>";	
			if($knSkill){
				echo " 	<td><span class='valideIcone' data-icon='&#x2b;'></span><b>Knowlege skills</b></td>";
			}
			else{
				echo " 	<td><span class='invalidIcone' remMotiv' data-icon='&#x39;'></span><b>Knowlege skills (Need : ".$cpKnowRestNeed." CP)</b>
				<label>
					You have to spend more points on your Knowlege skills.
				</label></td>";
			}
		echo "</tr>";
		
		echo "<tr>";
			if($aptPoint){
				echo " 	<td><span class='valideIcone' data-icon='&#x2b;'></span><b>Aptitudes points</b></td>";
			}
			else{
				echo " 	<td><span class='invalidIcone' remMotiv' data-icon='&#x39;'></span><b>Aptitudes points</b>
				<label>
					rest aptitude points to spend.
				</label></td>";
			}
		echo "</tr>";
		
		echo "<tr>";	
			if($repPoint){
				echo " 	<td><span class='valideIcone' data-icon='&#x2b;'></span><b>Reputation points</b></td>";
			}
			else{
				echo " 	<td><span class='invalidIcone' remMotiv' data-icon='&#x39;'></span><b>Reputation points</b>
				<label>
					rest reputation point to spend.
				</label></td>";
			}
		echo "</tr>";
		
		echo "<tr>";	
			if($morph){
				echo " 	<td><span class='valideIcone' data-icon='&#x2b;'></span><b>Morph(s)</b></td>";
			}
			else{
				echo " 	<td><span class='invalidIcone' remMotiv' data-icon='&#x39;'></span><b>Morph(s)</b>
				<label>
					You have to choose at least one Morph.
				</label></td>";
			}
		echo "</tr>";
		
		
		echo "<tr>";	
			if($charName){
				echo " 	<td><span class='valideIcone' data-icon='&#x2b;'></span><b>Character name</b></td>";
			}
			else{
				echo " 	<td><span class='invalidIcone' remMotiv' data-icon='&#x39;'></span><b>Character name</b>
				<label>
					Set a character Name.
				</label></td>";
			}
		echo "</tr>";	

        echo "<tr>";
        echo " ";
        echo "</tr>";
        
        echo "<tr>";	
			if($AP == 0){
				echo " 	<td><span class='valideIcone' data-icon='&#x2b;'></span><b>Aptitudes Points = 0</b></td>";
			}
			else{
				echo " 	<td><span class='invalidIcone' remMotiv' data-icon='&#x39;'></span><b>Aptitudes Points = ".$AP."</b></td>";
			}
		echo "</tr>";
        echo "<tr>";	
			if($CP == 0){
				echo " 	<td><span class='valideIcone' data-icon='&#x2b;'></span><b>Creation Points = 0</b></td>";
			}
			else{
				echo " 	<td><span class='invalidIcone' remMotiv' data-icon='&#x39;'></span><b>Creation Points = ".$CP."</b></td>";
			}
		echo "</tr>";        
        echo "<tr>";	
			if($RP == 0){
				echo " 	<td><span class='valideIcone' data-icon='&#x2b;'></span><b>Reputation Points = 0</b></td>";
			}
			else{
				echo " 	<td><span class='invalidIcone' remMotiv' data-icon='&#x39;'></span><b>Reputation Points = ".$RP."</b></td>";
			}
		 echo "</tr>";
	?>
	<tr align="center">
		<td>
			<button class="closeButton popupInnerButton">
				Close
			</button>
		</td>
	</tr>
</table>
