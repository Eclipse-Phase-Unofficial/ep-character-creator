<?php
//PHP Is Used in this javascript file to set the tracking ID from config.ini
$php_dir = dirname(__FILE__) . '/../../../php/';
require_once( $php_dir . 'EPConfigFile.php');
$configValues = new EPConfigFile($php_dir . 'config.ini');
$id = $configValues->getValue('GeneralValues','googleAnalyticsId');
?>

(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

ga('create', '<?php echo $id; ?>', 'auto');
ga('send', 'pageview');
