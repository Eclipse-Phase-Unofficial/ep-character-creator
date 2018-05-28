<?php
declare(strict_types=1);

require_once (__DIR__ . '/../../../../vendor/autoload.php');

use EclipsePhaseCharacterCreator\Site\other\Helpers;
use EclipsePhaseCharacterCreator\Site\other\Panel;

session_start();
$currentPsiS = $_SESSION['cc']->getPsySleightsByName($_SESSION['currentPsiSName']);

$myPanel = new Panel();
$myPanel->startDescriptivePanel($currentPsiS->name);
$myPanel->addDescription($currentPsiS->description);
$myPanel->addRawHtml( Helpers::getBMHtml($currentPsiS->bonusMalus,$currentPsiS->name,'psi') );
echo $myPanel->getHtml();
echo Panel::endPanel();
