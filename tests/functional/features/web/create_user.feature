Feature: create user
  As an admin I want to create users

  Scenario: create user
    When I create a user with username "username" and password "password"
    Then I have a user with username "username"

  Scenario: error when creating duplicated username
    Given I create a user with username "username" and password "password"
    When I create a user with username "username" and password "password"
    Then I have an error with message "User already registered"