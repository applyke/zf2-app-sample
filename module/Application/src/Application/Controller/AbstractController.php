<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Application\ApplicationTraits\DoctrineEntityManagerAwareTrait;
use Application\ApplicationTraits\PaginationAwareTrait;

use Zend\View\Resolver;

abstract class AbstractController extends AbstractActionController
{
    use DoctrineEntityManagerAwareTrait;
    use PaginationAwareTrait;


    protected $pageLimit = 10;

    protected function setLayoutData()
    {

    }

    protected function getUrl()
    {
        $controller = $this->getEvent()->getRouteMatch()->getParam('controller');
        $controller = str_replace(__NAMESPACE__ . '\\', '', $controller);

        return $this->url()->fromRoute(
            $this->getEvent()->getRouteMatch()->getMatchedRouteName(),
            array(
                'controller' => strtolower($controller),
                'action' => strtolower($this->getEvent()->getRouteMatch()->getParam('action')),
                'id' => $this->params()->fromRoute('id'),
                'id2' => $this->params()->fromRoute('id2'),
            )
        );
    }

    protected function notFound()
    {
        $this->getResponse()->setStatusCode(404);
        $this->getEvent()->stopPropagation(true);
        return $this->getEvent()->getApplication()->getEventManager()->trigger(\Zend\Mvc\MvcEvent::EVENT_DISPATCH_ERROR, $this->getEvent());
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

    public function getFilterDataFromRequest(\Application\Service\FilterService $filter)
    {
        $data = array();
        $filterData = $filter->getRawData();
        foreach ($filterData as $paramName => $value) {
            $paramValue = $this->params()->fromQuery($paramName);
            if ($paramValue || $paramValue == '0') {
                if ($filter->setFilterParam($paramName, $paramValue)) {
                    $value['db_criteria']['value'] = $paramValue;
                    $data[] = $value;
                }
            }
        }
        return $data;
    }

    public function getPageNumber($count = null)
    {
        $page = (int)$this->params()->fromRoute('page', 1);
        if (isset($_GET['p'])) {
            $page = (int)$_GET['p'];
        }
        if (!isset($page) || $page < 1) {
            $page = 1;
        }
        if (isset($count)) {
            $maxPage = max(ceil($count / $this->getPageLimit()), 1);
            if ($page > $maxPage) {
                $page = $maxPage;
            }
        }
        return (int)$page;
    }

    public function getPageLimit()
    {
        return $this->pageLimit;
    }

    public function checkPageNumber($count = 0)
    {
        $page = $this->params()->fromRoute('page');
        if ($page) {
            $page = (int)$page;
        }
        $totalPages = ceil($count / $this->getPageLimit());
        if ((isset($page) && $page < 2) || $page > $totalPages) {
            return $this->notFound();
        }
    }

    public function getPageOffset()
    {
        $limit = $this->getPageLimit();
        return $this->getPageNumber() * $limit - $limit;
    }

    public function setPageLimit($limit)
    {
        return $this->pageLimit = $limit;
    }


}