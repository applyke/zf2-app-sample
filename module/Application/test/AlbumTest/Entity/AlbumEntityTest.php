<?php

namespace AlbumTest\Entity;

use Application\Entity\Album as AlbumEntity;

class AlbumEntityTest extends \PHPUnit_Framework_TestCase
{
    protected $album;

    public function setUp()
    {
        $album = new AlbumEntity;
        $this->album = $album;
    }

    /**
     * @covers Application\Entity\Album::setId
     * @covers Application\Entity\Album::getId
     */
    public function testSetGetId()
    {
        $this->album->setId(1);
        $this->assertEquals(1, $this->album->getId());
    }

    /**
     * @covers Application\Entity\Album::setTitle
     * @covers Application\Entity\Album::getTitle
     */
    public function testSetGetTitle()
    {
        $this->album->setTitle('test_album');
        $this->assertEquals('test_album', $this->album->getTitle());
    }

    /**
     * @covers Application\Entity\Album::setCode
     * @covers Application\Entity\Album::getCode
     */
    public function testSetGetCode()
    {
        $this->album->setCode('testCode');
        $this->assertEquals('testCode', $this->album->getCode());
    }


    /**
     * @covers Application\Entity\Album::setStatus
     * @covers Application\Entity\Album::getStatus
     */
    public function testSetGetStatus()
    {
        $this->album->setStatus(1);
        $this->assertEquals(1, $this->album->getStatus());
    }
//    public function testInputFiltersAreSetCorrectly()
//    {
//        $album = new AlbumEntity();
//
//        $inputFilter = $album->getInputFilter();
//
//        $this->assertSame(3, $inputFilter->count());
//        $this->assertTrue($inputFilter->has('title'));
//        $this->assertTrue($inputFilter->has('code'));
//        $this->assertTrue($inputFilter->has('status'));
//    }
}
