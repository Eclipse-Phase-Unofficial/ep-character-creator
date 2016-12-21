<?php
require_once '../../../php/EPCharacterCreator.php';
session_start();
?>
<ul class="mainlist">
	<li>
		<label class='listSection'>1 creation point = 1000 credits</label>
	</li>
	<li>
		<span class="paddedLeft">Current Credit</span>
		<span class='iconPlusMinus slowTransition' id='removeCredit' data-icon='&#x3b;'></span>
		<?php
			$currentCredit = $_SESSION['cc']->getCredit();
			echo "<span class='betweenPlusMinus slowTransition'>[".$currentCredit."]</span>";
		?>
		<span class='iconPlusMinus slowTransition' id='addCredit' data-icon='&#x3a;'></span>
	</li>
	
	
</ul>
