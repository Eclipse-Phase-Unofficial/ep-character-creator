<?php
declare(strict_types=1);

require_once (__DIR__ . '/../../../../vendor/autoload.php');

use EclipsePhaseCharacterCreator\Backend\EPBackground;
use EclipsePhaseCharacterCreator\Site\other\Li;

session_start();
?>
<ul class="mainlist" id="backgrounds">
    <?php
        $currentBck = $_SESSION['cc']->getCurrentBackground();

         foreach($_SESSION['cc']->getBackgrounds() as $m){
            if($m->backgroundType == EPBackground::$ORIGIN){
                $li = new Li($m->name,'bck');
                $li->addBookIcon($m->name);
                $li->addCheckedBlank("",isset($currentBck) && $currentBck->name == $m->name);
                echo $li->getHtml();
            }
         }
    ?>
</ul>
