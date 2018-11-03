<?php
declare(strict_types=1);

use App\Creator\DisplayHelpers\Helpers;
use App\Creator\DisplayHelpers\Panel;

$currentAi = EpDatabase()->getAiByName($_SESSION['currentAiName']);

$myPanel = new Panel();
$myPanel->startDescriptivePanel($currentAi->name);
$myPanel->addBuySell($currentAi,"AI");
$myPanel->addDescription($currentAi->description);
$myPanel->addAi($currentAi);
$myPanel->addRawHtml( Helpers::getBMHtml(creator(), $currentAi->bonusMalus,$currentAi->name,'ai') ); // 'ai' is not a valid value, so any choose options WILL FAIL
echo $myPanel->getHtml();
echo Panel::endPanel();
?>