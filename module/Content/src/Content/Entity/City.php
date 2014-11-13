<?php 
namespace Content\Entity;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Zend\Form\Annotation;

/**
 * @ORM\Entity(repositoryClass="Content\Entity\Repository\CityRepository")
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
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    protected $name;

    /**
     * @var string
     * @ORM\Column(name="label", type="string", length=255, nullable=false)
     */
    protected $label;

    /**
     * @var string
     * @ORM\Column(name="route", type="string", length=255, nullable=false)
     */
    protected $route;

    /**
     * @var string
     * @ORM\Column(name="profile_photo", type="string", length=255, nullable=true)
     */
    protected $profileImage;

    /**
    * @var integer
    * @ORM\ManyToOne(targetEntity="Content\Entity\Country", inversedBy="cities")
    * @ORM\JoinColumn(name="country", referencedColumnName="id", onDelete="CASCADE")
    */
    protected $country;

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="Content\Entity\Venue", mappedBy="city")
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
     * @param string $profileImage
     */
    public function setProfileImage($profileImage)
    {
        $this->profileImage = $profileImage;
    }

    /**
     * @return string 
     */
    public function getProfileImage()
    {
        return $this->profileImage;
    }

    /**
     * @param null|Content\Entity\Country $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return Content\Entity\Country|null 
     */
    public function getCountry()
    {
        return $this->country;
    }
}
