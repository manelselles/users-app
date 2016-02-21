Feature: api
  As an api consumer,
  I want to get a token
  so I can access to the api

  Scenario: Can not access api without api token
    Given I prepare a GET request to "/api"
    When I send the request
    Then the response status code should be "401" with body:
      """
        {"code":401,"message":"Invalid credentials"}
      """

  Scenario: Can not access api with invalid credentials
    Given I generate token with username "bad_username" password "bad_password"
    And I prepare a GET request with token to "/api"
    When I send the request
    Then the response status code should be "401" with body:
      """
        {"code":401,"message":"Invalid credentials"}
      """

  Scenario: Successfully generating token and accessing api
    Given I create a user with username "username" and password "password"
    And I generate token with username "username" password "password"
    And I prepare a GET request with token to "/api"
    When I send the request
    Then the response status code should be "200" with body:
      """
        [{"username":"username","roles":["ROLE_USER"]}]
      """
