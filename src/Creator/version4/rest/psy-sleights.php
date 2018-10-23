<?php
require_once '../../../php/EPCharacterCreator.php';
require_once('../other/panelHelper.php');
require_once './_headers.php';
session_start();

$result = [];

echo json_encode($result);
?>
<ul class="mainlist" id="psyS">
	<?php
		 $currentPsyS = $_SESSION['cc']->getCurrentPsySleights();
		 $listPsyS = $_SESSION['cc']->getPsySleights();
		 
		 //CHI SECTION
 		echo "<li class='foldingListSection' id='chi'>";
 		echo "Chi Sleight";
 		echo "</li>";
 		if($_SESSION['cc']->getCanPsyChi()){
	 		$lishtChi = [];
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
 		echo "<li class='foldingListSection' id='gamma'>";
 		echo "Gamma Sleight";
 		echo "</li>";
 		if($_SESSION['cc']->getCanPsyGamma()){
 			$listGamma = [];
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
                    $li = new li($m->name,'psyS');
                    $li->addCost(5);
                    $li->addBookIcon($m->name);
                    $li->addPlusChecked('addSelPsySleightIcon',$m->isInArray($currentPsyS));
                    $result .= $li->getHtml();
                }
                return $result;
            }
	?>
</ul>
