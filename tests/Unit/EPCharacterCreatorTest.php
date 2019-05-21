<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 11/3/18
 * Time: 1:38 PM
 */

namespace Tests\Unit;


use App\Creator\EPCharacterCreator;
use Tests\TestCase;

class EPCharacterCreatorTest extends TestCase
{
    public function testCreatingACreator()
    {
        $creator = new EPCharacterCreator(1000);
        $this->assertInstanceOf(EPCharacterCreator::class, $creator);
    }
}