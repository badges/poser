@ui
Feature: Generation of an image
  In order to create image by command line
  As a visitor
  I want to use the poser script

  Scenario: Create the image running a script
    When I run "poser license MIT blue -p /tmp/img.svg"
    Then it should pass
    And the content of "/tmp/img.svg" should be equal to "bootstrap/fixtures/license-MIT-blue.svg"