<?php
declare(strict_types=1);

namespace EclipsePhaseCharacterCreator\Backend;

/**
 * Class manager for config file
 *
 * @author Jigé
 */
class EPConfigFile {
    private $configFile;

    function __construct(string $file){
        // Allow file path to be overridden by environment.
        if(isset($_ENV['EPCC_CONFIG_PATH'])) {
            $file = $_ENV['EPCC_CONFIG_PATH'];
        }
        if(file_exists($file)){
            $this->configFile = parse_ini_file ($file,TRUE);
        }
        else{
            error_log("Unable to open Config File: ".$file." does not exist!");
        }
    }

    /**
     * @param string $section
     * @param string $name
     * @return float|int|string|mixed
     */
    function getValue(string $section, string $name)
    {
        $type = $this->configFile[$section][$name . '_type'];

        switch ($type) {
            case 'i':
                return intval($this->configFile[$section][$name]);
            case 'f':
                return floatval($this->configFile[$section][$name]);
            case 's':
                return (string)$this->configFile[$section][$name];
            default:
                //TODO:  Make this throw instead
                return $this->configFile[$section][$name];
        }
    }

    function getVersionName(): string
    {
        return $this->getValue('GeneralValues', 'versionName');
    }

    function getVersionString(): string
    {
        $versionNumber = $this->getValue('GeneralValues', 'versionNumber');
        $releaseDate   = $this->getValue('GeneralValues', 'releaseDate');
        return "Version $versionNumber $releaseDate";
    }
}
