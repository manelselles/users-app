Feature: login
  As a user,
  In order to access content
  I want to login

  Scenario: Redirect to login
    When I am on "/"
    Then I should see "Login"

  Scenario: Logged In
    Given I create a user with username "username" and password "password"
    And I am authenticated with username "username" password "password"
    When I am on "/"
    Then I should see "Hello username"
