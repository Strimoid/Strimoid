Feature: Contents

  Scenario: Show list of contents
    Given I am on the homepage
    Then I follow "Ritchieland"
    Then I follow "nowe"
    Then the url should match "/g/Bartoletti/new"
    And I should see "Earum perspiciatis ea alias nulla"
