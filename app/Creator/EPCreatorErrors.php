<?php
declare(strict_types=1);

namespace App\Creator;

/**
 * Error Class
 *
 * @author reinhardt
 */
class EPCreatorErrors {
    static $SYSTEM_ERROR = 'System_Error';
    static $RULE_ERROR = 'Rule_Error';
    
    public $textError;
    public $typeError;    
    
    function __construct($text = '',$tError = 'Rule_Error') {
        $this->textError = $text;
        $this->typeError = $tError;
    } 
    function getTextOnly(){
        $n = strpos($this->textError, ' ');
        if ($n === false){
            return '';
        }
        return substr($this->textError, $n+1);
    }
    function getLigneNumber(){
        $temp = explode(':', $this->textError);
        $l = $temp[1];
        $temp = explode(' ', $l);
        $l = $temp[0];
        
        if (isset($l)){
            return $l;
        }
        return -1;
    }
}
