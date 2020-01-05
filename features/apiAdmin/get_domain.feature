@get_domain
Feature: Load user yaml file and populate database, then generate JWT to post a new domain

  Background:
    Given I use fixture file "VirtualDomains.yaml"
    Given The user fixture file "ApiAdmin.yml" is loaded
    Given The user "admin1" is authenticated with a JWT authorization header

  Scenario:  List /domain
    When  I send a "GET" request to "/domain"
    Then the response status code should be 200
    And the JSON should be equal to:
    """
      [
          {
              "id": "69018878-a72d-42dc-be9f-66eb7e8bcadd",
              "name": "example.org"
          }
      ]
    """