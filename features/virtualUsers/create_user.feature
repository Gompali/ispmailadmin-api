@create_user
Feature: Create a new account

  Background:
    Given I use fixture file "VirtualDomains.yaml"
    Given The user fixture file "ApiAdmin.yml" is loaded
    Given The user "admin1" is authenticated with a JWT authorization header

  Scenario:  Admin creates a new user in domain example.org with empty email
    When I send a "POST" request to "/user" with body:
    """
    {
      "email": "",
      "password": "",
      "quota": 1000000
    }
    """
    Then the response status code should be 400
    And the JSON node "error_message" should exist
    And the JSON should be equal to:
    """
    {
        "error_message": {
            "user[email]": "This value should not be blank.",
            "user[password]": "This value should not be blank."
        },
        "status_code": 400
    }
    """

  Scenario:  Admin creates a new user in domain example.org with empty password
    When I send a "POST" request to "/user" with body:
    """
    {
      "email": "email@example.org",
      "password": "",
      "quota": 1000000
    }
    """
    Then the response status code should be 400
    And the JSON node "error_message" should exist
    And the JSON should be equal to:
    """
    {
      "error_message": {
        "user[password]": "This value should not be blank."
        },
      "status_code": 400
    }
    """
  Scenario:  Admin creates a new user in domain example.org with empty quota
    When I send a "POST" request to "/user" with body:
    """
    {
      "email": "email@example.org",
      "password": "prout",
      "quota": 1000000
    }
    """
    Then the response status code should be 400
    And the JSON node "error_message" should exist
    And the JSON should be equal to:
    """
      {
          "error_message": {
              "user[password]": "Your password name must be at least 12 characters long"
          },
          "status_code": 400
      }
    """
  Scenario:  Admin creates a new user in wrong domain
    When I send a "POST" request to "/user" with body:
    """
    {
      "email": "email@example.eu",
      "password": "proutproutprout",
      "quota": 1000000
    }
    """
    Then the response status code should be 400
    And the JSON node "error_message" should exist
    And the JSON should be equal to:
    """
     {
         "error_message": "The domain name does not exists in the database",
         "status_code": 400
     }
    """


  Scenario:  Admin creates a new user in domain example.org
    When I send a "POST" request to "/user" with body:
    """
    {
      "email": "email@example.org",
      "password": "proutproutprout",
      "quota": 1000000
    }
    """
    Then the response status code should be 200
    And the JSON node "error_message" should not exist
    And the JSON node "id" should exist

