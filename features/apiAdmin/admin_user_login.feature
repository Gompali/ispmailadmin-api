@admin_login
Feature: Load user yaml file and populate database

  Background:
    Given The user fixture file "ApiAdmin.yml" is loaded
    
  Scenario:  Admin successfully logs in
    When  I send a "POST" request to "/login" with body:
    """
    {
      "username": "admin1",
      "password": "test"
    }
    """
    Then the response status code should be 200
    And the response should contain "token"


  Scenario:  Admin has wrong credentials
    When  I send a "POST" request to "/login" with body:
    """
    {
      "username": "admin1",
      "password": "test-false-password"
    }
    """
    Then the response status code should be 401
