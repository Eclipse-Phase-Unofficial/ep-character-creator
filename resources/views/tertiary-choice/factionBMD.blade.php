<?php
declare(strict_types=1);

use App\Creator\DisplayHelpers\Helpers;
use App\Creator\DisplayHelpers\Panel;

$currentFaction = creator()->getCurrentFaction();

$myPanel = new Panel();
$myPanel->startDescriptivePanel($currentFaction->name);
$myPanel->addDescription($currentFaction->description);
$myPanel->addTraits($currentFaction->traits);
$myPanel->addRawHtml( Helpers::getBMHtml(creator(), $currentFaction->bonusMalus,$currentFaction->name,'faction') );
echo $myPanel->getHtml();
echo Panel::endPanel();
?>
