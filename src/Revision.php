<?php

namespace Git;

class Revision extends Git
{
    /** @var string */
    private $hash;

    /** @var string */
    private $author;

    /** @var DateTime */
    private $date;

    /** @var string */
    private $message;

    /**
     * @param $directory
     * @param $hash
     */
    public function __construct($directory, $hash)
    {
        $this->setDirectory($directory);
        $this->setHash($hash);
    }

    /**
     * @param $hash
     *
     * @return $this
     */
    public function setHash($hash)
    {
        $hash   = trim($hash);
        $output = $this->exec('show ' . $hash . ' --quiet --format=medium');
        $output = Git::parseGitLogOutput($output);

        if (!$output) throw new \RuntimeException("{$hash} doesn't seem to be a valid revision.");

        $this->hash    = $output[0];
        $this->author  = $output[1];
        $this->date    = $output[2];
        $this->message = $output[3];
    }

    /**
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @return DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }



}