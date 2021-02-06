<?php
/* 
 *  Copyright (C) SafeDynamiAPI - All Rights Reserved
 *  Unauthorized copying of this file, via any medium is strictly prohibited
 *  Proprietary and confidential
 *  Written by Marcos Lima <marcoslima@marcoslima.com>, Sat Feb 06 2021
 *  File: HomeTest.php
 */
namespace Tests\Feature\Frontend;

use Tests\TestCase;

/**
 * Class HomeTest.
 */
class HomeTest extends TestCase
{
    /** @test */
    public function the_home_page_exists()
    {
        $this->get('/')->assertOk();
    }
}
