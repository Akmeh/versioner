<?php
declare(strict_types=1);

use Versioning\Command\InitializeCommand;

class InitializeCommandTest extends Codeception\TestCase\Test
{

    /**
     * @test
     */
    public function initializeCommandShouldCreateOne()
    {
        $filesystem = Mockery::mock('League\Flysystem\Filesystem');
        $filesystem->shouldReceive('read')
            ->andThrow(\League\Flysystem\FileNotFoundException::class);

        $filesystem->shouldReceive('write')
            ->andReturn(true);

        $console = new InitializeCommand($filesystem);
        $this->assertContains('Version 0.0.1 created', $console->execute());
    }

    /**
     * @test
     */
    public function tryToInitializateVersionWithApreviousVersionCreated()
    {
        $filesystem = Mockery::mock('League\Flysystem\Filesystem');
        $filesystem->shouldReceive('read')
            ->andReturn('0.0.1');

        $console = new InitializeCommand($filesystem);
        $this->assertContains('Version already created', $console->execute());

    }
}