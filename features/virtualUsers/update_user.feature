@update_user
Feature: Delete a user by username

  Background:
    Given I use fixture file "VirtualDomains.yaml"
    Given I use fixture file "VirtualUsers.yaml"
    Given The user fixture file "ApiAdmin.yml" is loaded
    Given The user "admin1" is authenticated with a JWT authorization header

  Scenario:  Admin updates a user and attempts to change email address
    When I send a "PUT" request to "/user/test_user1@example.org" with body:
    """
    {
      "email": "test_user8@example.org",
      "password": "pwetpwetpwet",
      "quota": "125000000"
    }
    """
    And print last JSON response
    Then the response status code should be 400
    And the JSON should be equal to:
    """
     {
         "error_message": {
             "update_user": "This form should not contain extra fields."
         },
         "status_code": 400
     }
    """


  Scenario:  Admin updates a user
    When I send a "PUT" request to "/user/test_user1@example.org" with body:
    """
    {
      "password": "pwetpwetpwet",
      "quota": "125000000"
    }
    """
    Then the response status code should be 201

    Given The user "admin1" is authenticated with a JWT authorization header
    When I send a "GET" request to "/users"
    And print last JSON response

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
            "quota": 125000000
        }
    ]
    """