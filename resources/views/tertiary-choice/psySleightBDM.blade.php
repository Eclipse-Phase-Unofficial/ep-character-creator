<?php
declare(strict_types=1);

use App\Creator\DisplayHelpers\Helpers;
use App\Creator\DisplayHelpers\Panel;

$currentPsiS = EpDatabase()->getPsySleightsByName($_SESSION['currentPsiSName']);

$myPanel = new Panel();
$myPanel->startDescriptivePanel($currentPsiS->getName());
$myPanel->addDescription($currentPsiS->getDescription());
$myPanel->addRawHtml( Helpers::getBMHtml(creator(), $currentPsiS->bonusMalus,$currentPsiS->getName(),'psi') );
echo $myPanel->getHtml();
echo Panel::endPanel();
