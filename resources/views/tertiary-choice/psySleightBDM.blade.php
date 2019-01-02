<?php
declare(strict_types=1);

use App\Creator\Atoms\EPAtom;
use App\Creator\DisplayHelpers\Helpers;
use App\Creator\DisplayHelpers\Panel;

$currentPsySleightsList = creator()->getCurrentPsySleights();
$currentPsiS            = EPAtom::getAtomByName($currentPsySleightsList, (string)session('currentSoftName'));
if ($currentPsiS == null) {
    $currentPsiS = EpDatabase()->getPsySleightsByName((string)session('currentPsiSName'));
}

$myPanel = new Panel();
$myPanel->startDescriptivePanel($currentPsiS->getName());
$myPanel->addDescription($currentPsiS->getDescription());
$myPanel->addRawHtml(Helpers::getBMHtml(creator(), $currentPsiS->bonusMalus, $currentPsiS->getName(), 'psi'));
echo $myPanel->getHtml();
echo Panel::endPanel();
