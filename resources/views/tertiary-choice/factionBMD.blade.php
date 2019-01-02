<?php
declare(strict_types=1);

use App\Creator\DisplayHelpers\Helpers;
use App\Creator\DisplayHelpers\Panel;

$currentFaction = creator()->getCurrentFaction();

$myPanel = new Panel();
$myPanel->startDescriptivePanel($currentFaction->getName());
$myPanel->addDescription($currentFaction->getDescription());
$myPanel->addTraits($currentFaction->traits);
$myPanel->addRawHtml( Helpers::getBMHtml(creator(), $currentFaction->bonusMalus,$currentFaction->getName(),'faction') );
echo $myPanel->getHtml();
echo Panel::endPanel();
?>
