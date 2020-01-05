@delete_domain
Feature: Delete a domain

  Background:
    Given I use fixture file "VirtualDomains.yaml"
    Given The user fixture file "ApiAdmin.yml" is loaded
    Given The user "admin1" is authenticated with a JWT authorization header

  Scenario:  Admin deletes example.com domain
    When I send a "DELETE" request to "/domain/example.org"
    Then the response status code should be 200

    Given The user "admin1" is authenticated with a JWT authorization header
    And I send a "GET" request to "/domain"
    Then the response status code should be 200
    And the JSON should be equal to:
    """
     [
         {
             "id": "fe2907b7-fede-4098-a606-2e10a211279d",
             "name": "example.com"
         }
     ]
    """

    Given The user "admin1" is authenticated with a JWT authorization header
    And I send a "DELETE" request to "/domain/example.com"
    Then the response status code should be 200

    Given The user "admin1" is authenticated with a JWT authorization header
    And I send a "GET" request to "/domain"
    Then the response status code should be 200
    And the JSON should be equal to:
    """
    []
    """