<?php
declare(strict_types=1);

namespace EclipsePhaseCharacterCreator\Backend;

/**
 * Simple data struct to translate cost levels into actual values
 *
 * @author reinhardt
 */
class EPCreditCost {
    
    static $TRIVIAL = 50;
    static $LOW =  250;
    static $MODERATE = 1000;
    static $HIGH = 5000;
    static $EXPENSIVE = 20000;
    static $VERY_EXPENSIVE = 40000;
    static $EXTREMELY_EXPENSIVE = 60000;
    
    public $value;
    
    function __construct($cost) {
        $this->value = $cost;
    }
}
