<?php
require_once '../../../php/EPCharacterCreator.php';
require_once('../other/panelHelper.php');

session_start();
?>
<ul class="mainlist" id="factions">
    <?php
        $currentFac = $_SESSION['cc']->getCurrentFaction();

         foreach($_SESSION['cc']->getBackgrounds() as $m){
            if($m->backgroundType == EPBackground::$FACTION){
                $li = new li($m->name,'fac');
                $li->addBookIcon($m->name);
                $li->addCheckedBlank("",isset($currentFac) && $currentFac->name == $m->name);
                echo $li->getHtml();
            }
         }
    ?>
</ul>
