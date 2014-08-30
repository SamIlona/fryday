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
     * @ORM\Column(name="picture", type="text", nullable=true)
     */
    protected $picture;

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
}
