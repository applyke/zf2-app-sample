<?php

namespace AlbumTest\Controller;

use ApplicationTest\Bootstrap;
use Zend\Stdlib\ArrayUtils;
use Zend\Http\Response;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class AlbumControllerTest extends AbstractHttpControllerTestCase
{
    protected $controller;
    protected $entityManager;
    protected $serviceManager;

    protected $traceError = true;

    public function setUp()
    {
        $this->setApplicationConfig(
            include __DIR__ . '/../../TestConfig.php'
        );
        parent::setUp();

        shell_exec('cd ../../../');
        shell_exec('./vendor/bin/doctrine-module orm:schema-tool:update --force');
        shell_exec('export APP_ENV=\'development\'');
        shell_exec('./vendor/bin/doctrine-module data-fixture:import');
    }

    public function testTestAction()
    {
        $this->dispatch('/album');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Application');
        $this->assertControllerName('Application\Controller\Album');
        $this->assertControllerClass('AlbumController');
        $this->assertActionName('index');
        $this->assertMatchedRouteName('album');
    }

    public function testDeleteAction()
    {
        $serviceManager = Bootstrap::getServiceManager();
        $serviceManager->get('ModuleManager')->loadModules();
        $entityManager = $serviceManager->get('doctrine.entitymanager.orm_default');
        /** @var \Application\Repository\AlbumRepository $albumRepository */
        $albumRepository = $entityManager->getRepository('\Application\Entity\Album');
        $album = $albumRepository->findOneBy(array('code' => 'one'));
        $this->dispatch('/album/delete/' . $album->getId() . '/', 'GET');
        $this->assertResponseStatusCode(302);
        $this->assertModuleName('Application');
        $this->assertControllerName('Application\Controller\Album');
        $this->assertControllerClass('AlbumController');
        $this->assertActionName('delete');
        $this->assertMatchedRouteName('album/default');
    }

    public function testInvalidRouteDoesNotCrash()
    {
        $this->dispatch('/invalid/route', 'GET');
        $this->assertResponseStatusCode(404);
    }

    public function testDetailsAction()
    {
        $serviceManager = Bootstrap::getServiceManager();
        $serviceManager->get('ModuleManager')->loadModules();
        $entityManager = $serviceManager->get('doctrine.entitymanager.orm_default');
        /** @var \Application\Repository\AlbumRepository $albumRepository */
        $albumRepository = $entityManager->getRepository('\Application\Entity\Album');
        $album = $albumRepository->findOneBy(array('code' => 'three'));
        $path = '/album/details/' . $album->getId() . '/';
        $this->dispatch($path, 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Application');
        $this->assertControllerName('Application\Controller\Album');
        $this->assertControllerClass('AlbumController');
        $this->assertActionName('details');
        $this->assertMatchedRouteName('album/default');
    }

    public function testCreateAlbum()
    {
        $albumData = array(
            'id' => '',
            'title' => 'My title',
            'code' => 'Test code'
        );
        $this->dispatch('/album/create', 'GET', $albumData);
        $this->assertModuleName('Application');
        $this->assertControllerName('Application\Controller\Album');
        $this->assertControllerClass('AlbumController');
        $this->assertMatchedRouteName('album/default');
        $this->assertResponseStatusCode(200);
        $this->assertMatchedRouteName('album/default');
    }
}
