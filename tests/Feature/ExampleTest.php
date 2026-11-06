<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * The root path is a router, not a page: it sends guests to the login
     * screen and signed-in users to their dashboard, so a redirect is the
     * correct response here.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
