<?php
/* 
 *  Copyright (C) SafeDynamiAPI - All Rights Reserved
 *  Unauthorized copying of this file, via any medium is strictly prohibited
 *  Proprietary and confidential
 *  Written by Marcos Lima <marcoslima@marcoslima.com>, Sat Feb 06 2021
 *  File: RoleCreated.php
 */
namespace App\Domains\Auth\Events\Role;

use App\Domains\Auth\Models\Role;
use Illuminate\Queue\SerializesModels;

/**
 * Class RoleCreated.
 */
class RoleCreated
{
    use SerializesModels;

    /**
     * @var
     */
    public $role;

    /**
     * @param $role
     */
    public function __construct(Role $role)
    {
        $this->role = $role;
    }
}
