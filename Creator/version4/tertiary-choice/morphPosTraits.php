<ul class="mainlist" id="morphPostraits">	
	<?php
		require_once '../../../php/EPCharacterCreator.php';
		include('../other/bookPageLayer.php');
		 session_start();
		 $currentMorph = $_SESSION['cc']->getCurrentMorphsByName($_SESSION['currentMorph']);
		 $currentTraits = $_SESSION['cc']->getCurrentMorphTraits($_SESSION['currentMorph']);
		 $defaultTrait = $_SESSION['cc']->getCurrentDefaultMorphTraits($currentMorph);
         foreach($_SESSION['cc']->getTraits() as $m){
            if($m->traitPosNeg == EPTrait::$POSITIVE_TRAIT &&
               $m->traitEgoMorph == EPTrait::$MORPH_TRAIT  &&
               isTraitLegal($currentMorph,$m) &&
               $m->cpCost > 0){
            	echo "<li>";
            	if($defaultTrait != null && $_SESSION['cc']->isAtomInArrayByName($m->name,$defaultTrait)){
	            	echo "		<label class='morphPosTrait selPosTrait' id='".$m->name."'>".$m->name."</label><label class='costInfo'>(Granted)</label><span class='selectedicone selPosTrait' data-icon='&#x2b;'></span>";

            	}
            	else if($currentTraits != null && $_SESSION['cc']->isAtomInArrayByName($m->name,$currentTraits)){
            		echo "		<label class='morphPosTrait selPosTrait' id='".$m->name."'>".$m->name."</label><label class='costInfo'>(".$m->cpCost." cp)</label><span class='selectedicone selPosTrait' data-icon='&#x2b;'></span>";
            	}
            	else{
            		echo "		<label class='morphPosTrait' id='".$m->name."'>".$m->name.getListStampHtml($m->name)."</label><label class='costInfo'>(".$m->cpCost." cp)</label>";
            	}
            	
            	echo "</li>";
            }
         }
         
         function isTraitLegal($morph,$trait){
         	if($morph->morphType == EPMorph::$INFOMORPH) return false;
         
	         if($trait->canUse == EPTrait::$CAN_USE_EVERYBODY) return true;
	         else if($trait->canUse == EPTrait::$CAN_USE_BIO){
		         if($morph->morphType == EPMorph::$BIOMORPH) return true;
	         }
	         else if($trait->canUse == EPTrait::$CAN_USE_SYNTH){
		         if($morph->morphType == EPMorph::$SYNTHMORPH) return true;
	         }
	         else if($trait->canUse == EPTrait::$CAN_USE_POD){
		         if($morph->morphType == EPMorph::$PODMORPH) return true;
	         }
	         return false;
         }
	?>
</ul>