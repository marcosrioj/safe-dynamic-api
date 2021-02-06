<?php
/* 
 *  Copyright (C) SafeDynamiAPI - All Rights Reserved
 *  Unauthorized copying of this file, via any medium is strictly prohibited
 *  Proprietary and confidential
 *  Written by Marcos Lima <marcoslima@marcoslima.com>, Sat Feb 06 2021
 *  File: ListRoleTest.php
 */
namespace Tests\Feature\Backend\Role;

use App\Domains\Auth\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Class ReadRolesTest.
 */
class ListRoleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_admin_can_access_the_role_index_page()
    {
        $this->loginAsAdmin();

        $this->get('/admin/auth/role')->assertOk();
    }

    /** @test */
    public function only_admin_can_view_roles()
    {
        $this->actingAs(User::factory()->admin()->create());

        $response = $this->get('/admin/auth/role');

        $response->assertSessionHas('flash_danger', __('You do not have access to do that.'));
    }
}
