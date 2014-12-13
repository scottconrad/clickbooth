Feature: Homepage works
  In order for us to ensure that the site works
  As a User
  I need to visit revcontent.com and the site displays our text

 Scenario: Visit Homepage
   Given I am not logged in
   When I navigate to the homepage
   Then I should see the copyright notice