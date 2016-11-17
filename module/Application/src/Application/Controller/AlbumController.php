<?php

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
        $albumService = $this->getAlbumService();

        $detailsImages = $albumService->getImagesForAlbumId($id);
        if ($detailsImages === false) {
            return $this->notFound();
        }
        return new ViewModel($detailsImages);
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
