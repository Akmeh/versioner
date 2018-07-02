<?php
declare(strict_types=1);

namespace Versioning\Commits;

use League\Flysystem\FileNotFoundException;
use League\Flysystem\Filesystem;

/**
 * Class Version
 * @package Versioning\Commits
 */
class Version
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

    /**
     * @param array $commits
     * @return string
     * @throws FileNotFoundException
     */
    public function calculateNew(array $commits): string
    {
        $version = $this->getCurrent();
        return $this->calculateNext($version, $commits);

    }

    /**
     * @return string
     */
    private function getCurrent(): string
    {
        try {
            $version = $this->filesystem->read(\Versioning\Models\Version::VERSION_FILE);
        } catch (FileNotFoundException $e) {
            $version = \Versioning\Models\Version::FIRST_VERSION;
            $this->filesystem->put(
                \Versioning\Models\Version::VERSION_FILE, $version
            );
        }

        return $version;

    }

    /**
     * @param string $version
     * @param array $commits
     * @return string
     * @throws FileNotFoundException
     */
    private function calculateNext(string $version, array $commits): string
    {
        $explodeVersion = explode('.', $version);

        foreach ($commits as $commit) {
            $severity = $this->severityChange($commit['message']);
        }

        ++$explodeVersion[\Versioning\Models\Version::SEVERITY[$severity]];

        $newVersion = implode('.', $explodeVersion);
        $this->filesystem->update(\Versioning\Models\Version::VERSION_FILE, $newVersion);

        return $newVersion;
    }

    /**
     * @param string $commit
     * @return string
     */
    private function severityChange(string $commit) : string
    {

        foreach (\Versioning\Models\Version::COMMIT_STRUCTURE as $key => $chain) {
            if (strpos(strtolower($commit), $chain) === 0) {
                $severity = $key;
            }
        }
        return $severity ?? 'patch';
    }


}