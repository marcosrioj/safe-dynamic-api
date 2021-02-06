<?php
/* 
 *  Copyright (C) SafeDynamiAPI - All Rights Reserved
 *  Unauthorized copying of this file, via any medium is strictly prohibited
 *  Proprietary and confidential
 *  Written by Marcos Lima <marcoslima@marcoslima.com>, Sat Feb 06 2021
 *  File: PermissionScope.php
 */
namespace App\Domains\Auth\Models\Traits\Scope;

/**
 * Class PermissionScope.
 */
trait PermissionScope
{
    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeIsMaster($query)
    {
        return $query->whereDoesntHave('parent')
            ->whereHas('children');
    }

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeIsParent($query)
    {
        return $query->whereHas('children');
    }

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeIsChild($query)
    {
        return $query->whereHas('parent');
    }

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeSingular($query)
    {
        return $query->whereNull('parent_id')
            ->whereDoesntHave('children');
    }
}
