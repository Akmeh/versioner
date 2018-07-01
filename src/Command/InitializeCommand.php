<?php
declare(strict_types=1);

namespace Versioning\Command;

use League\Flysystem\FileNotFoundException;
use League\Flysystem\Filesystem;
use Versioning\Models\Version;

class InitializeCommand
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * InitializeCommand constructor.
     * @param Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {

        $this->filesystem = $filesystem;
    }

    /**
     * @return string
     * @throws \League\Flysystem\FileExistsException
     * @throws
     */
    public function execute()
    {
        try {
            $this->filesystem->read(Version::VERSION_FILE);
            return 'Version already created';
        } catch (FileNotFoundException $e) {
            $this->filesystem->write(Version::VERSION_FILE, Version::FIRST_VERSION);
            return 'Version ' . Version::FIRST_VERSION . ' created';
        }
    }
}