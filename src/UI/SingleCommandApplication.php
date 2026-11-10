<?php

namespace PUGX\Poser\UI;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;

/**
 * Application providing access to just one command.
 *
 * When a console application only consists of one
 * command, having to specify this command's name as first
 * argument is superfluous.
 * This class simplifies creating and using this
 * kind of applications.
 *
 * Usage:
 *
 * $cmd = new SimpleCommand();
 * $app = new SingleCommandApplication($cmd, '1.2');
 * $app->run();
 *
 * @author Stefaan Lippens <soxofaan@gmail.com>
 */
class SingleCommandApplication extends Application
{
    /**
     * Name of the single accessible command of this application.
     */
    private ?string $commandName = null;

    /**
     * Constructor to build a "single command" application, given a command.
     *
     * The application will adopt the same name as the command.
     *
     * @param Command $command The single (accessible) command for this application
     * @param string  $version The version of the application
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(Command $command, string $version = 'UNKNOWN')
    {
        $commandName = $command->getName();
        if (null === $commandName) {
            throw new \InvalidArgumentException('command cannot be null');
        }

        parent::__construct($commandName, $version);

        // Add the given command as single (accessible) command.
        $this->addCommand($command);
        $this->commandName = $commandName;

        // Override the Application's definition so that it does not
        // require a command name as first argument.
        $this->getDefinition()->setArguments();
    }

    protected function getCommandName(InputInterface $input): ?string
    {
        return $this->commandName;
    }

    /**
     * Adds a command object.
     *
     * Kept for Symfony < 7.4, where Application::add() is the public API.
     *
     * @throws \LogicException
     */
    public function add(Command $command): ?Command
    {
        return $this->addCommand($command);
    }

    /**
     * Adds a command object.
     *
     * This function overrides (public) Application::addCommand()
     * but should should only be used internally.
     * Will raise \LogicException when called
     * after the single accessible command is set up
     * (from the constructor).
     *
     * @throws \LogicException
     *
     * @psalm-suppress UndefinedMethod,PossiblyInvalidArgument parent::add() is only reached on Symfony < 7.4
     */
    public function addCommand(callable|Command $command): ?Command
    {
        // Fail if we already set up the single accessible command.
        if ($this->commandName) {
            throw new \LogicException('You should not add additional commands to a SingleCommandApplication instance.');
        }

        // Application::addCommand() exists since Symfony 7.4, Application::add() is gone in 8.0.
        if (\method_exists(Application::class, 'addCommand')) {
            return parent::addCommand($command);
        }

        return parent::add($command);
    }
}
