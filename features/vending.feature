@all
Feature: Vending machine
  In order to save costs
  As a boss
  I want to assure the vending machine works fine

  - Rules
    A vending machine can store 20 items per product at most

  Background:
    Given a vending machine
    And the vending machine can store 20 items per product at most
    And the vending machine comes with these products out of the box
      | count | product |
      | 10    | "snaks" |
      | 5     | "chips" |
    And the vending machine motd is
      """
        Have a good day
          Your lovely boss
      """

  @dealer
  Scenario: The product dealer replenish the machine
    When the product dealer replenish 10 "coca-cola"
    Then there are 10 "coca-cola" left in the vending machine

  @dealer
  Scenario: The product dealer fully replenish the machine
    When the product dealer replenish 100 "coca-cola"
    Then there are 20 "coca-cola" left in the vending machine

  @employee
  Scenario: An employee takes one product
    Given the vending machine contains 5 "cookie"
    When an employee picks up 1 "cookie"
    Then the employee receives 1 "cookie"
    And there are 4 "cookie" left in the vending machine

  @employee
  Scenario: An employee takes 5 products
    Given the vending machine contains 5 "peach juice"
    When an employee picks up 5 "peach juice"
    Then the employee receives 5 "peach juice"
    And there are 0 "peach juice" left in the vending machine

  @employee
  Scenario Outline: An employee takes products
    Given the vending machine contains <available> <product>
    When an employee picks up <picked-up> <product>
    Then the employee receives <received> <product>
    And there are <left> <product> left in the vending machine

    Examples:
      | product       | available | picked-up | received | left |
      | "cookie"      | 5         | 1         | 1        | 4    |
      | "peach juice" | 7         | 7         | 7        | 0    |
      | "coca-cola"   | 13        | 15        | 13       | 0    |
      | "palitos"     | 0         | 2         | 0        | 0    |

  @employee
  @anomalies
  Scenario: An employee try to pick up a non-existent product
    When an employee picks up 1 "beer"
    Then the employee receives 0 "beer"
    And an error message is displayed
    And there are 0 "beer" left in the vending machine
