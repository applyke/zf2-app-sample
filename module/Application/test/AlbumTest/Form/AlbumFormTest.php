<?php

namespace ZfcUserTest\Form;

use Application\Form\AlbumForm as Form;

class BaseTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $form = new Form();
        $elements = $form->getElements();
        $this->assertArrayHasKey('title', $elements);
        $this->assertArrayHasKey('code', $elements);
        $this->assertArrayHasKey('status', $elements);
    }
}
