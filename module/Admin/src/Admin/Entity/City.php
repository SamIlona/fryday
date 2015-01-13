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
     * @ORM\Column(name="x_start_crop", type="integer", nullable=true)
     */
    protected $xStartCrop;

    /**
     * @var integer
     * @ORM\Column(name="y_start_crop", type="integer", nullable=true)
     */
    protected $yStartCrop;

    /**
     * @var integer
     * @ORM\Column(name="width_crop", type="integer", nullable=true)
     */
    protected $widthCrop;

    /**
     * @var integer
     * @ORM\Column(name="height_crop", type="integer", nullable=true)
     */
    protected $heightCrop;

    /**
     * @var integer
     * @ORM\Column(name="width_current", type="integer", nullable=true)
     */
    protected $widthCurrent;

    /**
     * @var integer
     * @ORM\Column(name="height_current", type="integer", nullable=true)
     */
    protected $heightCurrent;

    /**
    * @var integer
    * @ORM\ManyToOne(targetEntity="Admin\Entity\Country", inversedBy="cities")
    * @ORM\JoinColumn(name="country", referencedColumnName="id", onDelete="CASCADE")
    */
    protected $country;

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
     * @param $xStartCrop
     */
    public function setXStartCrop($xStartCrop)
    {
        $this->xStartCrop = $xStartCrop;
    }

    /**
     * @return
     */
    public function getXStartCrop()
    {
        return $this->xStartCrop;
    }

    /**
     * @param $yStartCrop
     */
    public function setYStartCrop($yStartCrop)
    {
        $this->yStartCrop = $yStartCrop;
    }

    /**
     * @return
     */
    public function getYStartCrop()
    {
        return $this->yStartCrop;
    }

    /**
     * @param $heightCrop
     */
    public function setHeightCrop($heightCrop)
    {
        $this->heightCrop = $heightCrop;
    }

    /**
     * @return
     */
    public function getHeightCrop()
    {
        return $this->heightCrop;
    }

    /**
     * @param $widthCrop
     */
    public function setWidthCrop($widthCrop)
    {
        $this->widthCrop = $widthCrop;
    }

    /**
     * @return
     */
    public function getWidthCrop()
    {
        return $this->widthCrop;
    }

    /**
     * @param $widthCurrent
     */
    public function setWidthCurrent($widthCurrent)
    {
        $this->widthCurrent = $widthCurrent;
    }

    /**
     * @return
     */
    public function getWidthCurrent()
    {
        return $this->widthCurrent;
    }

    /**
     * @param $heightCurrent
     */
    public function setHeightCurrent($heightCurrent)
    {
        $this->heightCurrent = $heightCurrent;
    }

    /**
     * @return
     */
    public function getHeightCurrent()
    {
        return $this->heightCurrent;
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
