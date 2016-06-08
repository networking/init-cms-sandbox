Feature: Authentication
  In order to use the CMS
  As an admin user
  I need to be able to login and logout

  Scenario: Login with correct password
    Given I am on "/admin/login"
    When I fill in "_username" with "sysadmin"
    And I fill in "_password" with "ych12Higledy"
    And I press "_submit"
    Then I should be on "/admin/dashboard"

  Scenario: Login with incorrect password
    Given I am on "/admin/login"
    When I fill in "_username" with "sysadmin"
    And I fill in "_password" with "ychd12Higledy"
    And I press "_submit"
    Then I should see "Bad credentials"

