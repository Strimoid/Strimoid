Feature: Authorization

  Scenario: Log in
    Given I am on "/login"
    When I fill in the following:
      | username | Frankie19 |
      | password | qwe123 |
    And I press "Zaloguj"
    Then I should be logged in

  Scenario: Try to log in with invalid credentials
    Given I am on "/login"
    When I fill in the following:
      | username | invalid-user |
      | password | qwe123 |
    And I press "Zaloguj"
    Then I should see "Błędna nazwa użytkownika lub hasło"

  Scenario: Register new account
    Given I am on the homepage
    And I follow "rejestracja"
    When I fill in the following:
      | .main_col username | nowicjusz |
      | password | haslo1234 |
      | email    | user@strimoid.pl |
    And I press "Zarejestruj"
    Then print last response
    Then I should see "Aby zakończyć rejestrację"
