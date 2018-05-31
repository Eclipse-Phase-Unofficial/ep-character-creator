<?php
declare(strict_types=1);

require_once (__DIR__ . '/../../../../vendor/autoload.php');

use App\Creator\DisplayHelpers\Helpers;
use App\Creator\DisplayHelpers\Panel;

session_start();
$currentPsiS = creator()->getPsySleightsByName($_SESSION['currentPsiSName']);

$myPanel = new Panel();
$myPanel->startDescriptivePanel($currentPsiS->name);
$myPanel->addDescription($currentPsiS->description);
$myPanel->addRawHtml( Helpers::getBMHtml(creator(), $currentPsiS->bonusMalus,$currentPsiS->name,'psi') );
echo $myPanel->getHtml();
echo Panel::endPanel();
