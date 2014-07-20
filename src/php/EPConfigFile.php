<?php
/*
 * Class manager for config file
 * 
 * @author Jigé
 */
class EPConfigFile {	
    private $configFile;
    
    function __construct($file){
	if(file_exists($file)){
            $this->configFile = parse_ini_file ($file,TRUE);
	}
    }

    function getValue($section,$name){
        $type = $this->configFile[$section][$name.'_type'];
		
        switch($type){
            case 'i':
                return intval($this->configFile[$section][$name]);
            case 'f':
                return floatval($this->configFile[$section][$name]);
            case 's':
                return $this->configFile[$section][$name];
            default:
                return $this->configFile[$section][$name];
        } 
    }
}
?>
