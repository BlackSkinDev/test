<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\User;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use JWTAuth;



class UserControllerTest extends TestCase
{
    use RefreshDatabase;



    /** @test */
    public function non_auth_user_cannot_access_below_endpoint(){

        $index= $this->json('GET','api/v1/auth/user');
        $index->assertStatus(401);
    }

    /** @test */
    public  function a_user_can_register(){
        $faker=\Faker\Factory::create();
        $response= $this->json('POST','api/v1/auth/register',[
            'name'=>$name= $faker->company,
            'email' => $email= $faker->safeEmail,
            'password'=>$password= '1234567'

        ]);
        $response
            ->assertStatus(200)
            ->assertExactJson([
                'message' => 'Account Created Successfully'
            ]);
    }

    /** @test */
    public  function a_user_can_login(){

        $user= \App\Models\User::factory()->create();

        $loginData = ['email' => 'sample@test.com', 'password' => 'sample123'];

        $response= $this->json('POST','api/v1/auth/login',$loginData);

        $response
            ->assertStatus(200)
            ->assertJsonStructure(['token']);
    }


    /** @test */
    public function can_returned_logged_in_user_profile(){

        $user= \App\Models\User::factory()->create();
        $token= JWTAuth::fromUser($user);
        $response=$this->withHeaders(['Authorization' => "Bearer {$token}",])->json('GET','/api/v1/auth/user');
        $response->assertStatus(200)
            ->assertJsonStructure([
                'user' => [
                    'id',
                    'name',
                    'email',
                    'created_at',
                    'updated_at'
                ]
            ])
            ->assertExactJson([
                'user'=> [
                'id'=>$user->id,
                'name'=>$user->name,
                'email'=>$user->email,
                'created_at'=>$user->created_at,
                'updated_at'=>$user->updated_at
                ]
            ]);

    }


}
