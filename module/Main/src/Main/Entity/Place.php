<?php 
namespace Main\Entity;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Zend\Form\Annotation;

/**
 * @ORM\Entity
 * @ORM\Table(name="places")
 */
class Place
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
     * @var integer
     * @ORM\ManyToOne(targetEntity="Main\Entity\Country", inversedBy="places")
     * @ORM\JoinColumn(name="country", referencedColumnName="id")
     */
    protected $country;

    /**
     * @var integer
     * @ORM\OneToOne(targetEntity="Main\Entity\City")
     * @ORM\JoinColumn(name="city", referencedColumnName="id")
     */
    protected $city;

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="Main\Entity\News", mappedBy="place")
     */
    private $news;

    /**
     * @return int
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
     * @param null|Main\Entity\City $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return Main\Entity\City|null 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param null|Main\Entity\Country $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return Main\Entity\Country|null 
     */
    public function getCountry()
    {
        return $this->country;
    }
}
