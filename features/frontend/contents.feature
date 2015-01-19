Feature: Contents

  Scenario: Show list of contents
    Given I am on the homepage
    Then I follow "Londontown"
    Then I follow "nowe"
    Then the url should match "/g/King/new"
    And I should see "Sint exercitationem quisquam"
