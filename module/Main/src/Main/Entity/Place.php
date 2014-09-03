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
     * @ORM\ManyToMany(targetEntity="Main\Entity\City", inversedBy="city")
     * @ORM\JoinColumn(name="city", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $city;

    /**
     * @var integer
     * @ORM\ManyToMany(targetEntity="Main\Entity\Country", inversedBy="country")
     * @ORM\JoinColumn(name="country", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $country;

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
