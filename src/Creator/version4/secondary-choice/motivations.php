<?php
declare(strict_types=1);

require_once (__DIR__ . '/../../../../vendor/autoload.php');

session_start();
?>
<ul class="mainlist" id="motivation">
	<?php
			if(count(creator()->getMotivations()) < 10) {
			
				echo "<li>
						<input type='text' id='motToAdd' placeholder='Enter a motivation'/>
						<span class='slowTransition iconPlusMinus' id='addMotiv' data-icon='&#x3a;'></span>
					  </li>";
					  
			}
			echo "<div id='motivationsList'>";
		
		
			if(creator()->getMotivations() != null){
				$motForm = "";
				foreach(creator()->getMotivations() as $m){
					echo "<li>".$m."</span><span class='remMotiv slowTransition iconPlusMinus' id='".$m."' data-icon='&#x39;'></li>";
				}
			}
			else{
				echo "";
			}
		?>
	</div>
</ul>
