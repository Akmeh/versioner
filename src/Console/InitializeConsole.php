<?php
declare(strict_types=1);

namespace Versioning\Console;

use League\Flysystem\Filesystem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Versioning\Command\InitializeCommand;
use Versioning\Models\Version;

/**
 * Seed the db with job application data.
 */
class InitializeConsole extends Command
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * InitializeConsole constructor.
     * @param Filesystem $filesystem
     * @throws \Symfony\Component\Console\Exception\LogicException
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
        parent::__construct();
    }

    /**
     * @inheritDoc
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    protected function configure()
    {
        $this->setName('version:init')
            ->setDescription('Create a ' . Version::VERSION_FILE . ' file in order to keep tracking of the versions');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     * @throws \League\Flysystem\FileExistsException
     * @throws \League\Flysystem\FileNotFoundException
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $command = new InitializeCommand($this->filesystem);
        $response = $command->execute();
        $output->writeln($response);
    }
}
