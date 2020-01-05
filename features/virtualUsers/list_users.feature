@list_users
Feature: List all users

  Background:
    Given I use fixture file "VirtualUsers.yaml"
    Given The user fixture file "ApiAdmin.yml" is loaded
    Given The user "admin1" is authenticated with a JWT authorization header

  Scenario:  Admin list the virtual users
    When I send a "GET" request to "/users"
    Then the response status code should be 200
    And the JSON should be equal to:
    """
    [
       {
           "id": "220dafd5-ff73-442c-bcbb-33a8d6cb2012",
           "email": "test_user2@example.org",
           "quota": 1000000
       },
       {
           "id": "31ee80c4-0a2c-4392-aa16-7d78ef199628",
           "email": "test_user3@example.org",
           "quota": 2000000
       },
       {
           "id": "8ace4013-7ce2-4a5b-b398-eb6db9843b21",
           "email": "test_user1@example.org",
           "quota": 1000000
       }
    ]
    """
