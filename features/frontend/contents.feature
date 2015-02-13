Feature: Contents

  Scenario: Show list of contents
    Given I am on "/g/rohan/new"
    Then I should see "Aut eius laudantium nam"

  Scenario: Show content info and comments
    Given I am on "/g/rohan/new"
    When I follow "3 komentarze"
    Then the url should match "/c/04a24c/aut-eius-laudantium"
    And I should see "Aut eius laudantium"
    And I should see "Pariatur mollitia qu"
    And I should see "Dolorum quasi explicabo"

  Scenario: Add new link
    Given I am logged in
    And I am on the homepage
    When I follow "Dodaj link"
    And I fill in the following:
      | groupname   | satterfield         |
      | url         | https://strimoid.pl |
      | title       | Fajna treść         |
      | description | Przykładowy opis    |
    And I uncheck "Miniaturka"
    And I press "Dodaj treść"
    Then the url should match "/c/"
    And I should see "Fajna treść"
    And I should see "Przykładowy opis"
