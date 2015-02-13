Feature: Groups

  Scenario: Create new group
    Given I am logged in
    And I am on "/groups/list"
    When I follow "Załóż nową grupę"
    And I fill in the following:
      | urlname     | Kaczka     |
      | groupname   | Kaczorland |
      | description | Grupa o kaczkach |
    And I press "Stwórz grupę"
    Then I should be on "/g/Kaczka"
    And I should see "została utworzona"
    And I should see "Kaczorland"
