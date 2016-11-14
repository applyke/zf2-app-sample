<?php
namespace Application\Controller\Api;

use Zend\XmlRpc\Client;
use Application\Entity\Album;
use Application\Entity\Image;
class AlbumsController extends AbstractRestfulController
{

    //GET all images by Album id
    public function get($id)
    {
             $entityManager = $this->getEntityManager();
        /** @var \Application\Repository\AlbumRepository $albumRepository */
        $albumRepository = $entityManager->getRepository('\Application\Entity\Album');
        $album = $albumRepository->findOneById($id);
        if($album) {
            $imageRepository = $entityManager->getRepository('\Application\Entity\Image');
            $images = $imageRepository->findBy(array('album_id' => $id));
            if ($images) {
                foreach ($images as $image) {
                    $imageList[] = $this->prepareImageObject($image);
                }
                return $this->sendSuccessResponse($imageList);
            }
            return $this->sendErrorResponse(array('images' => 'This album doesn\'t have images'));
        }
        return $this->sendErrorResponse(array('albums' => 'Albums not found'));
    }

    //GET all albums with max 10 images for albums list
    public function getList()
    {
        $entityManager = $this->getEntityManager();
        /** @var \Application\Repository\AlbumRepository $albumRepository */
        $albumRepository = $entityManager->getRepository('\Application\Entity\Album');
        $albums = $albumRepository->findAll();
        $albumList = array();
        if ($albums) {
            foreach ($albums as $album) {
                $albumList[] = array(
                    'album' => $this->prepareAlbumObject($album),
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
            $count = count($images);
            if (count($images) > 10) {
                $start = count($images) - 10;
            } else {
                $start = 0;
            }
            for ($i = $start; $i < $count; $i++) {
                $imageList[] = array(
                    'id' => $images[$i]->getId(),
                    'path' => $images[$i]->getPath(),
                    'url' => $images[$i]->getUrl(),
                    'width' => $images[$i]->getWidth(),
                    'height' => $images[$i]->getHeight(),
                    'created' => $images[$i]->getCreated(),
                );
            }
            return $imageList;
        }
        return $this->sendErrorResponse(array('albums' => 'This album doesn\'t have images'));
    }

    protected function prepareAlbumObject(\Application\Entity\Album $album)
    {
        return array(
            'id' => $album->getId(),
            'title' => $album->getTitle(),
            'code' => $album->getCode(),
            'state' => $album->getState(),
            'created' => $album->getCreated(),
        );
    }

    protected function prepareImageObject(\Application\Entity\Image $image)
    {
        return array(
            'id' => $image->getId(),
            'path' => $image->getPath(),
            'url' => $image->getUrl(),
            'width' => $image->getWidth(),
            'height' => $image->getHeight(),
            'created' => $image->getCreated(),
        );
    }

}