<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use App\User;
use Carbon\Carbon;

class UsersTests extends TestCase {

    use WithFaker,
        RefreshDatabase;

    /** @test */
    public function users_can_register_into_the_system() {
        $this->withoutExceptionHandling();
        $attributes = factory('App\User')->raw();
        $this->post('/register', $attributes)->assertRedirect('/pending-for-confirmation');
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
        $this->post('/register', $attributes)->assertSessionHasErrors('name');
    }

    /** @test */
    public function an_email_is_required() {
        $attributes = factory('App\User')->raw(['email' => '']);
        $this->post('/register', $attributes)->assertSessionHasErrors('email');
    }

    /** @test */
    public function password_is_required() {
        $attributes = factory('App\User')->raw(['password' => '']);
        $this->post('/register', $attributes)->assertSessionHasErrors('password');
    }

    /** @test */
    public function language_is_required() {
        $attributes = factory('App\User')->raw(['language' => '']);
        $this->post('/register', $attributes)->assertSessionHasErrors('language');
    }

    /** @test */
    public function tel_is_required() {
        $attributes = factory('App\User')->raw(['tel' => '']);
        $this->post('/register', $attributes)->assertSessionHasErrors('tel');
    }

    /** @test */
    public function country_is_required() {
        $attributes = factory('App\User')->raw(['country' => '']);
        $this->post('/register', $attributes)->assertSessionHasErrors('country');
    }

    /** @test */
    public function communication_media_is_required() {
        $attributes = factory('App\User')->raw(['communication_media' => '']);
        $this->post('/register', $attributes)->assertSessionHasErrors('communication_media');
    }

    /** @test */
    public function only_SystemAdmin_users_can_see_the_all_users_list() {
        $user = factory('App\User')->create(['access_level' => 'SystemAdmin']);
        $this->actingAs($user);
        $this->get('/all-users')->assertSee($user->name);
    }

    /** @test */
    public function other_users_cant_see_the_all_users_list() {
        $user = factory('App\User')->create();
        $this->actingAs($user);
        $this->get('/all-users')->assertSee('access-denied');
    }

    /** @test */
    public function guests_cannot_view_all_users_list() {
        $this->get('/all-users')->assertRedirect('/access-denied');
    }

    /** @test */
    public function just_SystemAdmin_can_edit_users() {
        $user = factory('App\User')->create();
        $this->actingAs(factory('App\User')->create(['access_level' => 'SystemAdmin']));
        $this->patch('/all-users/' . $user->id, [
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
        $this->assertInstanceOf(Carbon::class, DB::table('users')->where('id', $user->id)->last_login);
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

}
