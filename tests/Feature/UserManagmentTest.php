<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use App\User;
use Carbon\Carbon;

class UserManagmentTest extends TestCase {

    use WithFaker,
        RefreshDatabase;

    /** @test */
        public function users_can_register_into_the_system() {
        $this->withoutExceptionHandling();
        $attributes = factory('App\User')->raw();
        $this->get('/create')->assertOk(200);
        $this->post('/users', $attributes)->assertRedirect('/pending-for-confirmation');
        $this->assertCount(1, User::all());
    }

    /** @test */
    public function see_pending_for_confirmation_after_user_registration() {
        $this->withoutExceptionHandling();
        $this->get('/pending-for-confirmation')->assertSee('Please wait for confirmation');
    }

    /** @test */
    public function a_name_is_required() {
//        $this->withoutExceptionHandling();
        $attributes = factory('App\User')->raw(['name' => '']);
        $this->post('/users', $attributes)->assertSessionHasErrors('name');
    }

    /** @test */
    public function an_email_is_required() {
        $attributes = factory('App\User')->raw(['email' => '']);
        $this->post('/users', $attributes)->assertSessionHasErrors('email');
    }

    /** @test */
    public function password_is_required() {
        $attributes = factory('App\User')->raw(['password' => '']);
        $this->post('/users', $attributes)->assertSessionHasErrors('password');
    }

    /** @test */
    public function language_is_required() {
        $attributes = factory('App\User')->raw(['language' => '']);
        $this->post('/users', $attributes)->assertSessionHasErrors('language');
    }

    /** @test */
    public function tel_is_required() {
        $attributes = factory('App\User')->raw(['tel' => '']);
        $this->post('/users', $attributes)->assertSessionHasErrors('tel');
    }

    /** @test */
    public function country_is_required() {
        $attributes = factory('App\User')->raw(['country' => '']);
        $this->post('/users', $attributes)->assertSessionHasErrors('country');
    }

    /** @test */
    public function communication_media_is_required() {
        $attributes = factory('App\User')->raw(['communication_media' => '']);
        $this->post('/users', $attributes)->assertSessionHasErrors('communication_media');
    }

    /** @test */
    public function only_SystemAdmin_can_see_all_users() {
        $user = factory('App\User')->create(['access_level' => 'SystemAdmin']);
        $this->actingAs($user);
        $this->get('/users')->assertSee($user->name);
    }

    /** @test */
    public function just_SystemAdmin_can_edit_users() {
        $this->withoutExceptionHandling();
        $user = factory('App\User')->create();
        $this->actingAs(factory('App\User')->create(['access_level' => 'SystemAdmin']));
        $this->patch('/users/' . $user->id, [
            'confirmed' => 1,
            'access_level' => 'BuyerAdmin',
            'lock' => 0
        ]);
        $this->assertEquals(1, DB::table('users')->where('id', $user->id)->value('confirmed'));
        $this->assertEquals('BuyerAdmin', DB::table('users')->where('id', $user->id)->value('access_level'));
        $this->assertEquals(0, DB::table('users')->where('id', $user->id)->value('lock'));
    }

    /** @test */
    public function only_SystemAdmin_can_see_users_profile_page() {
        $this->withoutExceptionHandling();
        $user = factory('App\User')->create();
        $this->actingAs(factory('App\User')->create(['access_level' => 'SystemAdmin']));
        $response = $this->get($user->path());
        $this->assertInstanceOf(Carbon::class, $user->last_login);
        $this->assertInstanceOf(Carbon::class, $user->created_at);
        $this->assertInstanceOf(Carbon::class, $user->updated_at);
        //$this->assertEquals($user->last_login, $user->last_login->format('Y/m/d'));
        $response->assertSeeTextInOrder([
            $user->id,
            $user->name,
            $user->email,
            $user->confirmed,
            $user->access_level,
//            $user->last_login,
            $user->lock,
            $user->last_ip,
            $user->language,
            $user->tel,
            $user->country,
            $user->communication_media,
        ]);
    }

    /** @test */
    public function other_users_can_not_access_user_managment_system ()
    {
        $user = factory('App\User')->create(['access_level' => 'Retailer']);
        $this->actingAs($user);
        //other users can not see users list
        $this->get('/users')->assertRedirect('/access-denied');
        //other users can not edit users
        $this->patch('/users/' . $user->id)->assertRedirect('/access-denied');
        //other users can not see user profil page
        $this->get($user->path())->assertRedirect('/access-denied');


    }

    /** @test */
    public function guest_can_not_access_user_managment_system()
    {
        $user = factory('App\User')->create();
        //guest can not view all users
        $this->get('/users')->assertRedirect('login');
        //guest can not edit users
        $this->patch('/users/' . $user->id)->assertRedirect('login');
        //guest can not see user profile page
        $this->get($user->path())->assertRedirect('login');
    }

    /** @test */
    public function users_can_not_be_deleted_from_system() {
        $this->withoutExceptionHandling();
        $this->delete('/users')->assertRedirect('/access-denied');
    }

}
