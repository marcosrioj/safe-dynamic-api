<?php
/* 
 *  Copyright (C) SafeDynamiAPI - All Rights Reserved
 *  Unauthorized copying of this file, via any medium is strictly prohibited
 *  Proprietary and confidential
 *  Written by Marcos Lima <marcoslima@marcoslima.com>, Sat Feb 06 2021
 *  File: DashboardTest.php
 */
namespace Tests\Feature\Backend;

use App\Domains\Auth\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Class DashboardTest.
 */
class DashboardTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function unauthenticated_users_cant_access_admin_dashboard()
    {
        $this->get('/admin/dashboard')->assertRedirect('/login');
    }

    /** @test */
    public function not_authorized_users_cant_access_admin_dashboard()
    {
        $this->actingAs(User::factory()->user()->create());

        $response = $this->get('/admin/dashboard');

        $response->assertRedirect('/');

        $response->assertSessionHas('flash_danger', __('You do not have access to do that.'));
    }

    /** @test */
    public function admin_can_access_admin_dashboard()
    {
        $this->loginAsAdmin();

        $this->get('/admin/dashboard')->assertOk();
    }
}
