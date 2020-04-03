<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Date;

/**
 * Class Advert
 * @package App\Entity
 * @ORM\Entity(repositoryClass="App\Repository\AdvertRepository")
 */
class Advert
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=200)
     */
    protected $content;

    /**
     * @ORM\Column(name="date", type="date")
     */
    protected $date;

    /**
     * @ORM\Column(name="title", type="string", length=200)
     */
    protected $title;

    /**
     * @ORM\Column(name="author", type="string", length=200)
     */
    protected $author;

    /**
     * @ORM\Column(name="published", type="boolean")
     */
    protected $published;

    /**
     * @ORM\OneToOne(targetEntity=Media::class, cascade={"persist"})
     */
    private $image;

    // Et bien sûr les getters/setters :

    public function __construct()
    {
        $this->date = new \DateTime();
    }

    public function setId($id)
    {
        $this->id = $id;
    }
    public function getId()
    {
        return $this->id;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }
    public function getContent()
    {
        return $this->content;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setDate($date)
    {
        $this->date = $date;
    }


    public function getTitle()
    {
        return $this->title;
    }


    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function setAuthor($author)
    {
        $this->author = $author;
    }


    public function getPublished()
    {
        return $this->published;
    }


    public function setPublished($published)
    {
        $this->published = $published;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage(Media $image = null)
    {
        $this->image = $image;
    }
}
