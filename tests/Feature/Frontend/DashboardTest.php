<?php
/* 
 *  Copyright (C) SafeDynamiAPI - All Rights Reserved
 *  Unauthorized copying of this file, via any medium is strictly prohibited
 *  Proprietary and confidential
 *  Written by Marcos Lima <marcoslima@marcoslima.com>, Sat Feb 06 2021
 *  File: DashboardTest.php
 */
namespace Tests\Feature\Frontend;

use App\Domains\Auth\Models\User;
use Tests\TestCase;

/**
 * Class DashboardTest.
 */
class DashboardTest extends TestCase
{
    /** @test */
    public function only_authenticated_users_can_access_their_account()
    {
        $this->get('/dashboard')->assertRedirect('/login');

        $this->actingAs(User::factory()->user()->create());

        $this->get('/dashboard')->assertOk();
    }
}
