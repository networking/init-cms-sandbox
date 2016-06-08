Feature: Clear Page Cache
  In order to empty the page cache
  As an super_admin user
  I need to be able to see and use the clear cache feature

  @javascript
  Scenario: Clear Cache
    Given I am logged in
    And I should see "clear cache"
#    And I press "clear-cache"
#    Then I should see the alert "Cache was cleared"
    Then The cache should be empty