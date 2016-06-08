Feature: Auto language in admin area
  In order to have the CMS in my preferred language
  As an admin user
  I need to be able to set my language in my user settings

  Scenario: Set my language from English to German
    Given I am logged in
    When I go to "/admin/cms/users/1/edit"
    And I select "de_DE" from "Locale"
    And I press "Update"
    Then the response should contain "abmelden"

