<?php
declare(strict_types=1);

use App\Creator\DisplayHelpers\Helpers;
use App\Creator\DisplayHelpers\Panel;

$currentBck = creator()->getCurrentBackground();

$myPanel = new Panel();
$myPanel->startDescriptivePanel($currentBck->name);
$myPanel->addDescription($currentBck->description);
$myPanel->addTraits($currentBck->traits);
$myPanel->addRawHtml( Helpers::getBMHtml(creator(), $currentBck->bonusMalus,$currentBck->name,'origine') );
echo $myPanel->getHtml();
echo Panel::endPanel();
?>
