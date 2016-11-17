<?php

namespace Application\ApplicationTraits;

trait AlbumAwareTrait
{
    /** @var  \Application\Service\AlbumService */
    protected $albumService;

    public function setAlbumService(\Application\Service\AlbumService $albumService)
    {
        $this->albumService = $albumService;
        return $this;
    }

    /**
     * @return \Application\Service\AlbumService
     */
    protected function getAlbumService()
    {
        return $this->albumService;
    }

}