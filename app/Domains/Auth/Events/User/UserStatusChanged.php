<?php
/* 
 *  Copyright (C) SafeDynamiAPI - All Rights Reserved
 *  Unauthorized copying of this file, via any medium is strictly prohibited
 *  Proprietary and confidential
 *  Written by Marcos Lima <marcoslima@marcoslima.com>, Sat Feb 06 2021
 *  File: UserStatusChanged.php
 */
namespace App\Domains\Auth\Events\User;

use App\Domains\Auth\Models\User;
use Illuminate\Queue\SerializesModels;

/**
 * Class UserStatusChanged.
 */
class UserStatusChanged
{
    use SerializesModels;

    /**
     * @var
     */
    public $user;

    /**
     * @var
     */
    public $status;

    /**
     * UserStatusChanged constructor.
     *
     * @param  User  $user
     * @param $status
     */
    public function __construct(User $user, $status)
    {
        $this->user = $user;
        $this->status = $status;
    }
}
