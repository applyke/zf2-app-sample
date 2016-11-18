<?php

namespace Application\ApplicationTraits;

trait AlbumAwareTrait
{
    /** @var  \Application\Service\AlbumService */
    protected $album;

    public function setAlbumService(\Application\Service\AlbumService $albumService)
    {
        $this->album = $albumService;
        return $this;
    }

    /**
     * @return \Application\Service\AlbumService
     */
    protected function getAlbumService()
    {
        return $this->album;
    }
}