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

    public function testGetRevisionFromTo()
    {

        $revs = $this->repo->getRevisions('c5d18897f7aa9fec4f150b229ffd5171ca709631', 'a0c438b402d487e5c508ac781773a78c4c936dbb');
        $revs = array_map(function ($item) {
            return $item->getHash();
        }, $revs);

        $actual = [
            'a0c438b402d487e5c508ac781773a78c4c936dbb',
            '276fb9e07d77351d659a8d347e42ff2bc81fbc5b',
            '27ac9634d1765475f54d339e870ff519b3aa7099'
        ];

        $this->assertEquals($actual, $revs);
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