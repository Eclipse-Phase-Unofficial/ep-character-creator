<?php
declare(strict_types=1);

require_once (__DIR__ . '/../../../../vendor/autoload.php');

use App\Creator\DisplayHelpers\Helpers;

session_start();
$currentMorph = $_SESSION['cc']->getCurrentMorphsByName($_SESSION['currentMorph']);
?>
<label class="descriptionTitle"><?php echo $currentMorph->name; ?></label>
<ul class="mainlist" id="morphPostraits">
    <li class='foldingListSection'>Morph Pos. Traits</li>
    <?php
        $currentTraits = $_SESSION['cc']->getCurrentMorphTraits($_SESSION['currentMorph']);
        $defaultTraits = $_SESSION['cc']->getCurrentDefaultMorphTraits($currentMorph);
        foreach($_SESSION['cc']->getTraits() as $m){
            if($m->isPositive() &&
               $m->isMorph()  &&
                Helpers::isTraitLegal($currentMorph,$m) &&
               $m->cpCost > 0){
                echo Helpers::getDynamicTraitLi($m,$currentTraits,$defaultTraits,'morphPosTrait','addSelMorphPosTraitIcon');
            }
         }

    ?>
</ul>
