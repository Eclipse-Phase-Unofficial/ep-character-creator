<?php
require_once '../../../php/EPCharacterCreator.php';
require_once('../other/panelHelper.php');

session_start();
?>
<ul class="mainlist" id="backgrounds">
    <?php
        $currentBck = $_SESSION['cc']->getCurrentBackground();

         foreach($_SESSION['cc']->getBackgrounds() as $m){
            if($m->backgroundType == EPBackground::$ORIGIN){
                $li = new li($m->name,'bck');
                $li->addBookIcon($m->name);
                $li->addCheckedBlank("",isset($currentBck) && $currentBck->name == $m->name);
                echo $li->getHtml();
            }
         }
    ?>
</ul>
