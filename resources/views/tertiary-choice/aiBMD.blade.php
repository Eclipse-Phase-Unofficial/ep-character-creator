<?php
declare(strict_types=1);

use App\Creator\DisplayHelpers\Helpers;
use App\Creator\DisplayHelpers\Panel;

$currentAi = EpDatabase()->getAiByName((string) session('currentAiName'));

$myPanel = new Panel();
$myPanel->startDescriptivePanel($currentAi->getName());
$myPanel->addDescription($currentAi->getDescription());
$myPanel->addAi($currentAi);
$myPanel->addRawHtml( Helpers::getBMHtml(creator(), $currentAi->bonusMalus,$currentAi->getName(),'ai') ); // 'ai' is not a valid value, so any choose options WILL FAIL
echo $myPanel->getHtml();
echo Panel::endPanel();
?>
