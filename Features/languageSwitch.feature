Feature: Language Switch
  In order to see the pages in different languages
  As a frontend user
  I need to be able to click a link to change the page language

  @mink:goutte
  Scenario: Switch the homepage from English to German and Back
    Given I am on the homepage
    And I should see "The locale of this page is en_US" in the ".jumbotron" element
    When I follow "Deutsch"
    Then the response should contain "The locale of this page is de_CH"
    And the response status code should be 200
    And I should see "Deutsch" in the "li.active" element
    And I should not see "English" in the "li.active" element
    When I follow "English"
    Then the response should contain "The locale of this page is en_US"
    And the response status code should be 200
    And I should see "English" in the "li.active" element
    And I should not see "Deutsch" in the "li.active" element