<?php
declare(strict_types=1);

namespace EclipsePhaseCharacterCreator\Site\other;

/**
 * A class to help in adding icons to the page
 *
 * Use this instead of writing raw HTML.
 */
class Icon{
    static $html_template = "<span class='{html_class}' id='{id}' data-icon='{icon}'></span>";
    static $checked = '&#x2b;';
    static $plus    = '&#x3a;';
    static $minus   = '&#x3b;';
    static $X       = '&#x39;';

    /**
     * A function to enable Python style string formatting
     */
    private static function format($in_string,$vars){
        $output = $in_string;
        foreach ($vars as $key => $value) {
            $output = str_replace('{'.$key.'}',$value,$output);
        }
        return $output;
    }

    /**
     * Get the HTML for an icon.
     */
    public static function getHtml($class,$id,$icon){
        $vars = array(
            'html_class' => $class,
            'id' => $id,
            'icon' => $icon
        );
        return static::format(Icon::$html_template,$vars);
    }
}
