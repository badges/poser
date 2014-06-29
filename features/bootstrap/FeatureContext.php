<?php

use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

/**
 * Behat context class.
 */
class FeatureContext implements SnippetAcceptingContext
{
    /**
     * Initializes context.
     *
     * Every scenario gets its own context object.
     * You can also pass arbitrary arguments to the context constructor through behat.yml.
     */
    public function __construct()
    {
        $this->binFolder = __DIR__ . '/../../bin/';
        $this->output = '';
    }

    /**
     * @When I run :command
     */
    public function iRun($command)
    {
        $poser = $this->binFolder . $command;
        $this->return = -1;
        ob_start();
        passthru("cd {$this->binFolder};php $command", $this->return);
        $this->output = ob_get_clean();
    }

    /**
     * @Then the same output should be like the content of :filePath
     */
    public function theSameOutputShouldBeLikeTheContentOf($filePath)
    {
        $filePath = __DIR__.'/../'.$filePath;
        $content = file_get_contents($filePath);

        $this->assertEquals($content, $this->output);
    }
    /**
     * @Then it should pass
     */
    public function itShouldPass()
    {
        if (0 != $this->return) {
            throw new \Exception('Error executing '.$this->return);
        }
    }

    /**
     * @Then the content of :given should be equal to :expected
     */
    public function theContentOfShouldBeEqualTo($given, $expected)
    {
        $givenPath = $given;
        $given = file_get_contents($givenPath);

        $expectedPath = __DIR__.'/../'.$expected;
        $expected = file_get_contents($expectedPath);
        unlink($givenPath);

        $this->assertEquals($given, $expected);
    }

    private function assertEquals($given, $expected)
    {
        $expected = preg_replace('/\s+/', '', $expected);
        $given = preg_replace('/\s+/', '', $given);

        $perc = 0;
        similar_text($expected, $given, $perc);

        if ($perc < 94) {
            throw new \Exception('String similarity:'.$perc.'%. String expected:'.$expected.PHP_EOL.' given:'.$given);
        }
    }
}
