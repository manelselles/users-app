<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Symfony2Extension\Context\KernelDictionary;

class SetupContext implements Context, SnippetAcceptingContext
{
    use KernelDictionary;
    /**
     * @BeforeScenario
     * @param BeforeScenarioScope $scope
     */
    public function cleanDB(BeforeScenarioScope $scope)
    {
        $conn = $this->getContainer()->get('database_connection');
        $conn->executeQuery('Delete from users');
    }
}