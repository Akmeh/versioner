<?php
declare(strict_types=1);

namespace Versioning\Commits;

use League\Flysystem\FileNotFoundException;
use League\Flysystem\Filesystem;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

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
            $version = $this->getFirstVersion();
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
        $severity = 'patch';
        foreach ($commits as $commit) {
            $possibleSeverity = $this->severityChange($commit['message']);
            if ($possibleSeverity !== 'patch') {
                if ($possibleSeverity === 'major') {
                    $severity = 'major';
                    break;
                } else {
                    $severity = 'minor';
                }
            }
        }

        $newVersion = $this->calculateNewBaseOnSeverity($severity, $version);
        $this->filesystem->update(\Versioning\Models\Version::VERSION_FILE, $newVersion);
        return $newVersion;
    }

    /**
     * @param string $commit
     * @return string
     */
    private function severityChange(string $commit): string
    {
        $severity = 'patch';

        echo "\n" . $commit . "\n";

        foreach (\Versioning\Models\Version::COMMIT_STRUCTURE as $key => $chain) {

            if (strpos(strtolower($commit), $key) === 0 && $chain !== 'patch') {
                if ($chain === 'major') {
                    return 'major';
                } else {
                    $severity = 'minor';
                }
            }
        }
        return $severity;
    }

    /**
     * @param string $severity
     * @param string $version
     * @return string
     */
    private function calculateNewBaseOnSeverity(string $severity, string $version): string
    {
        $explodeVersion = explode('.', $version);

        $minor = \Versioning\Models\Version::SEVERITY['minor'];
        $major = \Versioning\Models\Version::SEVERITY['major'];
        $patch = \Versioning\Models\Version::SEVERITY['patch'];

        if ($severity === 'major') {
            ++$explodeVersion[$major];
            $explodeVersion[$minor] = '0';
            $explodeVersion[$patch] = '0';
        } elseif ($severity === 'minor') {
            ++$explodeVersion[$minor];
            $explodeVersion[$patch] = '0';
        } else {
            ++$explodeVersion[$patch];
        }

        return implode('.', $explodeVersion);
    }


    /**
     * @return string
     */
    private function getFirstVersion() : string
    {
        $version = \Versioning\Models\Version::FIRST_VERSION;
        $process = new Process('git describe --abbrev=0 --tags');
        $process->run();

        // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
        $output = $process->getOutput();
        return  $output === '' ? $version : $output;
    }


}

