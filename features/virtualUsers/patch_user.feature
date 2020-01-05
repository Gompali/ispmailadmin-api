@patch_user
Feature: Patch a user

  Background:
    Given I use fixture file "VirtualDomains.yaml"
    Given I use fixture file "VirtualUsers.yaml"
    Given The user fixture file "ApiAdmin.yml" is loaded
    Given The user "admin1" is authenticated with a JWT authorization header

  Scenario:  Admin updates a user and attempts to change email address
    When I send a "PATCH" request to "/user/test_user1@example.org" with body:
    """
    {
      "email": "test_user178@example.org"
    }
    """
    And print last JSON response
    Then the response status code should be 200

    Given The user "admin1" is authenticated with a JWT authorization header
    When I send a "GET" request to "/users"
    And print last JSON response
    Then the response status code should be 200
    And the JSON node '' should have 3 elements
    And the JSON node '[0]' should have 3 elements
    And the JSON node '[0].email' should exist
    And the JSON node '[0].email' should contain "test_user2@example.org"
    And the JSON node '[1]' should have 3 elements
    And the JSON node '[1].email' should exist
    And the JSON node '[1].email' should contain "test_user3@example.org"
    And the JSON node '[2]' should have 3 elements
    And the JSON node '[2].email' should exist
    And the JSON node '[2].email' should contain "test_user1@example.org"

  Scenario:  Admin updates quota
    When I send a "PATCH" request to "/user/test_user1@example.org" with body:
    """
    {
      "quota": "1"
    }
    """
    Then the response status code should be 200
    Given The user "admin1" is authenticated with a JWT authorization header
    When I send a "GET" request to "/user/test_user1@example.org"
    Then the response status code should be 200
    And the JSON should be equal to:
    """
     {
        "id": "8ace4013-7ce2-4a5b-b398-eb6db9843b21",
        "email": "test_user1@example.org",
        "quota": 1
     }
    """
  Scenario:  Admin updates a user and attempts to change id
    When I send a "PATCH" request to "/user/test_user1@example.org" with body:
    """
    {
      "id": "aeqweqweqw"
    }
    """
    And print last JSON response
    Then the response status code should be 200
    Given The user "admin1" is authenticated with a JWT authorization header
    When I send a "GET" request to "/user/test_user1@example.org"
    Then the response status code should be 200
    And the JSON should be equal to:
    """
     {
        "id": "8ace4013-7ce2-4a5b-b398-eb6db9843b21",
        "email": "test_user1@example.org",
        "quota": 1000000
     }

    """
  Scenario:  Admin updates quota and other field
    When I send a "PATCH" request to "/user/test_user1@example.org" with body:
    """
    {
      "quota": "1",
      "email": "tttt@example.org"
    }
    """
    Then the response status code should be 200
    Given The user "admin1" is authenticated with a JWT authorization header
    When I send a "GET" request to "/user/test_user1@example.org"
    Then the response status code should be 200
    And the JSON should be equal to:
    """
     {
        "id": "8ace4013-7ce2-4a5b-b398-eb6db9843b21",
        "email": "test_user1@example.org",
        "quota": 1
     }
    """