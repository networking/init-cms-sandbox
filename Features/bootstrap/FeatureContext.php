<?php


use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkContext;
use Behat\Symfony2Extension\Context\KernelDictionary;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends MinkContext implements Context, SnippetAcceptingContext
{
    use KernelDictionary;
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * @Then /^I should see the alert "([^"]*)"$/
     */
    public function iShouldSeeTheAlert($text)
    {
        $this->jqueryWait();
        $this->assertElementContainsText('.alert', $text);
    }


    protected function jqueryWait($duration = 1000)
    {
        $this->getSession()->wait($duration, '(0 === jQuery.active && 0 === jQuery(\':animated\').length)');
    }

    /**
     * @Then /^The cache should be empty$/
     */
    public function theCacheisEmpty()
    {
        /** @var \Networking\InitCmsBundle\Lib\PhpCacheInterface $phpCache */
        $phpCache = $this->getContainer()->get('networking_init_cms.lib.php_cache');
        $page = $phpCache->get('en_US');
        if ( $page !== null) {
            throw new Exception(
                "Cache was not emptied"
            );
        }
    }



    /**
     * @Given /^I am logged in$/
     */
    public function iAmLoggedIn()
    {
        $this->visit('/admin/login');
        $this->fillField('_username', 'sysadmin');
        $this->fillField('_password', 'ych12Higledy');
        $this->pressButton('_submit');
    }
}
