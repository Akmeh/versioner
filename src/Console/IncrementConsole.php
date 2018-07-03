<?php
declare(strict_types=1);

namespace Versioning\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Versioning\Command\CalculateVersionCommand;
use League\Flysystem\Filesystem;

/**
 * Seed the db with job application data.
 */
class IncrementConsole extends Command
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
     * @throws InvalidArgumentException
     */
    protected function configure()
    {
        $this->setName('version:inc')
            ->setDescription('Increment the current version number')
            ->addArgument('severity', InputArgument::REQUIRED, 'major|minor|patch How important are the changes');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     * @throws InvalidArgumentException
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $command = new CalculateVersionCommand($this->filesystem);
        $response = $command->execute($input->getArgument('severity'));
        $output->writeln($response);
    }
}
