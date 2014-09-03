<?php 
namespace Main\Entity;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Zend\Form\Annotation;

/**
 * @ORM\Entity
 * @ORM\Table(name="countries")
 */
class Country
{
    /**
     * @var integer
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="country", type="string", length=255, nullable=true)
     */
    private $country;

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="Main\Entity\City", mappedBy="country")
     */
    private $cities;

     /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Main\Entity\Country|null
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param null|Application\Entity\Country $country
     * @return void
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }
}
