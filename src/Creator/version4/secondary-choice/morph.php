<?php
require_once '../../../php/EPCharacterCreator.php';
require_once('../other/panelHelper.php');
session_start();
?>
<ul class="mainlist" id="morphs">
	<?php
		 $listMorphs = $_SESSION['cc']->getMorphs();
		 $currentMorphs = $_SESSION['cc']->getCurrentMorphs(); 
		 
         function printMorph($listMorphs,$currentMorphs,$morphType,$sectionName){
            //Generate a HTML valid Id from the section name
            $id = preg_replace("/[^A-z]/","",$sectionName);

            $listFiltered = array();
            foreach($listMorphs as $m){
                if($m->morphType == $morphType){
                    array_push($listFiltered, $m);
                }
            }
            $formatedHtml = getFormatedMorphList($listFiltered,$currentMorphs);

            echo "<li class='foldingListSection' id='".$id."'>";
            echo $sectionName;
            echo "</li>";
            echo "<ul class='mainlist foldingList ".$id."'>";
            echo $formatedHtml;
            echo "</ul>";
         }

         printMorph($listMorphs,$currentMorphs,EPMorph::$BIOMORPH,"Biomorphs");
         printMorph($listMorphs,$currentMorphs,EPMorph::$PODMORPH,"Pods");
         printMorph($listMorphs,$currentMorphs,EPMorph::$SYNTHMORPH,"Synthmorphs");
         printMorph($listMorphs,$currentMorphs,EPMorph::$INFOMORPH,"Infomorphs");

         function getFormatedMorphList($totalMorphList,$currentList){
             $provider = new EPListProvider('../../../php/config.ini');
	         $htmlBlock = "";
	         foreach($totalMorphList as $m){
                $li = new li($m->name,'morphHover');
                if($_SESSION['cc']->creationMode){
                    $li->addCost($m->cpCost,False,'cp');
                }else{
                    $li->addCost($m->getCost(),False,'Credits');
                }
                $li->addBookIcon($m->name);
                $li->addPlusMinus('addRemMorph', !$m->isInArray($currentList) );
                $htmlBlock .= $li->getHtml();
                if( $m->isInArray($currentList) ){
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









