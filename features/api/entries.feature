Feature: Entries

  Scenario: Returning a collection of entries
    When I request "GET /api/v1/entries"
    Then I get a "200" response
    And scope into the first "data" property
    And the properties exist:
      """
      _id
      created_at
      user
      group
      uv
      dv
      text
      replies
      """
    And the "uv" property is an integer
    And the "dv" property is an integer
    And the "replies" property is an array

  Scenario: Finding a specific entry
    When I request "GET /api/v1/entries/836aa5"
    Then I get a "200" response
    And the properties exist:
      """
      _id
      created_at
      user
      group
      uv
      dv
      text
      replies
      """
    And the "uv" property is an integer
    And the "dv" property is an integer
    And the "replies" property is an array

  Scenario: Finding a non-existing entry
    When I request "GET /api/v1/entries/nosuchid"
    Then I get a "404" response
