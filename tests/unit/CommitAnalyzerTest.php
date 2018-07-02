<?php
declare(strict_types=1);


use Codeception\TestCase\Test;

/**
 * Class CommitAnalyzerTest
 */
class CommitAnalyzerTest extends Test
{

    /**
     * @test
     */
    public function getLatestCommit()
    {
        $analyzer = new \Versioning\Commits\Analyzer();
        $commits = $analyzer->getLatest();
        $this->assertArrayHasKey('user', $commits[0]);
    }
}
