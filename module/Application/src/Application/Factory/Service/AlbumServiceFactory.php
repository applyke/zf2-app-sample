<?php

namespace Application\Factory\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Application\Service\AlbumService as Service;

class AlbumServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \Doctrine\Orm\EntityManager $entityManager */
        $entityManager = $serviceLocator->get('doctrine.entitymanager.orm_default');
        /** @var \Zend\Log\Logger $logger */
        $logger = $serviceLocator->get('logger');

        $albumService = new Service();
        $albumService->setEntityManager($entityManager);
        $paginationService = $serviceLocator->get('pagination');
        $albumService->setPaginationService($paginationService);
        $pluginManager = $serviceLocator->get('controllerpluginmanager');
        $paramsPlugin = $pluginManager->get('\Zend\Mvc\Controller\Plugin\Params');
        $albumService->setParamsPlugin($paramsPlugin);
        $albumService->setLogger($logger);
        return $albumService;
    }
}