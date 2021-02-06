<?php
/* 
 *  Copyright (C) SafeDynamiAPI - All Rights Reserved
 *  Unauthorized copying of this file, via any medium is strictly prohibited
 *  Proprietary and confidential
 *  Written by Marcos Lima <marcoslima@marcoslima.com>, Sat Feb 06 2021
 *  File: ClearSessionTest.php
 */
namespace Tests\Feature\Backend\User;

use App\Domains\Auth\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Class ClearSessionTest.
 */
class ClearSessionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function only_a_user_with_correct_permissions_can_clear_user_sessions()
    {
        $this->actingAs($user = User::factory()->admin()->create());

        $user->syncPermissions(['admin.access.user.clear-session']);

        $newUser = User::factory()->create();

        $response = $this->post('/admin/auth/user/'.$newUser->id.'/clear-session');

        $response->assertSessionHas('flash_success', __('The user\'s session was successfully cleared.'));

        $user->syncPermissions([]);

        $response = $this->post('/admin/auth/user/'.$newUser->id.'/clear-session');

        $response->assertSessionHas('flash_danger', __('You do not have access to do that.'));
    }

    /** @test */
    public function a_user_can_not_clear_their_own_session()
    {
        $this->actingAs($user = User::factory()->admin()->create());

        $user->syncPermissions(['admin.access.user.clear-session']);

        $response = $this->post('/admin/auth/user/'.$user->id.'/clear-session');

        $response->assertSessionHas('flash_danger', __('You can not clear your own session.'));
    }
}
