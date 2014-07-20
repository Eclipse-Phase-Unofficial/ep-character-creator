<ul class="mainlist" id="credits">
	<li>
		<label class='listSection'>1 creation point = 1000 credits</label>
	</li>
	<li>
		<?php
			require_once '../../../php/EPCharacterCreator.php';
			session_start();
			$currentCredit = $_SESSION['cc']->getCredit();
			echo "<label>Current Credit</label><label class='score'>[".$currentCredit."]</label>";
		?>
		<span class='iconeAdd' id='addCredit' data-icon='&#x3a;'></span>	
		<span class='iconeRem' id='removeCredit' data-icon='&#x3b;'></span>
	</li>
	
	
</ul>