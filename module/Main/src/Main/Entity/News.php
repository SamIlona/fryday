<?php 
namespace Main\Entity;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Zend\Form\Annotation;

/**
 * @ORM\Entity
 * @ORM\Table(name="news")
 */
class News
{
    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    protected $title;

    /**
     * @var string
     * @ORM\Column(name="author", type="string", length=255, nullable=true)
     */
    protected $author;

    /**
     * @var string
     * @ORM\Column(name="text", type="text", nullable=true)
     */
    protected $text;

    /**
     * @var string
     * @ORM\Column(name="image", type="string", nullable=true)
     */
    protected $image;

   /**
    * @var \DateTime
    * @ORM\Column(name="date", type="datetime", nullable=true)
    */
   protected $date;

    /**
    * @var string
    * @ORM\Column(name="city", type="text", nullable=true)
    */
    protected $city;

    /**
    * @var string
    * @ORM\Column(name="country", type="text", nullable=true)
    */
    protected $country;

    /**
     * @var integer
     * @ORM\ManyToOne(targetEntity="Main\Entity\Place", inversedBy="news")
     * @ORM\JoinColumn(name="place", referencedColumnName="id")
     */
    protected $place;

    /**
    * @return string
    */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return void
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @param null|Main\Entity\Place $place
     */
    public function setPlace($place)
    {
        $this->place = $place;
    }

    /**
     * @return Main\Entity\Place|null 
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
    * @return string
    */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text
     * @return void
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
    * @return string
    */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param string $image
     * @return void
     */
    public function setImage($image)
    {
        $this->image = $image;
    }
}
