<?php
namespace Application\Service;

use Application\ApplicationTraits\DoctrineEntityManagerAwareTrait;
use Application\ApplicationTraits\LoggerAwareTrait;
use Application\ApplicationTraits\PaginationAwareTrait;
use Zend\Http\Response as HttpResponse;

class AlbumService
{
    use DoctrineEntityManagerAwareTrait;
    use LoggerAwareTrait;
    use PaginationAwareTrait;

    protected $paramsPlugin;
    protected $pageLimit = 10;

    public function getImagesForAlbumId($id)
    {
        $entityManager = $this->getEntityManager();
        /** @var \Application\Repository\AlbumRepository $albumRepository */
        $albumRepository = $entityManager->getRepository('\Application\Entity\Album');
        /** @var \Application\Repository\ImageRepository $imageRepository */
        $imageRepository = $entityManager->getRepository('\Application\Entity\Image');

        $album = $albumRepository->findOneById((int)$id);
        if (!$album) {
            return false;
        }
        $paginationService = $this->getPaginationService();

        $images = $imageRepository->findByWithTotalCount(array('album_id' => $album->getId()), array('id' => 'ASC'), $this->getPageLimit(), $this->getPageOffset());
        $imagesTotalCount = $imageRepository->getTotalCount();

        return array(
            'album' => $album,
            'images' => $images,
            'paginator' => $paginationService->createPaginator($imagesTotalCount, $this->getPageNumber(), $this->getPageLimit()),
        );
    }

    public function getPageNumber($count = null)
    {
        $page = (int)$this->paramsPlugin->fromRoute('page', 1);
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

    public function getPageOffset()
    {
        $limit = $this->getPageLimit();
        return $this->getPageNumber() * $limit - $limit;
    }

    public function setPageLimit($limit)
    {
        return $this->pageLimit = $limit;
    }

    public function setParamsPlugin(\Zend\Mvc\Controller\Plugin\Params $paramsPlugin)
    {
        $this->paramsPlugin = $paramsPlugin;
        return $this;
    }
}