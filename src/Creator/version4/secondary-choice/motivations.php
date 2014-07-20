<ul class="mainlist" id="motivation">
	<?php
			require_once '../../../php/EPCharacterCreator.php';
				
			session_start();
			
			if(count($_SESSION['cc']->getMotivations()) < 10) {
			
				echo "<li>
						<input type='text' id='motToAdd' placeholder='Enter a motivation'/>
						<span class='icone' id='addMotiv' data-icon='&#x3a;'></span>
					  </li>";
					  
			}
			echo "<div id='motivationsList'>";
		
		
			if($_SESSION['cc']->getMotivations() != null){
				$motForm = "";
				foreach($_SESSION['cc']->getMotivations() as $m){
					echo "<li><label>".$m."</label><span class='icone remMotiv' id='".$m."' data-icon='&#x39;'></span></li>";
				}
			}
			else{
				echo "";
			}
		?>
	</div>
</ul>