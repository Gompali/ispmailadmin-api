Feature: Create a domain

  Background:
    Given I use fixture files:
      | ApiAdmin.yml |

  Scenario:
    When I send a POST request to "/domain/domain.org"
    Then the response status code should be 201
