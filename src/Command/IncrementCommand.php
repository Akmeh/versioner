<?php
declare(strict_types=1);

namespace Versioning\Command;

use League\Flysystem\Filesystem;
use Versioning\Models\Version;

class IncrementCommand
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * IncrementCommand constructor.
     * @param Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function execute($severity) : string
    {
        try {
            $version = $this->filesystem->read(Version::VERSION_FILE);
        } catch (FileNotFoundException $e) {
            return 'We can not found any version in this project';
        }

		$explodeVersion = explode('.', $version);

        $current = $explodeVersion[Version::SEVERITY[$severity]] + 1;

        $explodeVersion[Version::SEVERITY[$severity]] = $current;
        $newVersion = implode('.', $explodeVersion);
        $this->filesystem->update(Version::VERSION_FILE, $newVersion);

        return 'New version is ' . $newVersion;

    }
}
