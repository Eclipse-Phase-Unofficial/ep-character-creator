<?php
require_once '../../../php/EPCharacterCreator.php';
include('../other/bookPageLayer.php');

session_start();
?>
<ul class="mainlist" id="backgrounds">
    <?php
        $currentBck = $_SESSION['cc']->getCurrentBackground();

         foreach($_SESSION['cc']->getBackgrounds() as $m){
            if($m->backgroundType == EPBackground::$ORIGIN){
                echo "<li class='bck' id='".$m->name."'>";
                echo "<span>".$m->name.getListStampHtml($m->name)."</span>";
                if(isset($currentBck) && $currentBck->name == $m->name){
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
