<?php
declare(strict_types=1);

use App\Creator\DisplayHelpers\Helpers;
use App\Creator\DisplayHelpers\Panel;

$currentBck = creator()->getCurrentBackground();

$myPanel = new Panel();
$myPanel->startDescriptivePanel($currentBck->getName());
$myPanel->addDescription($currentBck->getDescription());
$myPanel->addTraits($currentBck->traits);
$myPanel->addRawHtml( Helpers::getBMHtml(creator(), $currentBck->bonusMalus,$currentBck->getName(),'origine') );
echo $myPanel->getHtml();
echo Panel::endPanel();
?>
