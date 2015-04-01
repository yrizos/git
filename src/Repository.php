<?php

namespace Git;

class Repository extends Git
{

    /**
     * @param string|null $directory
     */
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
     * @param string|null $from
     * @param string|null $to
     *
     * @throws \RuntimeException
     *
     * @return array
     */
    public function getRevisions($from = null, $to = null)
    {
        $command = 'rev-list --all';
        $output  = $this->exec($command);

        if (null !== $from) {
            $search = array_search($from, $output);
            if (false === $search) throw new \RuntimeException('Revision ' . $from . ' not found');

            $output = array_slice($output, 0, $search);
        }

        if (null !== $to) {
            $search = array_search($to, $output);

            if (false === $search) throw new \RuntimeException('Revision ' . $to . ' not found');

            $output = array_slice($output, $search);
        }

        return array_map(function ($hash) {
            return new Revision($this->getDirectory(), $hash);
        }, $output);
    }

    /**
     * @return Revision|null
     */
    public function getLastRevision()
    {
        $command = 'rev-list --all';
        $output  = $this->exec($command, true);

        return new Revision($this->getDirectory(), $output);
    }

    /**
     * @return Revision|null
     */
    public function getFirstRevision()
    {
        $command = 'rev-list --reverse --all';
        $output  = $this->exec($command, true);

        return new Revision($this->getDirectory(), $output);
    }
}