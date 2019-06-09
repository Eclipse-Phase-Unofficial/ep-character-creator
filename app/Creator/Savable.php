<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 5/28/18
 * Time: 12:00 PM
 */

namespace App\Creator;


/**
 * This indicates the object can be stored and retrieved.
 * TODO:  Have objects use the serializable interface instead
 * TODO:  Don't store meaningless objects
 */
interface Savable
{
    function getSavePack(): array;
    static function __set_state(array $an_array);
}
