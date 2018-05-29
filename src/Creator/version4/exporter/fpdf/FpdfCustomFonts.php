<?php
declare(strict_types=1);

namespace EclipsePhaseCharacterCreator\Site\exporter\fpdf;


/**
 * An class allowing for importing and extending custom fonts.
 * It's basically a workaround for FPDF not working correctly.
 */
class FpdfCustomFonts extends \FPDF
{
    public function __construct(string $orientation = 'P', string $unit = 'mm', string $size = 'A4')
    {
        parent::__construct($orientation, $unit, $size);
        $this->fontpath = __DIR__ . '/font/';
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->$name;
    }
}
