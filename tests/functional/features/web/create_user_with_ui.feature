Feature: create user
  As an admin I want to create users

  Scenario: create user
    Given I create a user with username "username" and password "password"
    And I am authenticated with username "username" password "password"
    And I am on "/"
    When I follow "create_user"
    And I fill in "username" with "username2"
    And I fill in "password" with "password2"
    And I press "_submit"
    Then I should see "Created user with username username2"