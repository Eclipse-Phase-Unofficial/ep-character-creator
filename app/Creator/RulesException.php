<?php


namespace App\Creator;

/**
 * These are thrown when doing something would violate some rule somewhere.
 * Things like, trying to buy too much Moxie, or going over a cap.
 */
class RulesException extends \Exception
{

}