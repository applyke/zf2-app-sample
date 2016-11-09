<?php

namespace Application\Service;

use Application\ApplicationTraits\PaginationAwareTrait;

class PaginationService
{
    use PaginationAwareTrait;

    protected $pageLimit = 10;


    public function createPaginator($count = 0, $pageNumber, $pageLimit)
    {
        \Zend\Paginator\Paginator::setDefaultScrollingStyle('sliding');
        \Zend\View\Helper\PaginationControl::setDefaultViewPartial('application/includes/pagination.phtml');
        $paginator = new \Zend\Paginator\Paginator(new \Zend\Paginator\Adapter\NullFill($count));
        $paginator->setCurrentPageNumber($pageNumber);
        $paginator->setItemCountPerPage($pageLimit);
        $paginator->setPageRange(10); // number of buttons
        $renderer = $this->getRenderer();
        $paginator->setView($renderer);
        return $paginator;
    }
    
    public function createFilter($url)
    {
        $filter = new \Application\Service\FilterService();
        $filter->setUrl($url);
        return $filter;
    }

    public function setRenderer(\Zend\View\Renderer\PhpRenderer $phpRenderer)
    {
        $this->renderer = $phpRenderer;
        return $this;
    }

    public function getRenderer()
    {
        return $this->renderer;
    }
}