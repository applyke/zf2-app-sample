<?php

namespace Application\Fixture;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Application\Entity\Album;
use Application\Entity\Image;
use Application\ApplicationTraits\DoctrineEntityManagerAwareTrait;

class LoadGalleryData implements FixtureInterface
{
    //   ./vendor/bin/doctrine-module data-fixture:import
    use DoctrineEntityManagerAwareTrait;

    public function load(ObjectManager $manager)
    {
        $albumTitles = array(
            'Picture Perfect Memories',
            'Only Yesterday',
            'Ordinary People',
            'Day by Day',
            'The Good Old Days'
        );
        $albumCodes = array(
            'one', 'two', 'tree', 'four', 'five'
        );
        for ($i = 0; $i < 5; $i++) {
            $album = new Album();
            $album->setTitle($albumTitles[$i]);
            $album->setCode($albumCodes[$i]);
            $manager->persist($album);
            $manager->flush();
        }

        $albumRepository = $manager->getRepository('\Application\Entity\Album');
        $albums = $albumRepository->findAll();
        $pathToImage = '/public/images/2d.jpg';
        $counter = 5;
        foreach ($albums as $album) {
            for ($i = 0; $i < $counter; $i++) {
                $image = new Image();
                $image->setAlbumId($album);
                $image->setPath($pathToImage);
                $image->setUrl('/');
                $image->setWidth('320');
                $image->setHeight('242');
                $manager->persist($image);
            }
            $counter += 20;
        }
        $manager->flush();
    }

}