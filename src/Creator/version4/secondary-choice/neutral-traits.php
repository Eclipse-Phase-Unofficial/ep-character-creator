<?php
declare(strict_types=1);

require_once (__DIR__ . '/../../../../vendor/autoload.php');

use App\Creator\DisplayHelpers\Helpers;

session_start();
?>
<ul class="mainlist" id="postraits">
    <?php
        $currentTraits = $_SESSION['cc']->getCurrentTraits();
        $defaultTraits = $_SESSION['cc']->getCurrentDefaultEgoTraits();
         foreach($_SESSION['cc']->getTraits() as $m){
            if($m->isEgo() && $m->cpCost == 0){
                echo Helpers::getDynamicTraitLi($m,$currentTraits,$defaultTraits,'neuTrait','addSelNeuTrait');
            }
         }
    ?>
</ul>









