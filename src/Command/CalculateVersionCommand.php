<?php
declare(strict_types=1);

namespace Versioning\Command;

use League\Flysystem\Filesystem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Versioning\Commits\Analyzer;
use Versioning\Commits\Version;

class CalculateVersionCommand extends Command
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * CalculateVersionCommand constructor.
     * @param Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
        parent::__construct('version:generate');
    }

    public function execute(InputInterface $input, OutputInterface $output) : string
    {
        $analyzer = new Analyzer();
        $commits = $analyzer->getLatest();

        $versioner = new Version($this->filesystem);

        $newVersion = $versioner->calculateNew($commits);
        return 'New version is ' . $newVersion;

    }
}
