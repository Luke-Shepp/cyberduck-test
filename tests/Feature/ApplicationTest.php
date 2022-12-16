<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApplicationTest extends TestCase
{
    public function testApplicationIsUp()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
