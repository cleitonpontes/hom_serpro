<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $response = $this->get('/');

        // Ok or Redirect
        $validResponsesStatus = [200, 302];
        $status = $response->getStatusCode();

        $this->assertTrue(in_array($status, $validResponsesStatus));
    }
}
