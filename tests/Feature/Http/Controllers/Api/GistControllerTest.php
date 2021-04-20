<?php

namespace Tests\Unit;
use App\Models\Gist;
use App\Models\User;
use Database\Factories\GistFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Faker\Factory;
use Illuminate\Http\Response;
use JWTAuth;

class GistControllerTest extends TestCase
{
   use RefreshDatabase;


    /** @test */
    public function non_auth_user_cannot_access_below_endpoints(){

        $index= $this->json('GET','api/v1/gists');
        $index->assertStatus(Response::HTTP_UNAUTHORIZED);


        $store= $this->json('POST','api/v1/gists');
        $store->assertStatus(Response::HTTP_UNAUTHORIZED);

        $show= $this->json('GET','api/v1/gists/-1');
        $show->assertStatus(Response::HTTP_UNAUTHORIZED);

        $update= $this->json('PUT','api/v1/gists/-1');
        $update->assertStatus(Response::HTTP_UNAUTHORIZED);

        $destroy= $this->json('DELETE','api/v1/gists/-1');
        $destroy->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public  function a_logged_in_user_can_create_a_gist(){

        $faker=\Faker\Factory::create();
        $user= \App\Models\User::factory()->create();
        $token= JWTAuth::fromUser($user);
        $response=$this->withHeaders(['Authorization' => "Bearer {$token}",])->json('POST','api/v1/gists',[
            'title'=>$name= $faker->text(100),
            'body' => $email= $faker->text,
        ]);
        $response->assertStatus(Response::HTTP_CREATED)
            ->assertExactJson([
                'message' => 'Gist Created Successfully'
            ]);




    }

    /** @test */
    public function a_logged_in_user_can_get_all_gists(){

        \App\Models\Gist::factory(5)->create();
        $user= \App\Models\User::factory()->create();
        $token= JWTAuth::fromUser($user);
        $response=$this->withHeaders(['Authorization' => "Bearer {$token}",])->json('GET','api/v1/gists');
        $response->assertStatus(Response::HTTP_OK);
        $allgists=Gist::all()->count();
        $response->assertJsonCount($allgists,'gists');


    }


    /** @test */
    public  function will_fail_with_a_404_if_gist_to_be_returned_is_not_found(){
        $user= \App\Models\User::factory()->create();
        $token= JWTAuth::fromUser($user);
        $response=$this->withHeaders(['Authorization' => "Bearer {$token}",])->json('GET',"api/v1/gists/-3");
        $response->assertStatus(404);
    }

    /** @test */
    public function a_logged_in_user_can_get_single_gist(){
        $gist=\App\Models\Gist::factory()->create();
        $user= \App\Models\User::factory()->create();
        $token= JWTAuth::fromUser($user);
        $response=$this->withHeaders(['Authorization' => "Bearer {$token}",])->json('GET',"api/v1/gists/{$gist->uuid}");
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'gist' => [
                    'uuid',
                    'title',
                    'body',
                    'created_at',
                    'updated_at'
                ]
            ])
            ->assertExactJson([
                'gist'=>[
                    "uuid"=>$gist->uuid,
                    "title"=>$gist->title,
                    "body"=>$gist->body,
                    "created_at"=>$gist->created_at,
                    "updated_at"=>$gist->updated_at

                ]
            ]);

    }

    /** @test */
    public  function will_fail_with_a_404_if_gist_to_be_updated_is_not_found(){
        $faker=\Faker\Factory::create();
        $user= \App\Models\User::factory()->create();
        $token= JWTAuth::fromUser($user);
        $response=$this->withHeaders(['Authorization' => "Bearer {$token}",])->json('PUT',"api/v1/gists/-3",[
            'title'=>$name= $faker->text(100),
            'body' => $email= $faker->text,
        ]);
        $response->assertStatus(404);
    }

    /** @test */
    public function a_logged_in_user_can_update_his_gist(){
        $faker=\Faker\Factory::create();
        $gist=\App\Models\Gist::factory()->create();
        $user=$gist->user;
        $token= JWTAuth::fromUser($user);
        $response=$this->withHeaders(['Authorization' => "Bearer {$token}",])->json('PUT',"api/v1/gists/{$gist->uuid}",[
            'title'=>$name= $faker->paragraph,
            'body' => $email= $faker->text,
        ]);
        $response->assertStatus(Response::HTTP_OK)
            ->assertExactJson([
                'message' => 'gist updated successfully'
            ]);
    }

    /** @test */
    public  function will_fail_with_a_404_if_gist_to_be_deleted_is_not_found(){
        $user= \App\Models\User::factory()->create();
        $token= JWTAuth::fromUser($user);
        $response=$this->withHeaders(['Authorization' => "Bearer {$token}",])->json('DELETE',"api/v1/gists/-3");
        $response->assertStatus(404);
    }

    /** @test */
    public function a_logged_in_user_can_delete_his_gist(){
        $faker=\Faker\Factory::create();
        $gist=\App\Models\Gist::factory()->create();
        $user=$gist->user;
        $token= JWTAuth::fromUser($user);
        $response=$this->withHeaders(['Authorization' => "Bearer {$token}",])->json('DELETE',"api/v1/gists/{$gist->uuid}");
        $response->assertStatus(Response::HTTP_OK)
            ->assertExactJson([
                'message' => 'gist deleted successfully'
            ]);
    }



}
