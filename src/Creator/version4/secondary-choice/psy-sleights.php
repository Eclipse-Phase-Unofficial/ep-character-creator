<?php
require_once '../../../php/EPCharacterCreator.php';
include('../other/bookPageLayer.php');
session_start();
?>
<ul class="mainlist" id="psyS">
	<?php
		 $currentPsyS = $_SESSION['cc']->getCurrentPsySleights();
		 $listPsyS = $_SESSION['cc']->getPsySleights();
		 
		 //CHI SECTION
 		echo "<li>";
 		echo "		<label class='foldingListSection' id='chi'>chi sleight (5 cp)</label>";
 		echo "</li>";
 		if($_SESSION['cc']->getCanPsyChi()){
	 		$lishtChi = array();
	 		foreach($listPsyS as $m){
	 		 	if($m->psyLevel == EPPsySleight::$LEVEL_CHI_PSY){
	     		 	array_push($lishtChi, $m);
	 		 	}
	 		}
	 		$chiHtml = getFormatedPsySleight($lishtChi,$currentPsyS);
	 		echo "<ul class='mainlist foldingList chi'>";
	 		echo $chiHtml;
	 		echo "</ul>";
	 	}
	 	else{
		 	echo "<li>";
		 	echo "		<label>You must take the trait [Psi I or II] for using psi chi </label>";
		 	echo "</li>";
	 	}
 		
 		//GAMMA SECTION
 		echo "<li>";
 		echo "		<label class='foldingListSection' id='gamma'>gamma sleight (5 cp)</label>";
 		echo "</li>";
 		if($_SESSION['cc']->getCanPsyGamma()){
 			$listGamma = array();
	 		foreach($listPsyS as $m){
	 		 	if($m->psyLevel == EPPsySleight::$LEVEL_GAMMA_PSY){
	     		 	array_push($listGamma, $m);
	 		 	}
	 		}
	 		$gammaHtml = getFormatedPsySleight($listGamma,$currentPsyS);
	 		echo "<ul class='mainlist foldingList gamma'>";
	 		echo $gammaHtml;
	 		echo "</ul>";
		}
	 	else{
		 	echo "<li>";
		 	echo "		<label>You must take the trait [Psi II] for using psi gamma </label>";
		 	echo "</li>";
	 	} 
		 
 		function getFormatedPsySleight($list,$currentPsyS){
 			 $result = "";
	         foreach($list as $m){
	            	$result .=  "<li>";
	            	if(isPsySOnlist($currentPsyS,$m)){
	            		$result .= "		<label class='psyS selPsyS' id='".$m->name."'>".$m->name."</label><span class='selectedicone selPsyS' data-icon='&#x2b;'></span>";
	            	}
	            	else{
	            		$result .=   "		<label class='psyS' id='".$m->name."'>".$m->name.getListStampHtml($m->name)."</label>";
	            	}
	            	
	            	$result .=  "</li>";
	          }
	          return $result;
         }
         
         
          function isPsySOnlist($list,$psyS){
	         foreach($list as $m){
	         	if($m->name == $psyS->name) return true;
	         	
	         }
	         return false;
         }

	?>
</ul>









