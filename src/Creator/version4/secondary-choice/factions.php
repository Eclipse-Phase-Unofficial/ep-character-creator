<?php
require_once '../../../php/EPCharacterCreator.php';
include('../other/bookPageLayer.php');
session_start();
?>
<ul class="mainlist" id="factions">
    <?php
        $currentFac = $_SESSION['cc']->getCurrentFaction();

         foreach($_SESSION['cc']->getBackgrounds() as $m){
            if($m->backgroundType == EPBackground::$FACTION){
                echo "<li class='fac' id='".$m->name."'>";
                echo "<span>".$m->name.getListStampHtml($m->name)."</span>";
                if(isset($currentFac) && $currentFac->name == $m->name){
                    echo "<span class='addOrSelectedIcon' data-icon='&#x2b;'></span>";
                }
                else{
                    echo "<span class='addOrSelectedIcon'></span>";
                }

                echo "</li>";
            }
         }
    ?>
</ul>
