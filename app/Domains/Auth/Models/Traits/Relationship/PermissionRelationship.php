<?php
/* 
 *  Copyright (C) SafeDynamiAPI - All Rights Reserved
 *  Unauthorized copying of this file, via any medium is strictly prohibited
 *  Proprietary and confidential
 *  Written by Marcos Lima <marcoslima@marcoslima.com>, Sat Feb 06 2021
 *  File: PermissionRelationship.php
 */
namespace App\Domains\Auth\Models\Traits\Relationship;

/**
 * Class PermissionRelationship.
 */
trait PermissionRelationship
{
    /**
     * @return mixed
     */
    public function parent()
    {
        return $this->belongsTo(__CLASS__, 'parent_id')->with('parent');
    }

    /**
     * @return mixed
     */
    public function children()
    {
        return $this->hasMany(__CLASS__, 'parent_id')->with('children');
    }
}
