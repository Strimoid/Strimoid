Feature: Settings

  Scenario: Display page with user settings
    Given I am "54d8afaedf6cbe401a00002a"
    And I am on "/settings"
    Then I should see "Rok urodzenia"