<?php
/**
 * This file is part of the Networking package.
 *
 * (c) net working AG <info@networking.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Tests\Functional\Controller;

use Tests\Lib\WebTestCase;

/**
 * Class BackendControllerTest
 * @package Tests\Functional\Controller
 * @author Yorkie Chadwick <y.chadwick@networking.ch>
 */
class BackendControllerTest extends WebTestCase
{
    public function testAdmin()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/admin', array('_locale' => 'en'));
        $this->assertTrue($client->getResponse()->isRedirect('http://localhost/admin/login'), 'redirect to login page');
        $crawler = $client->followRedirect();
        $this->assertRegExp('#admin/login_check#', $client->getResponse()->getContent(), 'its the login page');

    }

    public function testWrongLogin()
    {
        $this->restoreDatabase();
        $client = static::createClient();
        $crawler = $client->request('GET', '/admin/login', array('admin/_locale' => 'en'));
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
        $this->assertTrue($crawler->filter('div .alert:contains("Invalid credentials")')->count() == 1);
    }

    public function testLogin()
    {
        $this->restoreDatabase();
        $client = static::createClient();
        $crawler = $client->request('GET', '/admin/login', array('admin/_locale' => 'en'));
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
        $this->restoreDatabase();
        $client = static::createClient();
        $crawler = $client->request('GET', '/admin/login');

        var_dump($client->getResponse());
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
        $form[$key[0].'[locale]']->select('de_DE');
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
        $this->assertGreaterThan(0, $crawler->filter('a:contains("abmelden")')->count(), 'Abmelden is German');
//        $this->assertTrue($crawler->filter('html:contains("Benutzer")')->count() > 0, 'Benutzer is German');

    }

    public function testBackendLanguage2()
    {
        $this->restoreDatabase();
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
        $form[$key[0].'[locale]']->select('en');
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
