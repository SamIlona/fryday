<?php
/**
 * @category   Fryday_Application
 * @package    Admin
 * @subpackage Entity
 * @author     Vadim Izmalkov <399115@gmail.com>
 * @link       http://fryday.net
 */

namespace Admin\Entity;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass="Admin\Entity\Repository\VenueRepository")
 * @ORM\Table(name="venues")
 */
class Venue
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
     * @var Category
     * @ORM\ManyToOne(targetEntity="Admin\Entity\VenueCategory", inversedBy="venues")
     * @ORM\JoinColumn(name="category", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    // protected $category;

    /**
     * @var string
     * @ORM\Column(name="phone", type="string", length=255, nullable=true)
     */
    protected $phone;

    /**
     * @var string
     * @ORM\Column(name="website", type="string", length=255, nullable=true)
     */
    protected $website;

    /**
     * @var string
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    protected $email;

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
     * @var string
     * @ORM\Column(name="address", type="string", length=255, nullable=true)
     */
    protected $address;

    /**
     * @var City
     * @ORM\ManyToOne(targetEntity="Admin\Entity\City")
     * @ORM\JoinColumn(name="city", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     */
    protected $city;

    /**
     * @var string
     * @ORM\Column(name="country", type="string", length=255, nullable=true)
     */
    // protected $country;

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
     * @param string $category
     */
    // public function setCategory($category)
    // {
    //     $this->category = $category;
    // }

    /**
     * @return string 
     */
    // public function getCategory()
    // {
    //     return $this->category;
    // }
    /**
     * @return phone 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return string 
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * @param string $website
     */
    public function setWebsite($website)
    {
        $this->website = $website;
    }

    /**
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
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
     * @param string $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param null|Admin\Entity\City $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return Admin\Entity\City|null 
     */
    public function getCity()
    {
        return $this->city;
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
}