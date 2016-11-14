<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace AlbumTest\Controller;

use Application\Controller\AlbumController;
use Zend\Stdlib\ArrayUtils;
use Zend\Http\Response;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Application\Entity\Album;

class AlbumControllerTest extends AbstractHttpControllerTestCase
{
    protected $controller;
    protected $request;
    protected $response;
    protected $routeMatch;
    protected $event;
    protected $entityManager;
    protected $serviceManager;

    protected $traceError = true;

    public function setUp()
    {
        $this->setApplicationConfig(
//            include '/vagrant/zf2-image-gallery/config/application.config.php'
            include '/vagrant/zf2-image-gallery/module/Application/test/TestConfig.php'
        );
        parent::setUp();
    }

    public function testTestAction()
    {
        $this->dispatch('/album');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Application');
        $this->assertControllerName('Application\Controller\Album');
        $this->assertControllerClass('AlbumController');
        $this->assertActionName('index');
        $this->assertMatchedRouteName('album/default');
    }

    public function testDetailAction()
    {
        $this->dispatch('/album/details/2');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Application');
        $this->assertControllerName('Application\Controller\Album');
        $this->assertControllerClass('AlbumController');
        $this->assertActionName('details');
        $this->assertMatchedRouteName('album/default');
    }

    public function testInvalidRouteDoesNotCrash()
    {
        $this->dispatch('/invalid/route', 'GET');
        $this->assertResponseStatusCode(404);
    }

    public function testCreateAlbum()
    {
        $this->dispatch('/album/create', 'GET');
//        $albumData = array(
//            'id'     => 123,
//            'artist' => 'The Military Wives',
//            'title'  => 'In My Dreams',
//        );
//        $album     = new Album();
//        $album->exchangeArray($albumData);
       shell_exec('./vendor/bin/doctrine-module orm:schema-tool:update --force');
       shell_exec('./vendor/bin/doctrine-module orm:schema-tool:drop table');
       shell_exec('./vendor/bin/doctrine-module data-fixture:import');
        var_dump(1);
    }


}
