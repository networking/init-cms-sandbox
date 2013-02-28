<?php

namespace Sandbox\InitCmsBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BackendControllerTest extends WebTestCase
{
    public function testAdmin()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/admin', array('_locale' => 'en_US'));
        $this->assertTrue($client->getResponse()->isRedirect('http://localhost/admin/login'), 'redirect to login page');
        $crawler = $client->followRedirect();
        $this->assertRegExp('#admin/login_check#', $client->getResponse()->getContent(), 'its the login page');

    }

    public function testWrongLogin()
    {
        restoreDatabase();
        $client = static::createClient();
        $crawler = $client->request('GET', '/admin/login', array('admin/_locale' => 'en_US'));
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), 'no further redirect');

        // try login with unknown user
        $form = $crawler->filter('form')->form();
        // set some values
        $form['_username'] = 'foo1';
        $form['_password'] = 'bar1';
        // submit the form
        $crawler = $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect('http://localhost/admin/login'), 'redirect to login page');
        $crawler = $client->followRedirect();
        $this->assertTrue($crawler->filter('div .alert:contains("Bad credentials")')->count() == 1);
    }

    public function testLogin()
    {
        restoreDatabase();
        $client = static::createClient();
        $crawler = $client->request('GET', '/admin/login', array('admin/_locale' => 'en_US'));
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), 'no further redirect');

        // try login with correct user
        $form = $crawler->filter('form')->form();
        // set some values
        $form['_username'] = 'foo';
        $form['_password'] = 'bar';
        // submit the form
        $crawler = $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect('http://localhost/admin/dashboard'), 'redirect to dashboard');
        $crawler = $client->followRedirect();
        $this->assertTrue($crawler->filter('html:contains("Dashboard")')->count() > 0, 'dashboard');

    }

    public function testBackendLanguage()
    {
        restoreDatabase();
        $client = static::createClient();
        $crawler = $client->request('GET', '/admin/login');
        // try login with correct user
        $form = $crawler->filter('form')->form();
        // set some values
        $form['_username'] = 'foo';
        $form['_password'] = 'bar';
        // submit the form
        $crawler = $client->submit($form);
        $crawler = $client->followRedirect();
        $this->assertTrue($crawler->filter('html:contains("Dashboard")')->count() > 0, 'dashboard');

        // edit user
        $crawler = $client->request('GET', '/admin/cms/users/1/edit');
        $form = $crawler->filter('form')->form();
        // get the unique key
        $key = array_keys($form->getPhpValues());
        // set some values
        $form[$key[0].'[locale]'] = 'de_CH';
        // submit the form
        $crawler = $client->submit($form);
        // logout
        $crawler = $client->request('GET', '/admin/logout');


        // login
        $crawler = $client->request('GET', '/admin/login');

        // login again
        $form = $crawler->filter('form')->form();
        // set some values
        $form['_username'] = 'foo';
        $form['_password'] = 'bar';
        // submit the form
        $crawler = $client->submit($form);
        $crawler = $client->followRedirect();

        // is the locale de?
        $this->assertTrue($crawler->filter('a:contains("abmelden")')->count() > 0, 'it\'s German');

    }

    public function testBackendLanguage2()
    {
        restoreDatabase();
        $client = static::createClient();
        $crawler = $client->request('GET', '/admin/login');
        // try login with correct user
        $form = $crawler->filter('form')->form();
        // set some values
        $form['_username'] = 'foo';
        $form['_password'] = 'bar';
        // submit the form
        $crawler = $client->submit($form);
        $crawler = $client->followRedirect();
        $this->assertTrue($crawler->filter('html:contains("Dashboard")')->count() > 0, 'dashboard');


        $crawler = $client->request('GET', '/admin/cms/users/1/edit');
        // edit form
        $form = $crawler->filter('form')->form();
        // get form unique key
        $key = array_keys($form->getPhpValues());
        // set some values
        $form[$key[0].'[locale]'] = 'en_US';
        // submit the form
        $crawler = $client->submit($form);

        // logout
        $crawler = $client->request('GET', '/admin/logout');

        // login again
        $crawler = $client->request('GET', '/admin/login');
        // try to login
        $form = $crawler->filter('form')->form();
        // set some values
        $form['_username'] = 'foo';
        $form['_password'] = 'bar';
        // submit the form
        $crawler = $client->submit($form);
        $crawler = $client->followRedirect();
        // is the locale de?
        $this->assertTrue($crawler->filter('a:contains("Logout")')->count() > 0, 'It\'s English');

    }

}
