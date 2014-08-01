<?php
require_once '../../../php/EPCharacterCreator.php';
include('../other/bookPageLayer.php');
session_start();
?>
<ul class="mainlist" id="implants">
    <?php
        $morph = $_SESSION['cc']->getCurrentMorphsByName($_SESSION['currentMorph']);
        foreach($_SESSION['cc']->getGears() as $m){
            if($m->gearType === EPGear::$IMPLANT_GEAR){
            	if(isGearLegal($morph,$m)){
	                echo "<li>";
	            	if(isset($morph) && $_SESSION['cc']->haveGearOnMorph($m,$morph)){
	                    if ($_SESSION['cc']->haveAdditionalGear($m,$morph)){
	                        echo "		<label class='morphImplant selGear' id='".$m->name."'>".$m->name."</label><label class='costInfo'>(".$m->getCost()." credits)</label><span class='selectedicone selGear' data-icon='&#x2b;'></span>";
	                    }else{
	                        echo "		<label class='morphImplant selGear' id='".$m->name."'>".$m->name."</label><label class='costInfo'>(base gear)</label><span class='selectedicone selGear' data-icon='&#x2b;'></span>";
	                    }
	                    
	            	}else{
	                    echo "		<label class='morphImplant' id='".$m->name."'>".$m->name.getListStampHtml($m->name)."</label><label class='costInfo'>(".$m->getCost()." credits)</label>";
	            	}
	            	echo "</li>";
            	}
            }
        }
        
        
	    function isGearLegal($morph,$gear){
	    	 if($gear->gearRestriction == EPGear::$CAN_USE_EVERYBODY) return true;
	         else if($gear->gearRestriction == EPGear::$CAN_USE_BIO){
		         if($morph->morphType == EPMorph::$BIOMORPH) return true;
		         else return false;
	         }
	         else if($gear->gearRestriction == EPGear::$CAN_USE_SYNTH){
		         if($morph->morphType == EPMorph::$SYNTHMORPH || EPMorph::$INFOMORPH) return true;
		         else return false;
	         }
	         else if($gear->gearRestriction == EPGear::$CAN_USE_POD){
		         if($morph->morphType == EPMorph::$PODMORPH) return true;
		         else return false;
	         }
	         else if($gear->gearRestriction == EPGear::$CAN_USE_BIO_POD){
		         if($morph->morphType == EPMorph::$BIOMORPH || $morph->morphType == EPMorph::$PODMORPH) return true;
		         else return false;
	         }
	         else if($gear->gearRestriction == EPGear::$CAN_USE_SYNTH_POD){
		         if($morph->morphType == EPMorph::$SYNTHMORPH || $morph->morphType == EPMorph::$PODMORPH) return true;
		         else return false;
	         }
	          
	         return false;
	    }
	?>
</ul>