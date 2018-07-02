<?php
declare(strict_types=1);

use Codeception\TestCase\Test;

/**
 * Class CommitAnalyzerTest
 */
class VersionTest extends Test
{

    /**
     * @test
     */
    public function getNewVersionFromAnNonExistentVersionFile()
    {

        $filesystem = Mockery::mock(\League\Flysystem\Filesystem::class);

        $filesystem->shouldReceive('read')
            ->andThrow(\League\Flysystem\FileNotFoundException::class);

        $filesystem->shouldReceive('put');
        $filesystem->shouldReceive('update');

        $analyzer = new \Versioning\Commits\Analyzer();
        $commits = $analyzer->getLatest();

        $versioner = new \Versioning\Commits\Version($filesystem);
        $newVersion = $versioner->calculateNew($commits);

        $this->assertEquals('0.0.1', $newVersion);
    }
}
