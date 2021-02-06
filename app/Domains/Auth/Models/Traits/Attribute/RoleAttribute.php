<?php
/* 
 *  Copyright (C) SafeDynamiAPI - All Rights Reserved
 *  Unauthorized copying of this file, via any medium is strictly prohibited
 *  Proprietary and confidential
 *  Written by Marcos Lima <marcoslima@marcoslima.com>, Sat Feb 06 2021
 *  File: RoleAttribute.php
 */
namespace App\Domains\Auth\Models\Traits\Attribute;

/**
 * Trait RoleAttribute.
 */
trait RoleAttribute
{
    /**
     * @return string
     */
    public function getPermissionsLabelAttribute(): string
    {
        if ($this->isAdmin()) {
            return 'All';
        }

        if (! $this->permissions->count()) {
            return 'None';
        }

        return collect($this->getPermissionDescriptions())
            ->implode('<br/>');
    }
}
