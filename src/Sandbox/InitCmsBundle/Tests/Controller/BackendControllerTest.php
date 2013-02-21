<?php

namespace Sandbox\InitCmsBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BackendControllerTest extends WebTestCase
{
    public function testAdmin()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/admin');
        $this->assertTrue($client->getResponse()->isRedirect('http://localhost/admin/login'), 'redirect to login page');
        $crawler = $client->followRedirect();
        $this->assertRegExp('#admin/login_check#', $client->getResponse()->getContent(), 'its the login page');

    }

    public function testWrongLogin()
    {
        restoreDatabase();
        $client = static::createClient();
        $crawler = $client->request('GET', '/admin/login');
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), 'no further redirect');

        // try login with unknown user
        $form = $crawler->selectButton('Login')->form();
        // set some values
        $form['_username'] = 'foo1';
        $form['_password'] = 'bar1';
        // submit the form
        $crawler = $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect('http://localhost/admin/login'), 'redirect to login page');
        $crawler = $client->followRedirect();
        $this->assertTrue($crawler->filter('div .alert:contains("Bad credentials")')->count() == 1);

//        $this->assertTrue($client->getResponse()->isRedirect('http://localhost/admin/dashboard'), 'redirect to dashboard');
//        $crawler = $client->followRedirect();
//        $this->assertTrue($crawler->filter('html:contains("Dashboard")')->count() > 0, 'dashboard');

    }

    public function testLogin()
    {
        restoreDatabase();
        $client = static::createClient();
        $crawler = $client->request('GET', '/admin/login');
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), 'no further redirect');

        // try login with unknown user
        $form = $crawler->selectButton('Login')->form();
        // set some values
        $form['_username'] = 'foo';
        $form['_password'] = 'bar';
        // submit the form
        $crawler = $client->submit($form);


        $this->assertTrue($client->getResponse()->isRedirect('http://localhost/admin/dashboard'), 'redirect to dashboard');
        $crawler = $client->followRedirect();
        $this->assertTrue($crawler->filter('html:contains("Dashboard")')->count() > 0, 'dashboard');

    }

}
