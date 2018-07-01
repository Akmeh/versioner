<?php
declare(strict_types=1);

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Versioning\Console\InitializeConsole;

class InitializeConsoleTest extends Codeception\TestCase\Test
{

    /**
     * @test
     */
    public function initializeConsoleShouldCreateOne()
    {
        $filesystem = Mockery::mock('League\Flysystem\Filesystem');
        $filesystem->shouldReceive('read')
            ->andThrow(\League\Flysystem\FileNotFoundException::class);

        $filesystem->shouldReceive('write')
            ->andReturn(true);

        $application = new Application();
        $application->add(new InitializeConsole($filesystem));

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
        $filesystem = Mockery::mock('League\Flysystem\Filesystem');
        $filesystem->shouldReceive('read')
            ->andReturn('0.0.1');

        $application = new Application();
        $application->add(new InitializeConsole($filesystem));

        $command = $application->find('version:init');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command'  => $command->getName(),

        ));

        $output = $commandTester->getDisplay();
        $this->assertContains('Version already created', $output);

    }
}