<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BasicTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testPage()
    {
        // test redirection to a post page
        $response = $this->get('/');
        $response->assertStatus(302);

        // test post page load
        $page_id = rand(pow(10, 4 - 1), pow(10, 4) - 1);
        $response = $this->get('/' . $page_id);
        $response->assertStatus(200);
    }
}
