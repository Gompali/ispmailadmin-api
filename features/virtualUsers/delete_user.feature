@delete_user
Feature: Delete a user by username

  Background:
    Given I use fixture file "VirtualDomains.yaml"
    Given I use fixture file "VirtualUsers.yaml"
    Given The user fixture file "ApiAdmin.yml" is loaded
    Given The user "admin1" is authenticated with a JWT authorization header

  Scenario:  Admin creates a new user in domain example.org with empty email
    When I send a "DELETE" request to "/user/test_user1@example.org"
    Then the response status code should be 200

    Given The user "admin1" is authenticated with a JWT authorization header
    When I send a "GET" request to "/users"
    Then the response status code should be 200
    And the JSON node '' should have 2 elements
    And the JSON node '[0]' should have 3 elements
    And the JSON node '[0].email' should exist
    And the JSON node '[0].email' should contain "test_user2@example.org"
    And the JSON node '[1]' should have 3 elements
    And the JSON node '[1].email' should exist
    And the JSON node '[1].email' should contain "test_user3@example.org"
