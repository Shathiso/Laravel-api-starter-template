<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserRegistrationTest extends TestCase
{

    use RefreshDatabase, WithFaker;

    /** @test */
    public function a_user_can_register(){
      
        $payload = [
          'firstname' => $this->faker->firstName,
          'lastname'  => $this->faker->lastName,
          'role'      => 'admin',
          'email'     => $this->faker->email,
          'password'  => $this->faker->password(8,20)
        ];
  
        $response = $this->json('post', 'api/register', $payload);
        $response->assertStatus(200);
        
    }
}
