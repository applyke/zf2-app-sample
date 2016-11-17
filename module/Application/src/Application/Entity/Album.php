<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Application\Repository\AlbumRepository"))
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="`album`")
 */
class Album extends EntityAbstract
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /** @ORM\Column(type="string", length=128) */
    protected $title;

    /** @ORM\Column(type="string", length=128) */
    protected $code;

    /**
     * @ORM\OneToMany(targetEntity="Image", mappedBy="images" )
     */
    protected $images;

    /** @ORM\Column(type="smallint") */
    protected $status = 1;

    /** @ORM\Column(type="datetime") */
    protected $created;

    /** @ORM\Column(type="datetime", nullable=true) */
    protected $updated;


    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->created = new \DateTime();
    }

    /**
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
        $this->updated = new \DateTime();
    }
}
