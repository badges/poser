<?php

use Behat\Behat\Context\Context;

/**
 * Behat context class.
 */
class FeatureContext implements Context
{
    private string $binFolder;
    private string $output;
    private int $return;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context object.
     * You can also pass arbitrary arguments to the context constructor through behat.yml.
     */
    public function __construct()
    {
        $this->binFolder = __DIR__ . '/../../bin/';
        $this->output    = '';
    }

    /**
     * @When I run :command
     */
    public function iRun($command): void
    {
        $poser        = $this->binFolder . $command;
        $this->return = -1;
        \ob_start();
        \passthru("cd {$this->binFolder};php $command", $this->return);
        $this->output = \ob_get_clean();
    }

    /**
     * @Then the same output should be like the content of :filePath
     */
    public function theSameOutputShouldBeLikeTheContentOf($filePath): void
    {
        $filePath = __DIR__ . '/../' . $filePath;
        $content  = \file_get_contents($filePath);

        $this->assertEquals($content, $this->output);
    }

    /**
     * @Then it should pass
     */
    public function itShouldPass(): void
    {
        if (0 != $this->return) {
            throw new Exception('Error executing ' . $this->return);
        }
    }

    /**
     * @Then the content of :given should be equal to :expected
     */
    public function theContentOfShouldBeEqualTo($given, $expected): void
    {
        $givenPath = $given;
        $given     = \file_get_contents($givenPath);

        $expectedPath = __DIR__ . '/../' . $expected;
        $expected     = \file_get_contents($expectedPath);
        \unlink($givenPath);

        $this->assertEquals($given, $expected);
    }

    private function assertEquals($given, $expected): void
    {
        $expected = \preg_replace('/\s+/', '', $expected);
        $given    = \preg_replace('/\s+/', '', $given);

        $perc = 0;
        \similar_text($expected, $given, $perc);

        if ($perc < 94) {
            throw new Exception('String similarity:' . $perc . '%. String expected:' . $expected . \PHP_EOL . ' given:' . $given);
        }
    }
}
