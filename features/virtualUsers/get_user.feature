@get_user
Feature: Get a user by username

  Background:
    Given I use fixture file "VirtualDomains.yaml"
    Given I use fixture file "VirtualUsers.yaml"
    Given The user fixture file "ApiAdmin.yml" is loaded
    Given The user "admin1" is authenticated with a JWT authorization header

  Scenario:  Admin creates a new user in domain example.org with empty email
    When I send a "GET" request to "/user/test_user1@example.org"
    Then the response status code should be 200
    And the json node "id" should exist
    And the json node "email" should exist
    And the json node "quota" should exist
    And the JSON should be equal to:
    """
    {
      "id": "8ace4013-7ce2-4a5b-b398-eb6db9843b21",
      "email": "test_user1@example.org",
      "quota": 1000000
    }
    """
