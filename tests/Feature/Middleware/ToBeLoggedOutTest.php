<?php
/* 
 *  Copyright (C) SafeDynamiAPI - All Rights Reserved
 *  Unauthorized copying of this file, via any medium is strictly prohibited
 *  Proprietary and confidential
 *  Written by Marcos Lima <marcoslima@marcoslima.com>, Sat Feb 06 2021
 *  File: ToBeLoggedOutTest.php
 */
namespace Tests\Feature\Middleware;

use App\Domains\Auth\Models\User;
use Tests\TestCase;

/**
 * Class ToBeLoggedOutTest.
 */
class ToBeLoggedOutTest extends TestCase
{
    /** @test */
    public function the_user_can_be_forced_logged_out()
    {
        $user = User::factory()->user()->create(['to_be_logged_out' => false]);

        $this->actingAs($user);

        $this->get('/dashboard')->assertOk();

        $user->update(['to_be_logged_out' => true]);

        $this->get('/dashboard')->assertRedirect('/login');

        $this->assertFalse($this->isAuthenticated());
    }
}
