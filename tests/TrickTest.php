<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TrickTest extends WebTestCase
{
    public function testHome(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();

        $this->assertCount(2, $crawler->filter("div.trick-card"));
    }

    public function testTrick(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/trick/view/grab-mute');

        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains('div.header-title', 'Mute');
    }
}
