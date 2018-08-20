<?php
declare(strict_types=1);

use App\Creator\EPListProvider;
use App\Creator\Atoms\EPMorph;
use App\Creator\DisplayHelpers\Li;

?>
<ul class="mainlist" id="morphs">
	<?php
		 $listMorphs = EpDatabase()->getMorphs();
		 $currentMorphs = creator()->getCurrentMorphs(); 
		 
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

    /**
     * @param EPMorph[] $totalMorphList
     * @param EPMorph[] $currentList
     * @return string
     */
    function getFormatedMorphList(array $totalMorphList, array $currentList)
    {
             $provider = new EPListProvider(getConfigLocation());
	         $htmlBlock = "";
	         foreach($totalMorphList as $m){
                $li = new Li($m->name,'morphHover');
                if(creator()->creationMode){
                    $li->addCost($m->cpCost,False,'cp');
                }else{
                    $li->addCost($m->getCost(),False,'Credits');
                }
                $li->addBookIcon($m->name);
                $li->addPlusMinus('addRemMorph', !$m->isInArray($currentList) );
                $htmlBlock .= $li->getHtml();
                if( $m->isInArray($currentList) ){
                    $htmlBlock .= "
                        <li>
                                <a class='morph-BMD' id='".$m->name."' href='#'><span class='icone' data-icon='&#x22;'></span>Bonus & Description</a>
                        </li>
                        <li>
                                <a class='morph-settings' id='".$m->name."' href='#'><span class='icone' data-icon='&#x27;'></span>settings</a>
                        </li>
                        <li>
                                <a class='morph-positive-traits' id='".$m->name."' href='#'>
                                    <span class='icone' data-icon='&#x35;'></span>
                                    morph pos. traits
                                    <span class='btnhelp slowTransition' id='posTrait' data-icon='&#x2a;' title='".$provider->getInfosById('posTrait')."'></span>
                                </a>
                        </li>
                        <li>
                                <a class='morph-neutral-traits' id='".$m->name."' href='#'>
                                    <span class='icone' data-icon='&#x34;'></span>
                                    morph neutral traits
                                    <span class='btnhelp slowTransition' id='neuTrait' data-icon='&#x2a;' title='".$provider->getInfosById('neuTrait')."'></span>
                                </a>
                        </li>
                        <li>
                                <a class='morph-negative-traits'  id='".$m->name."' href='#'>
                                    <span class='icone' data-icon='&#x36;'></span>
                                    morph neg. traits
                                    <span class='btnhelp slowTransition' id='negTrait' data-icon='&#x2a;' title='".$provider->getInfosById('negTrait')."'></span>
                                </a>
                        </li>
                        <li>
                                <a class='implants'  id='".$m->name."' href='#'>
                                    <span class='icone' data-icon='&#x31;'></span>
                                    implants
                                    <span class='btnhelp slowTransition' id='implants' data-icon='&#x2a;' title='".$provider->getInfosById('implants')."'></span>
                                </a>
                        </li>
                        <li>
                                <a class='gear'  id='".$m->name."' href='#'>
                                    <span class='icone' data-icon='&#x33;'></span>
                                    gear
                                    <span class='btnhelp slowTransition' id='gear' data-icon='&#x2a;' title='".$provider->getInfosById('gear')."'></span>
                                </a>
                        </li>";
            	}
            }
            return $htmlBlock;
         }
	?>
</ul>









