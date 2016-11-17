<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Application\Repository\ImageRepository")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="`image`")
 */
class Image extends EntityAbstract
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /** @ORM\Column(type="string", length=1024) */
    protected $path;

    /** @ORM\Column(type="string", length=1024) */
    protected $url;

    /** @ORM\Column(type="integer") */
    protected $width;

    /** @ORM\Column(type="integer") */
    protected $height;

    /**
     * @ORM\ManyToOne(targetEntity="Album", inversedBy="album_id")
     * @ORM\JoinColumn(name="album_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $album_id;

    /** @ORM\Column(type="datetime") */
    protected $created;


    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->created = new \DateTime();
    }

}
