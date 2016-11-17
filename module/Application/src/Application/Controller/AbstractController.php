<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Application\ApplicationTraits\DoctrineEntityManagerAwareTrait;
use Application\ApplicationTraits\PaginationAwareTrait;
use Application\ApplicationTraits\AlbumAwareTrait;

use Zend\View\Resolver;

abstract class AbstractController extends AbstractActionController
{
    use DoctrineEntityManagerAwareTrait;
    use PaginationAwareTrait;
    use AlbumAwareTrait;

   
    protected function setLayoutData()
    {
    }

    protected function removeEntity($entity = null, $params = array(), $route = 'album')
    {
        if (is_object($entity)) {
            $entityManager = $this->getEntityManager();
            try {
                $entityManager->remove($entity);
                $entityManager->flush();
                $this->flashMessenger()->addSuccessMessage('Entry removed.');
            } catch (\Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException $e) {
                $this->flashMessenger()->addErrorMessage('Entry cant be removed');
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage('Delete Error');
            }
        } else {
            $this->flashMessenger()->addInfoMessage('Entry not found');
        }
        return $this->redirect()->toRoute($route, $params);
    }

    protected function notFound()
    {
        $this->getResponse()->setStatusCode(404);
        $this->getEvent()->stopPropagation(true);
        return $this->getEvent()->getApplication()->getEventManager()->trigger(\Zend\Mvc\MvcEvent::EVENT_DISPATCH_ERROR, $this->getEvent());
    }
   
}