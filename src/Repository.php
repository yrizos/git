<?php

namespace Git;

class Repository extends Git
{
    public function __construct($directory = null)
    {
        if (null == $directory) $directory = getcwd();

        $this->setDirectory($directory);
    }

    /**
     * @return string
     */
    public function getName()
    {
        $name = explode(DIRECTORY_SEPARATOR, $this->getDirectory());

        return array_pop($name);
    }

    /**
     * @param $hash
     *
     * @return Revision
     */
    public function getRevision($hash)
    {
        return new Revision($this->getDirectory(), $hash);
    }

    /**
     * @return array
     */
    public function getRevisions()
    {
        $command = 'rev-list --all';
        $output  = $this->exec($command);

        return array_map(function ($hash) {
            return new Revision($this->getDirectory(), $hash);
        }, $output);
    }

    /**
     * @return Revision|null
     */
    public function getLastRevision()
    {
        $command = 'rev-list --all --max-count=1';
        $output  = $this->exec($command, true);

        return new Revision($this->getDirectory(), $output);
    }

    /**
     * @return Revision|null
     */
    public function getFirstRevision()
    {
        $command = 'rev-list --reverse --all --max-count=1';
        $output  = $this->exec($command, true);

        return new Revision($this->getDirectory(), $output);
    }
}