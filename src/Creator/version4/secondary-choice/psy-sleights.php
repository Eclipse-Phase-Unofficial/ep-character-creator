<?php
require_once '../../../php/EPCharacterCreator.php';
require_once('../other/bookPageLayer.php');
require_once('../other/gearHelper.php');
session_start();
?>
<ul class="mainlist" id="psyS">
	<?php
		 $currentPsyS = $_SESSION['cc']->getCurrentPsySleights();
		 $listPsyS = $_SESSION['cc']->getPsySleights();
		 
		 //CHI SECTION
 		echo "<li>";
 		echo "		<label class='foldingListSection' id='chi'>chi sleight</label>";
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
 		echo "		<label class='foldingListSection' id='gamma'>gamma sleight</label>";
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
                    $result .= "<li class='psyS' id='".$m->name."'>";
                    $result .= "<span>".$m->name.getListStampHtml($m->name)."</span>";
                    $result .= getCostHtml(5,false);
                    if($m->isInArray($currentPsyS)){
                        $result .= "<span class='addOrSelectedIcon addSelPsySleightIcon' id='".$m->name."' data-icon='&#x2b;'></span>";
                    }
                    else{
                        $result .= "<span class='addOrSelectedIcon addSelPsySleightIcon' id='".$m->name."' data-icon='&#x3a;'></span>";
                    }

                    $result .= "</li>";
                }
                return $result;
            }
	?>
</ul>
