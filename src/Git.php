<?php

namespace Git;

abstract class Git
{

    /** @var string */
    private $directory;

    /**
     * @param $directory
     *
     * @throws \InvalidArgumentException
     *
     * @return $this
     */
    public function setDirectory($directory)
    {
        if (!Git::isGitRepository($directory)) throw new \InvalidArgumentException("Directory doesn't appear to be a git repository");

        $this->directory = realpath($directory);

        return $this;
    }

    /**
     * @return string
     */
    public function getDirectory()
    {
        return $this->directory;
    }

    /**
     * @param string $arguments
     * @param bool   $first_only
     *
     * @throws \RuntimeException
     *
     * @return array|string
     */
    protected function exec($arguments = '', $first_only = false)
    {
        $arguments = explode(' ', $arguments);
        $arguments = array_map(function ($value) {
            $value = is_string($value) ? trim($value) : '';

            if (strpos($value, '=') !== false) {
                $value = explode('=', $value);
                $value = array_map('escapeshellarg', $value);
                $value = implode('=', $value);
            } else {
                $value = escapeshellarg($value);
            }

            return $value;
        }, $arguments);

        $arguments = array_filter($arguments, function ($value) {
            return !empty($value);
        });

        $arguments = implode(' ', $arguments);
        $command   = 'git ' . $arguments;

        $cwd = getcwd();
        chdir($this->getDirectory());

        exec($command, $output, $return);

        chdir($cwd);

        if ($return !== 0) throw new \RuntimeException('Command ' . $command . ' returned exit code: ' . $return);
        if ($first_only) $output = is_array($output) ? array_shift($output) : '';

        return $output;
    }

    /**
     * @param string $directory
     * @param array  $output
     *
     * @return array
     */
    public static function parseGitLogOutput(array $output)
    {
        $output = array_map('trim', $output);
        $output = array_filter($output, function ($item) { return !empty($item); });
        $output = array_values($output);

        if (count($output) < 4) return false;

        $hash    = explode(' ', $output[0]);
        $hash    = array_pop($hash);
        $author  = trim(str_replace('Author:', '', $output[1]));
        $date    = trim(str_replace('Date:', '', $output[2]));
        $date    = \DateTime::createFromFormat('D M j H:i:s Y O', $date);
        $message = trim($output[3]);

        return [$hash, $author, $date, $message];
    }

    /**
     * @param $directory
     *
     * @return bool
     */
    public static function isGitRepository($directory)
    {
        return is_dir($directory) ? is_dir(realpath($directory) . DIRECTORY_SEPARATOR . '.git') : false;
    }

}