<?php
require_once '../../../php/EPCharacterCreator.php'; //BMD stand for : Bonus Malus Description
require_once '../../../php/EPAtom.php';
include('../other/bonusMalusLayer.php');
include('../other/bookPageLayer.php');
session_start();
$currentMorphsList = $_SESSION['cc']->getCurrentMorphs();
$currentMorph = getAtomByName($currentMorphsList,$_SESSION['currentMorph']);
if($currentMorph == null){
    $currentMorph = $_SESSION['cc']->getMorphByName($_SESSION['currentMorph']);
}
?>
<label class="descriptionTitle"><?php echo $currentMorph->name; ?></label>
<ul class="mainlist" id="bmdList">
	<?php
		  getBPHtml($currentMorph->name);
		  
		  getBMHtml($currentMorph->bonusMalus,$currentMorph->name,'morph');
		  echo "<li class='listSection'>";
          echo "Description";
          echo "</li>"; 
          echo "<li>";
          echo "		<label class='bmDesc'>".$currentMorph->description."</label>";
          echo "</li>";
          $traits = $_SESSION['cc']->getCurrentMorphTraits($currentMorph->name);
          if(!empty($traits)){
			  echo "<li class='listSection'>";
	          echo "Traits";
	          echo "</li>"; 
                  
	          foreach($traits as $t){
		          echo "<li>";
		          echo "		<label class='bmGranted'>".$t->name."</label>";
		          echo "</li>";
	          }
          }
           if(!empty($currentMorph->gears)){
			  echo "<li class='listSection'>";
	          echo "Implants";
	          echo "</li>"; 
	          foreach($currentMorph->gears as $g){
		          echo "<li>";
		          echo "		<label class='bmGranted'>".$g->name."</label>";
		          echo "</li>";
	          }
          }

	?>
</ul>
