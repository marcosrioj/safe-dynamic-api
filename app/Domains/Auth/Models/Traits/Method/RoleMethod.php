<?php
/* 
 *  Copyright (C) SafeDynamiAPI - All Rights Reserved
 *  Unauthorized copying of this file, via any medium is strictly prohibited
 *  Proprietary and confidential
 *  Written by Marcos Lima <marcoslima@marcoslima.com>, Sat Feb 06 2021
 *  File: RoleMethod.php
 */
namespace App\Domains\Auth\Models\Traits\Method;

use Illuminate\Support\Collection;

/**
 * Trait RoleMethod.
 */
trait RoleMethod
{
    /**
     * @return mixed
     */
    public function isAdmin(): bool
    {
        return $this->name === config('boilerplate.access.role.admin');
    }

    /**
     * @return Collection
     */
    public function getPermissionDescriptions(): Collection
    {
        return $this->permissions->pluck('description');
    }
}
