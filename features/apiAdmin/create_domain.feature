@post_domain
Feature: Load user yaml file and populate database, then generate JWT to post a new domain

  Background:
    Given The user fixture file "ApiAdmin.yml" is loaded
    Given The user "admin1" is authenticated with a JWT authorization header

  Scenario:  Admin successfully logged in and get JWT into header for next request to post /domain
    When  I send a "POST" request to "/domain" with body:
    """
    {
      "name": "example.org"
    }
    """
    Then the response status code should be 201
