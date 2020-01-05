@create_alias
Feature: Create an alias - loaded from fixture

  Background:
    Given I use fixture files:
    |VirtualDomains.yaml|
    |VirtualUsers.yaml|
    |VirtualAliases.yaml|
    Given The user fixture file "ApiAdmin.yml" is loaded
    Given The user "admin1" is authenticated with a JWT authorization header

  Scenario:  Create /alias with wrong destination
    When  I send a "POST" request to "/alias" with body:
    """
    {
      "source": "toto@example.org",
      "destination": "test@example.org"
    }
    """
    Then the response status code should be 400
    And the JSON should be equal to:
    """
    {
      "error_message": "Destination of alias does not exist",
      "status_code": 400
    }
    """

  Scenario:  Create alias
    When  I send a "POST" request to "/alias" with body:
    """
    {
      "source": "nouvel-alias-toto@example.org",
      "destination": "test_user2@example.org"
    }
    """
    And print last JSON response
    Then the response status code should be 201

    Given The user "admin1" is authenticated with a JWT authorization header
    When  I send a "GET" request to "/alias"
    Then the response status code should be 200
    And print last JSON response
    And the JSON node '' should have 3 elements

