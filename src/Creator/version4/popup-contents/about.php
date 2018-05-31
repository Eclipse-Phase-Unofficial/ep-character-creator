<?php
declare(strict_types=1);

require_once (__DIR__ . '/../../../../vendor/autoload.php');

use EclipsePhaseCharacterCreator\Backend\EPConfigFile;

$config = new EPConfigFile(getConfigLocation());
?>
    <a href="https://github.com/EmperorArthur/ep-character-creator" target="_blank"><img style="position: absolute; top: 0; right: 0; border: 0;" src="https://camo.githubusercontent.com/a6677b08c955af8400f44c6298f40e7d19cc5b2d/68747470733a2f2f73332e616d617a6f6e6177732e636f6d2f6769746875622f726962626f6e732f666f726b6d655f72696768745f677261795f3664366436642e706e67" alt="Fork me on GitHub" data-canonical-src="https://s3.amazonaws.com/github/ribbons/forkme_right_gray_6d6d6d.png"></a>

<div class="popup_contents">
    <h1><b><u> About</u></b></h1>
    <p>Eclipse Phase Character Creator (<?php echo $config->getVersionName(); ?>)</p>
    <p><?php echo $config->getVersionString(); ?></p>
    <p>A character creator for the <a href="http://eclipsephase.com" target="_blank">Eclipse Phase</a> role-playing game.</p>
    <p>
        Please submit all suggestions and bug reports to the
        <a href="https://github.com/EmperorArthur/ep-character-creator/issues" target="_blank">Issues</a> page.
    </p>
    <p>
        Created by:
        <b>Russell Bewley,</b>
        <b>Stoo Goof,</b>
        <b>Derek Payne,</b>
        <b>Cédric Reinhardt,</b>
        <b>Jigé Pont,</b>
        <b>Olivier Murith,</b>
        <b>Arthur Moore</b>
    </p>
    <a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/3.0/deed.en_US" target="_blank">
        <img alt="Creative Commons License" style="border-width:0" src="http://i.creativecommons.org/l/by-nc-sa/3.0/88x31.png" />
    </a>
    <p>
        <small>
            This work is licensed under a
            <a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/3.0/deed.en_US" target="_blank">
                Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License
            </a>
            .
        </small>
    </p>
    <button class="closeButton popupInnerButton">Close</button>
</div>

