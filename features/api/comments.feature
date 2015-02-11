Feature: Comments

  Scenario: Returning a collection of entries
    When I request "GET /api/v1/comments"
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