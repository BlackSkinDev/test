<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\User;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use JWTAuth;

use Illuminate\Http\Response;



class UserControllerTest extends TestCase
{
    use RefreshDatabase;



    /** @test */
    public function non_auth_user_cannot_access_below_endpoint(){

        $fetchProfile= $this->json('GET','api/v1/auth/user');
        $fetchProfile->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public  function a_user_can_register(){
        $faker=\Faker\Factory::create();
        $response= $this->json('POST','api/v1/auth/register',[
            'name'=>$faker->name,
            'email' =>$faker->safeEmail,
            'password'=>$password= '1234567'

        ]);
        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson([
                'message' => 'Account Created Successfully'
            ]);
    }

    /** @test */
    public  function a_user_can_login(){

        $user= \App\Models\User::factory()->create();

        $loginData = ['email' => $user->email, 'password' => 'sample123'];

        $response= $this->json('POST','api/v1/auth/login',$loginData);

        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['token']);
    }


    /** @test */
    public function can_returned_logged_in_user_profile(){

        $user= \App\Models\User::factory()->create();
        $token= JWTAuth::fromUser($user);
        $response=$this->withHeaders(['Authorization' => "Bearer {$token}",])->json('GET','/api/v1/auth/user');
        $response->assertStatus(Response::HTTP_OK)
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
