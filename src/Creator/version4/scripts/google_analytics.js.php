<?php
declare(strict_types=1);
//PHP Is Used in this javascript file to set the tracking ID from config.ini

require_once (__DIR__ . '/../../../../vendor/autoload.php');

use EclipsePhaseCharacterCreator\Backend\EPConfigFile;

$configValues = new EPConfigFile(getConfigLocation());
$id = $configValues->getValue('GeneralValues','googleAnalyticsId');
?>

(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

ga('create', '<?php echo $id; ?>', 'auto');
ga('send', 'pageview');
