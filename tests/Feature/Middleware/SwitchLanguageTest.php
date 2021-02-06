<?php
/* 
 *  Copyright (C) SafeDynamiAPI - All Rights Reserved
 *  Unauthorized copying of this file, via any medium is strictly prohibited
 *  Proprietary and confidential
 *  Written by Marcos Lima <marcoslima@marcoslima.com>, Sat Feb 06 2021
 *  File: SwitchLanguageTest.php
 */
namespace Tests\Feature\Middleware;

use Tests\TestCase;

/**
 * Class SwitchLanguageTest.
 */
class SwitchLanguageTest extends TestCase
{
    /** @test */
    public function the_language_can_be_switched()
    {
        $response = $this->get('/lang/de');

        $response->assertSessionHas('locale', 'de');
    }
}
