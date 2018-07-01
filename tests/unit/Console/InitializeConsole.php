<?php
declare(strict_types=1);

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Versioning\Command\InitializeCommand;

class InitializeConsoleTest extends Codeception\TestCase\Test
{

    /**
     * @test
     */
    public function initializeCommandShouldCreateOne()
    {
        $initializeCommnad = Mockery::mock(InitializeCommand::class);
        $initializeCommnad->shouldReceive('execute')
            ->andReturn('Version 0.0.1 created');

        $application = new Application();
        $application->add($initializeCommnad);

        $command = $application->find('version:init');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command'  => $command->getName(),
        ));

        $output = $commandTester->getDisplay();
        $this->assertContains('Version 0.0.1 created', $output);
    }

    /**
     * @test
     */
    public function tryToInitializateVersionWithApreviousVersionCreated()
    {
        $initializeCommnad = Mockery::mock(InitializeCommand::class);
        $initializeCommnad->shouldReceive('execute')
            ->andReturn('Version already created');

        $application = new Application();
        $application->add($initializeCommnad);

        $command = $application->find('version:init');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command'  => $command->getName(),
        ));

        $output = $commandTester->getDisplay();
        $this->assertContains('Version already created', $output);

    }
}
