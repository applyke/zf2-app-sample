<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\View\Model\ViewModel;
use Zend\View\Resolver;

class AlbumController extends AbstractController
{

    public function indexAction()
    {
        $entityManager = $this->getEntityManager();
        /** @var \Application\Repository\AlbumRepository $albumRepository */
        $albumRepository = $entityManager->getRepository('\Application\Entity\Album');

        $all_albums = $albumRepository->findAll();
        return new ViewModel(array(
            'albums' => $all_albums,
        ));
    }

    public function createAction()
    {
        $id = $this->params()->fromRoute('id');
        $entityManager = $this->getEntityManager();
        /** @var \Application\Repository\AlbumRepository $albumRepository */
        $albumRepository = $entityManager->getRepository('\Application\Entity\Album');
        $album = new \Application\Entity\Album();

        if (isset($id)) {
            $album = $albumRepository->findOneById($id);
            if (!$album) {
                return $this->notFound();
            }
        }
        $albumForm = new \Application\Form\AlbumForm('album', array(
            'album' => $album,
            'backBtnUrl' => $this->url()->fromRoute(
                'home'
            ),
        ));

        $albumForm->setEntityManager($entityManager)
            ->bind($album);
        if ($this->getRequest()->isPost()) {
            $albumForm->setData($this->getRequest()->getPost());
            $albumForm->remove('send');
            if ($albumForm->isValid()) {
                $entityManager->persist($album);
                $entityManager->flush();
                $this->flashMessenger()->addSuccessMessage('Saved');
                return $this->redirect()->toRoute('home');
            }
        }
        return new ViewModel(array(
            'albumForm' => $albumForm,
        ));
    }

    public function detailsAction()
    {
        $id = $this->params()->fromRoute('id');
        $entityManager = $this->getEntityManager();
        /** @var \Application\Repository\AlbumRepository $albumRepository */
        $albumRepository = $entityManager->getRepository('\Application\Entity\Album');
        /** @var \Application\Repository\ImageRepository $imageRepository */
        $imageRepository = $entityManager->getRepository('\Application\Entity\Image');

        $album = $albumRepository->findOneById((int)$id);
        if (!$album) {
            return $this->notFound();
        }
        $paginationService = $this->getPaginationService();
        $filter = $paginationService->createFilter($this->getUrl());
        $images = $imageRepository->findByWithTotalCount(array('album_id' => $album->getId()), array('id' => 'ASC'), $this->getPageLimit(), $this->getPageOffset(), $this->getFilterDataFromRequest($filter));
        $imagesTotalCount = $imageRepository->getTotalCount();
        return new ViewModel(
            array(
                'album' => $album,
                'images' => $images,
                'paginator' => $paginationService->createPaginator($imagesTotalCount, $this->getPageNumber(), $this->getPageLimit()),
                'filter' => $filter,
            )
        );
    }

    public function deleteAction()
    {
        $id = $this->params()->fromRoute('id');
        $entityManager = $this->getEntityManager();
        /** @var \Application\Repository\AlbumRepository $albumRepository */
        $albumRepository = $entityManager->getRepository('\Application\Entity\Album');
        $album = $albumRepository->findOneById((int)$id);
        if (!$album) {
            return $this->notFound();
        }
        return $this->removeEntity($album, array(
            'controller' => 'album'
        ));
    }
}
