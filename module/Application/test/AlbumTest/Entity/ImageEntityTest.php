<?php

namespace AlbumTest\Entity;

use Application\Entity\Image as Entity;

class ImageEntityTest extends \PHPUnit_Framework_TestCase
{
    protected $image;

    public function setUp()
    {
        $image = new Entity;
        $this->image = $image;
    }

    /**
     * @covers Application\Entity\Image::setId
     * @covers Application\Entity\Image::getId
     */
    public function testSetGetId()
    {
        $this->image->setId(1);
        $this->assertEquals(1, $this->image->getId());
    }

    /**
     * @covers Application\Entity\Image::setPath
     * @covers Application\Entity\Image::getPath
     */
    public function testSetGetPath()
    {
        $this->image->setPath('/test/to/path');
        $this->assertEquals('/test/to/path', $this->image->getPath());
    }

    /**
     * @covers Application\Entity\Image::setUrl
     * @covers Application\Entity\Image::getUrl
     */
    public function testSetGetUrl()
    {
        $this->image->setUrl('https://test/url');
        $this->assertEquals('https://test/url', $this->image->getUrl());
    }


    /**
     * @covers Application\Entity\Image::setWidth
     * @covers Application\Entity\Image::getWidth
     */
    public function testSetGetWidth()
    {
        $this->image->setWidth('200');
        $this->assertEquals('200', $this->image->getWidth());
    }
    /**
     * @covers Application\Entity\Image::setHeight
     * @covers Application\Entity\Image::geHeight
     */
    public function testSetGetHeight()
    {
        $this->image->setHeight('300');
        $this->assertEquals('300', $this->image->getHeight());
    }
}
