<?php
declare(strict_types=1);

require_once (__DIR__ . '/../../vendor/autoload.php');

session_start();
?>
<ul class="mainlist">
	<li class='listSection'>
		1 creation point = 1000 credits
	</li>
	<li>
		Current Credit
		<span class='iconPlusMinus slowTransition' id='removeCredit' data-icon='&#x3b;'></span>
		<?php
			$currentCredit = creator()->getCredit();
			echo "<span class='betweenPlusMinus slowTransition'>[".$currentCredit."]</span>";
		?>
		<span class='iconPlusMinus slowTransition' id='addCredit' data-icon='&#x3a;'></span>
	</li>
	
	
</ul>
