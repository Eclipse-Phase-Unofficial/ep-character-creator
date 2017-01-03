<?php
require_once '../../../php/EPCharacterCreator.php';
require_once('../other/bookPageLayer.php');
session_start();
?>
<ul class="mainlist" id="morphs">
	<?php
		 $listMorphs = $_SESSION['cc']->getMorphs();
		 $currentMorphs = $_SESSION['cc']->getCurrentMorphs(); 
		 
         
         		//BIOMORPH SECTION
         		echo "<li>";
         		echo "		<label class='foldingListSection' id='bio'>biomorphs</label>";
         		echo "</li>";
         		$listBiomorphs = array();
         		foreach($listMorphs as $m){
         		 	if($m->morphType == EPMorph::$BIOMORPH){
	         		 	array_push($listBiomorphs, $m);
         		 	}
         		}
         		$biomorphHtml = getFormatedMorphList($listBiomorphs,$currentMorphs);
         		echo "<ul class='mainlist foldingList bio'>";
         		echo $biomorphHtml;
         		echo "</ul>";
         		
         		//POD SECTION
         		echo "<li>";
         		echo "		<label class='foldingListSection' id='pod'>pods</label>";
         		echo "</li>";
         		$listPods = array();
         		foreach($listMorphs as $m){
         		 	if($m->morphType == EPMorph::$PODMORPH){
	         		 	array_push($listPods, $m);
         		 	}
         		}
         		$podHtml = getFormatedMorphList($listPods,$currentMorphs);
         		echo "<ul class='mainlist foldingList pod'>";
         		echo $podHtml;
         		echo "</ul>";
         		
         		//SYNTHMORPH SECTION
         		echo "<li>";
         		echo "		<label class='foldingListSection' id='synth'>synthmorphs</label>";
         		echo "</li>";
         		$listsyns = array();
         		foreach($listMorphs as $m){
         		 	if($m->morphType == EPMorph::$SYNTHMORPH){
	         		 	array_push($listsyns, $m);
         		 	}
         		}
         		$synHtml = getFormatedMorphList($listsyns,$currentMorphs);
         		echo "<ul class='mainlist foldingList synth'>";
         		echo $synHtml;
         		echo "</ul>";
         		
         		//INFOMORPH SECTION
         		echo "<li>";
         		echo "		<label class='foldingListSection' id='info'>infomorphs</label>";
         		echo "</li>";
         		$listinfo = array();
         		foreach($listMorphs as $m){
         		 	if($m->morphType == EPMorph::$INFOMORPH){
	         		 	array_push($listinfo, $m);
         		 	}
         		}
         		$infoHtml = getFormatedMorphList($listinfo,$currentMorphs);
         		echo "<ul class='mainlist foldingList info'>";
         		echo $infoHtml;
         		echo "</ul>";
         		
        
         
         function getFormatedMorphList($totalMorphList,$currentList){
             $provider = new EPListProvider('../../../php/config.ini');
	         $htmlBlock = "";
	         foreach($totalMorphList as $m){
	         	$cost_string = "";
	         	if($_SESSION['cc']->creationMode){
                            $cost_string = $m->cpCost." cp";
	         	}else{
                            $cost_string = $m->getCost()." credits";
	         	}                        

                $htmlBlock .= "<li class='addRemMorph' id='".$m->name."'>";
                $htmlBlock .= "		<span>".$m->name."</span>";
                $htmlBlock .= getListStampHtml($m->name);
                $htmlBlock .= "		<span class='costInfo'>(".$cost_string.")</span>";
            	if(isMorphOnlist($currentList,$m)){
            		$htmlBlock .= "		<span class='addOrSelectedIcon remMorphIcone' id='".$m->name."' data-icon='&#x3b;'></span>";
            		$htmlBlock .= "</li>";
            		$htmlBlock .= "<li>";
            		$htmlBlock .= "		<a class='morph-BMD' id='".$m->name."' href='#'><span class='icone' data-icon='&#x22;'></span>Bonus & Description</a>";
            		$htmlBlock .= "</li>";
            		$htmlBlock .= "<li>";
            		$htmlBlock .= "		<a class='morph-settings' id='".$m->name."' href='#'><span class='icone' data-icon='&#x27;'></span>settings</a>";
            		$htmlBlock .= "</li>";
            		$htmlBlock .= "<li>";
            		$htmlBlock .= "		<a class='morph-positive-traits' id='".$m->name."' href='#'>";
            		$htmlBlock .= "			<span class='icone' data-icon='&#x35;'></span>";
            		$htmlBlock .= "			morph pos. traits";
            		$htmlBlock .= "			<span class='btnhelp slowTransition' id='posTrait' data-icon='&#x2a;' title='".$provider->getInfosById('posTrait')."'></span>";
            		$htmlBlock .= "		</a>";
            		$htmlBlock .= "</li>";
            		$htmlBlock .= "<li>";
            		$htmlBlock .= "		<a class='morph-neutral-traits' id='".$m->name."' href='#'>";
            		$htmlBlock .= "			<span class='icone' data-icon='&#x34;'></span>";
            		$htmlBlock .= "			morph neutral traits";
            		$htmlBlock .= "			<span class='btnhelp slowTransition' id='neuTrait' data-icon='&#x2a;' title='".$provider->getInfosById('neuTrait')."'></span>";
            		$htmlBlock .= "		</a>";
            		$htmlBlock .= "</li>";
            		$htmlBlock .= "<li>";
            		$htmlBlock .= "		<a class='morph-negative-traits'  id='".$m->name."' href='#'>";
            		$htmlBlock .= "			<span class='icone' data-icon='&#x36;'></span>";
            		$htmlBlock .= "			morph neg. traits";
            		$htmlBlock .= "			<span class='btnhelp slowTransition' id='negTrait' data-icon='&#x2a;' title='".$provider->getInfosById('negTrait')."'></span>";
            		$htmlBlock .= "		</a>";
            		$htmlBlock .= "</li>";
            		$htmlBlock .= "<li>";
            		$htmlBlock .= "		<a class='implants'  id='".$m->name."' href='#'>";
            		$htmlBlock .= "			<span class='icone' data-icon='&#x31;'></span>";
            		$htmlBlock .= "			implants";
            		$htmlBlock .= "			<span class='btnhelp slowTransition' id='implants' data-icon='&#x2a;' title='".$provider->getInfosById('implants')."'></span>";
            		$htmlBlock .= "		</a>";
            		$htmlBlock .= "</li>";
            		$htmlBlock .= "<li>";
            		$htmlBlock .= "		<a class='gear'  id='".$m->name."' href='#'>";
            		$htmlBlock .= "			<span class='icone' data-icon='&#x33;'></span>";
            		$htmlBlock .= "			gear";
            		$htmlBlock .= "			<span class='btnhelp slowTransition' id='gear' data-icon='&#x2a;' title='".$provider->getInfosById('gear')."'></span>";
            		$htmlBlock .= "		</a>";
            		$htmlBlock .= "</li>";
            	}
            	else{
            		$htmlBlock .= "		<span class='addOrSelectedIcon addMorphIcone' id='".$m->name."' data-icon='&#x3a;'></span>";
            	}  	
            	$htmlBlock .= "</li>";
            }
            return $htmlBlock;
         }
         
         function isMorphOnlist($list,$morph){
	         foreach($list as $m){
	         	if($m->name == $morph->name) return true;
	         }
	         return false;
         }
         
	?>
</ul>









