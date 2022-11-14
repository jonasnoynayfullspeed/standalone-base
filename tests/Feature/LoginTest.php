<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class LoginTest extends TestCase
{
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFaker();
    }

    /**
     * Test if the admin can see the page.
     *
     * @return void
     */
    public function testAdminCanViewSignInPage()
    {
        $response = $this->get('/signIn');

        $response->assertSuccessful();
        $response->assertViewIs('signin');
    }

    /**
     * Test if the admin can login using the valid credentials.
     *
     * @return void
     */
    public function testAdminCanLoginWeb()
    {
        $response = $this->post('/login', [
            'username' => env('MANAGE_ACCOUNT'),
            'password' => env('MANAGE_PASSWORD'),
        ]);

        $response->assertRedirect('/');
    }

    /**
     * Test if the admin can login using the valid credentials
     * and API token.
     *
     * @return void
     */
    public function testAdminCanLoginApi()
    {
        $response = $this->withHeaders([
            'apiToken' => env('API_TOKEN'),
        ])->post('/login', [
            'username' => env('MANAGE_ACCOUNT'),
            'password' => env('MANAGE_PASSWORD'),
        ]);

        $response->assertRedirect('/');
    }

    /**
     * Check the authentication errors.
     *
     * @return void
     */
    public function testAdminLoginErrorWeb()
    {
        $response = $this->post('/login', [
            'username' => $this->faker->userName,
            'password' => $this->faker->password,
        ]);

        $response->assertSessionHasErrors('error');
        $response->assertRedirect('/signIn');
        $this->assertGuest();
    }

    /**
     * Check the authentication errors
     * with the API token.
     *
     * @return void
     */
    public function testAdminLoginErrorApi()
    {
        $response = $this->withHeaders([
            'apiToken' => $this->faker->text,
        ])->post('/login', [
            'username' => $this->faker->userName,
            'password' => $this->faker->password,
        ]);

        $response->assertRedirect('/signIn');
    }

    /**
     * Test if the admin can logout.
     *
     * @return void
     */
    public function testAdminCanLogout()
    {
        $this->get('/logout')
            ->assertRedirect('signIn');
        $this->assertGuest();
    }
}
