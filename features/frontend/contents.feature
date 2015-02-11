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

  Scenario: Add content
    Given I am "54d8afaedf6cbe401a00002a"