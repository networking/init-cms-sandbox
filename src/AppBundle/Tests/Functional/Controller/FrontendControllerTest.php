<?php

namespace AppBundle\Tests\Functional\Controller;

use AppBundle\Lib\WebTestCase;

class FrontendControllerTest extends WebTestCase
{

    public function testLanguageSwitchToGerman()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertTrue($crawler->filter('a:contains("Deutsch")')->count() == 1, 'there is a link for deutsch');

        $link = $crawler->selectLink('Deutsch')->link();
        $client->click($link);
        $this->assertTrue($client->getResponse()->isRedirect('/'), 'language switch does a redirect');
        $crawler = $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), 'no further redirect');
        $this->assertTrue(
            $crawler->filter('div.jumbotron p:contains("The locale of this page is de_CH")')->count() == 1,
            'Language is switched'
        );
        $this->assertTrue(
            $crawler->filter('.header ul li.active a:contains("Deutsch")')->count() == 1,
            'active is german'
        );
        $this->assertTrue(
            $crawler->filter('.header ul li.active a:contains("English")')->count() == 0,
            'english is not active'
        );
    }

    public function testLanguageSwitchToEnglish()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertTrue($crawler->filter('a:contains("English")')->count() == 1, 'there is a link for english');

        $link = $crawler->selectLink('English')->link();
        $crawler = $client->click($link);
        $this->assertTrue($client->getResponse()->isRedirect('/'), 'language switch does a redirect');
        $crawler = $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), 'no further redirect');
        $this->assertTrue(
            $crawler->filter('.jumbotron p:contains("The locale of this page is en_US")')->count() == 1,
            'Language is switched'
        );
        $this->assertTrue(
            $crawler->filter('.header ul li.active a:contains("English")')->count() == 1,
            'active is English'
        );
        $this->assertTrue(
            $crawler->filter('.header ul li.active a:contains("Deutsch")')->count() == 0,
            'german is not active'
        );
    }

}
