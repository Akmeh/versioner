<?php
declare(strict_types=1);

namespace Versioning\Commits;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Versioning\Models\Version;

/**
 * Class Analyzer
 * @package Versioning\Commits
 */
class Analyzer
{

    const GET_LATEST_COMMIT = "git log --pretty=format:'%Cred%h%Creset -%C(yellow)%d%Creset %s %Cgreen(%cr) %C(bold blue)<%an>%Creset' -n 100  --abbrev-commit";

    /**
     * @return array
     */
    public function getLatest(): array
    {

        $process = new Process(self::GET_LATEST_COMMIT);
        $process->run();

        // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return $this->analyze(explode("\n", $process->getOutput()));
    }

    /**
     * @param array $commits
     * @return array
     */
    private function analyze(array $commits): array
    {
        $output = [];
        foreach ($commits as $commit) {

            $user = $this->getUser($commit);

            if ($this->isARelease($commit)) {
                return $output;
            }

            $message = $this->getMessage($commit);

            if ($this->isCommitValid($message)) {
                $output[] = [
                    'user' => $user,
                    'message' => $message,
                ];
            }

        }

        return $output;
    }

    /**
     * @param $commit
     * @return string
     */
    private function getMessage(string $commit): string
    {
        preg_match_all("/\-\s(.*)\(/", $commit, $output);

        return trim(str_replace('(HEAD -> master, origin/master)', '', $output[1][0]));
    }

    /**
     * @param $commit
     * @return string
     */
    private function getUser(string $commit): string
    {
        preg_match_all("/\<(.*)\>/", $commit, $output);
        return $output[1][0];
    }

    /**
     * @param string $user
     * @return bool
     */
    private function isARelease(string $commit): bool
    {

        return strpos(trim($commit), '(tag:') === 0;
    }

    /**
     * @param string $message
     * @return bool
     */
    private function isCommitValid(string $message): bool
    {
        $message = trim($message);

        if (strpos($message, '(') === 0) {
            return false;
        }

        return true;
    }
}