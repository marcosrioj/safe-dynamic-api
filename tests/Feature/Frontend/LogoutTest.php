<?php
/* 
 *  Copyright (C) SafeDynamiAPI - All Rights Reserved
 *  Unauthorized copying of this file, via any medium is strictly prohibited
 *  Proprietary and confidential
 *  Written by Marcos Lima <marcoslima@marcoslima.com>, Sat Feb 06 2021
 *  File: LogoutTest.php
 */
namespace Tests\Feature\Frontend;

use App\Domains\Auth\Models\User;
use Tests\TestCase;

/**
 * Class LogoutTest.
 */
class LogoutTest extends TestCase
{
    /** @test */
    public function the_user_can_logout()
    {
        $this->actingAs($user = User::factory()->create());

        $this->assertAuthenticatedAs($user);

        $this->post('/logout')->assertRedirect('/');

        $this->assertFalse($this->isAuthenticated());
    }
}
