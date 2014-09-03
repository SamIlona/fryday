<?php 
namespace Main\Entity;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Zend\Form\Annotation;

/**
 * @ORM\Entity
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
     * @ORM\Column(name="city", type="string", length=255, nullable=false)
     */
    protected $city;

    /**
    * @var integer
    * @ORM\ManyToOne(targetEntity="Main\Entity\Country", inversedBy="cities")
    * @ORM\JoinColumn(name="country", referencedColumnName="id", onDelete="CASCADE")
    */
    protected $country;

    /**
     * Set City
     * @param string $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * Get City
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set Country
     * @param string $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * Get Country
     * @return string 
     */
    public function getCountry()
    {
        return $this->country;
    }
}
