<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkContext;
use Laracasts\Behat\Context\Services\MailTrap;
use PHPUnit_Framework_Assert as PHPUnit;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends MinkContext implements Context, SnippetAcceptingContext
{

    use MailTrap;

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
     * @Given I am :arg1
     */
    public function iAm($id)
    {
        app()->auth->onceUsingId($id);
    }

    /**
     * @Given I am logged in
     */
    public function iAmLoggedIn()
    {
        $this->iAm('54d8afaedf6cbe401a00002a');
    }

    /**
     * @Then I should be logged in
     */
    public function iShouldBeLoggedIn()
    {
        $loggedIn = app()->auth->check();
        PHPUnit::assertTrue($loggedIn);
    }

    /**
     * @Then I should be logged out
     */
    public function iShouldBeLoggedOut()
    {
        $loggedIn = app()->auth->check();
        PHPUnit::assertFalse($loggedIn);
    }

    /**
     * @When I submit form :arg1
     */
    public function iSubmitForm($name)
    {
        $this->getSession()->getPage()->find('css', $name)->submit();
    }

}
