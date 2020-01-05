@list_alias
Feature: List aliases in database - laoded from fixture

  Background:
    Given I use fixture files:
      |VirtualDomains.yaml|
      |VirtualUsers.yaml|
      |VirtualAliases.yaml|
    Given The user fixture file "ApiAdmin.yml" is loaded
    Given The user "admin1" is authenticated with a JWT authorization header

  Scenario:  List /alias
    When  I send a "GET" request to "/alias"
    Then the response status code should be 200
    And the JSON should be equal to:
    """
    [
          {
              "id": "76ae4282-8ea9-49b7-b804-7b125759ee69",
              "source": "alias1@example.org",
              "destination": "test_user2@example.org",
              "domain": "example.org"
          },
          {
              "id": "b1cc6885-d29f-49f0-8130-c5b4200a5500",
              "source": "alias2@example.org",
              "destination": "test_user2@example.org",
              "domain": "example.org"
          }
    ]
    """