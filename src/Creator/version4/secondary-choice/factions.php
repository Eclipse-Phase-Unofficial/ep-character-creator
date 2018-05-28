<?php
declare(strict_types=1);

require_once (__DIR__ . '/../../../../vendor/autoload.php');

use EclipsePhaseCharacterCreator\Backend\EPBackground;
use EclipsePhaseCharacterCreator\Site\other\Li;

session_start();
?>
<ul class="mainlist" id="factions">
    <?php
        $currentFac = $_SESSION['cc']->getCurrentFaction();

         foreach($_SESSION['cc']->getBackgrounds() as $m){
            if($m->backgroundType == EPBackground::$FACTION){
                $li = new Li($m->name,'fac');
                $li->addBookIcon($m->name);
                $li->addCheckedBlank("",isset($currentFac) && $currentFac->name == $m->name);
                echo $li->getHtml();
            }
         }
    ?>
</ul>
