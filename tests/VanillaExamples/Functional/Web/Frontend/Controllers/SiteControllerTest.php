<?php

namespace App\Tests\Functional\Web\Frontend\Controllers;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SiteControllerTest extends WebTestCase
{
    public function testMainpageOk()
    {
        $client = static::createClient();
        $client->request('GET', '/');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Hello!');
    }
}