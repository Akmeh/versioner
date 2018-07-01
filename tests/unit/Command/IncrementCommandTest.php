<?php
declare(strict_types=1);

use Versioning\Command\IncrementCommand;

class IncrementCommandTest extends Codeception\TestCase\Test
{

    /**
     * @test
     * @dataProvider incrementProvider
     * @param $newVersion
     * @param $severity
     */
    public function incrementCommnadShouldWork($newVersion, $severity)
    {
        $expected = 'New version is ' . $newVersion;
        $filesystem = Mockery::mock('League\Flysystem\Filesystem');
        $filesystem->shouldReceive('read')
            ->andReturn('0.0.1');

        $filesystem->shouldReceive('write')
            ->andReturn(true);

        $console = new IncrementCommand($filesystem);
        $this->assertContains($expected, $console->execute($severity));
    }

    public function incrementProvider()
    {
        return [
            ['0.0.2', 'patch'],
            ['0.1.1', 'minor'],
            ['1.0.1', 'major'],
        ];
    }
}