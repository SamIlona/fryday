<?php
/**
 * @category   Fryday_Application
 * @package    Main
 * @subpackage Entity
 * @author     Vadim Izmalkov <399115@gmail.com>
 * @link       http://fryday.net
 */

namespace Main\Entity;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity
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
     * @ORM\ManyToOne(targetEntity="Main\Entity\VenueCategory", inversedBy="venues")
     * @ORM\JoinColumn(name="category", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    protected $category;

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
     * @ORM\Column(name="profile_photo", type="string", length=255, nullable=true)
     */
    protected $profileImage;

    /**
     * @var string
     * @ORM\Column(name="address", type="string", length=255, nullable=true)
     */
    protected $address;

    /**
     * @var City
     * @ORM\ManyToOne(targetEntity="Main\Entity\City", inversedBy="venues")
     * @ORM\JoinColumn(name="city", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     */
    protected $city;

    /**
     * @var string
     * @ORM\Column(name="country", type="string", length=255, nullable=true)
     */
    protected $country;

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
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @return string 
     */
    public function getCategory()
    {
        return $this->category;
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
}