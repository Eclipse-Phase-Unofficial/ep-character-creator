<?php
require_once '../../../php/EPCharacterCreator.php';
session_start();
?>
<ul class="mainlist" id="motivation">
	<?php
			if(count($_SESSION['cc']->getMotivations()) < 10) {
			
				echo "<li>
						<span class='paddedLeft'><input type='text' id='motToAdd' placeholder='Enter a motivation'/></span>
						<span class='slowTransition iconPlusMinus' id='addMotiv' data-icon='&#x3a;'></span>
					  </li>";
					  
			}
			echo "<div id='motivationsList'>";
		
		
			if($_SESSION['cc']->getMotivations() != null){
				$motForm = "";
				foreach($_SESSION['cc']->getMotivations() as $m){
					echo "<li><span class='paddedLeft'>".$m."</span><span class='remMotiv slowTransition iconPlusMinus' id='".$m."' data-icon='&#x39;'></span></li>";
				}
			}
			else{
				echo "";
			}
		?>
	</div>
</ul>
