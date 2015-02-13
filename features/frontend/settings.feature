Feature: Settings

  Scenario: Display page with user settings
    Given I am logged in
    And I am on "/settings"
    Then I should see "Rok urodzenia"
