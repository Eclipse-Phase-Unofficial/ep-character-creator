<?php
declare(strict_types=1);

use App\Creator\DisplayHelpers\Helpers;
use App\Creator\DisplayHelpers\Panel;

$currentPsiS = EpDatabase()->getPsySleightsByName($_SESSION['currentPsiSName']);

$myPanel = new Panel();
$myPanel->startDescriptivePanel($currentPsiS->name);
$myPanel->addDescription($currentPsiS->description);
$myPanel->addRawHtml( Helpers::getBMHtml(creator(), $currentPsiS->bonusMalus,$currentPsiS->name,'psi') );
echo $myPanel->getHtml();
echo Panel::endPanel();
