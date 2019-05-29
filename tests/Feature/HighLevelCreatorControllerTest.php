<?php

namespace Tests\Feature;

use App\Creator\EPCharacterCreator;
use Tests\TestCase;

class HighLevelCreatorControllerTest extends TestCase
{
    /**
     * Create a new (empty) character to run these tests against
     */
    public function setUp(): void
    {
        parent::setUp();
        session()->put('cc', new EPCharacterCreator(1000));
    }

    public function testSave()
    {
        $this->validateJson('/api/creator/save', __DIR__ . '/HighLevelCreatorController/save.json');
    }

    public function testGet()
    {
        $this->validateJson('/api/creator/', __DIR__ . '/HighLevelCreatorController/get.json');
    }

    public function testUpdate()
    {
        $this->markTestSkipped('TODO:  Implement Test Loading a character');
    }

    public function testValidateCharacter()
    {
        $this->validateJson('/api/creator/validate', __DIR__ . '/HighLevelCreatorController/validate.json');
    }

    public function testStore()
    {
        $this->markTestSkipped('TODO:  Implement the function');
    }
}
