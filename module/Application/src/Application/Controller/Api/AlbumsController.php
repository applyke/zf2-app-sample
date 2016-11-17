<?php
namespace Application\Controller\Api;

use Zend\XmlRpc\Client;
use Application\Service\PrepareObjectService;

class AlbumsController extends AbstractRestfulController
{
    //GET all images by Album id.  /api/v1/albums
    public function get($id)
    {
        $entityManager = $this->getEntityManager();
        /** @var \Application\Repository\AlbumRepository $albumRepository */
        $albumRepository = $entityManager->getRepository('\Application\Entity\Album');
        $album = $albumRepository->findOneById($id);
        if ($album) {
            $imageRepository = $entityManager->getRepository('\Application\Entity\Image');
            $images = $imageRepository->findBy(array('album_id' => $id));
            $prepareObject = new PrepareObjectService();
            if ($images) {
                foreach ($images as $image) {
                    $imageList[] = $prepareObject->prepareImageObject($image);
                }
                return $this->sendSuccessResponse($imageList);
            }
            return $this->sendErrorResponse(array('images' => 'This album doesn\'t have images'));
        }
        return $this->sendErrorResponse(array('albums' => 'Albums not found'));
    }

    //GET all albums with max 10 images for albums list.  /api/v1/albums/{:id}
    public function getList()
    {
        $entityManager = $this->getEntityManager();
        /** @var \Application\Repository\AlbumRepository $albumRepository */
        $albumRepository = $entityManager->getRepository('\Application\Entity\Album');
        $albums = $albumRepository->findAll();
        $albumList = array();
        if ($albums) {
            $prepareObject = new PrepareObjectService();
            foreach ($albums as $album) {
                $albumList[] = array(
                    'album' => $prepareObject->prepareAlbumObject($album),
                    'images' => $this->getImageListAction($album->getId())
                );
            }
            return $this->sendSuccessResponse($albumList);
        }
        return $this->sendErrorResponse(array('albums' => 'Albums not found'));
    }

    public function getImageListAction($id)
    {
        $entityManager = $this->getEntityManager();
        /** @var \Application\Repository\ImageRepository $imageRepository */
        $imageRepository = $entityManager->getRepository('\Application\Entity\Image');
        $images = $imageRepository->findBy(array('album_id' => $id));
        if ($images) {
            $imageList = array();
            $imageListTmp = array_slice($images, -10);
            foreach ($imageListTmp as $image)
                $imageList[] = array(
                    'id' => $image->getId(),
                    'path' => $image->getPath(),
                    'url' => $image->getUrl(),
                    'width' => $image->getWidth(),
                    'height' => $image->getHeight(),
                );
            return $imageList;
        }
        return $this->sendErrorResponse(array('albums' => 'This album doesn\'t have images'));
    }
}