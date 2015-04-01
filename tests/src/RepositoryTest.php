<?php

namespace GitTests;

use Git\Repository;

class RepositoryTest extends \PHPUnit_Framework_TestCase
{

    private $dir;

    /** @var Repository */
    private $repo;

    public function setUp()
    {
        $this->dir  = __DIR__ . '/../../';
        $this->repo = new Repository($this->dir);
    }

    public function testConstructor()
    {
        $this->assertEquals('git', $this->repo->getName());
        $this->assertEquals(realpath($this->dir), $this->repo->getDirectory());
    }

    public function testConstructorException()
    {
        $this->setExpectedException('InvalidArgumentException');

        $repo = new Repository('.');
    }

    public function testGetRevision()
    {
        $rev = $this->repo->getRevision('56c22552921');

        $this->assertInstanceOf('Git\Revision', $rev);
        $this->assertEquals($this->repo->getDirectory(), $rev->getDirectory());
        $this->assertEquals('56c22552921db505d73f06cbfa524f739880a28b', $rev->getHash());
        $this->assertEquals('yrizos <yrizos@gmail.com>', $rev->getAuthor());
        $this->assertEquals('Wed Apr 1 11:29:30 2015', $rev->getDate()->format('D M j H:i:s Y'));
    }

    public function testGetFirstLastRevision()
    {
        $revisions = $this->repo->getRevisions();
        $first     = $this->repo->getFirstRevision();
        $last      = $this->repo->getLastRevision();

        $this->assertEquals(reset($revisions), $last);
        $this->assertEquals(end($revisions), $first);
    }

}