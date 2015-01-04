<?php 
namespace Admin\Entity;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Zend\Form\Annotation;

/**
 * @ORM\Entity(repositoryClass="Admin\Entity\Repository\CityRepository")
 * @ORM\Table(name="cities")
 */
class City
{
    /**
     * @var integer
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    protected $name;

    /**
     * @var string
     * @ORM\Column(name="label", type="string", length=255, nullable=true)
     */
    protected $label;

    /**
     * @var string
     * @ORM\Column(name="route", type="string", length=255, nullable=true)
     */
    protected $route;

    /**
     * @var string
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     */
    protected $image;

    /**
    * @var integer
    * @ORM\ManyToOne(targetEntity="Admin\Entity\Country", inversedBy="cities")
    * @ORM\JoinColumn(name="country", referencedColumnName="id", onDelete="CASCADE")
    */
    protected $country;

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="Admin\Entity\Venue", mappedBy="city")
     */
    //protected $venues;

   /**
    * @var Collection
    * @ORM\OneToMany(targetEntity="Admin\Entity\User", mappedBy="city")
    */
   protected $users;

    /**
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * @return string 
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $route
     */
    public function setRoute($route)
    {
        $this->route = $route;
    }

    /**
     * @return string 
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @param string $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * @return string 
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param null|Admin\Entity\Country $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return Admin\Entity\Country|null 
     */
    public function getCountry()
    {
        return $this->country;
    }
}
