<?php
declare(strict_types=1);

namespace App\Creator\DisplayHelpers;


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

    /**
     * Acts mostly like a normal $pdf->Cell, but re-sizes the current font so the text always fits on one line
     * @param int             $width
     * @param int             $height
     * @param string          $text
     * @param bool            $useFill
     */
    function singleCell(int $width,int $height,string $text, bool $useFill = false)
    {
        //If the column is too long, drop the font size accordingly so it fits in a single line
        while($this->GetStringWidth($text) > $width)
        {
            $this->SetFontSize($this->FontSizePt - 1);
            error_log($this->FontSizePt."->".$text.":  ".$this->GetStringWidth($text));
        }
        $this->Cell($width,$height,$text,0,0,'l',$useFill);
    }
}
