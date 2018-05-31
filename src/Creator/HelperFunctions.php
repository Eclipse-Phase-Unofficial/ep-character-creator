<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 5/30/18
 * Time: 10:31 PM
 */

use \EclipsePhaseCharacterCreator\Backend\EPCharacterCreator;

/**
 * A globally available helper function for accessing the creator
 * @return EPCharacterCreator|null
 */
function creator(): ?EPCharacterCreator
{
//    session_start();
    return $_SESSION['cc'] ?? null;
}