<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\TestResponse;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Test that the response is good, and exactly matches the json in a file
     *
     * @param string $uri
     * @param string $jsonFile
     * @return TestResponse
     */
    function validateJson(string $uri, string $jsonFile)
    {
        $response = $this->get($uri);
        $response->assertStatus(200);
        $response->assertJson(json_decode(file_get_contents($jsonFile), true));
        return $response;
    }
}
