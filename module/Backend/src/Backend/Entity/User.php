<?php
/**
 * @category   Fryday_Application
 * @package    Backend
 * @subpackage Entity
 * @author     Vadim Izmalkov <399115@gmail.com>
 * @link       http://fryday.net
 */

namespace Backend\Entity;

use Main\Entity\City;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User
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
     * @ORM\Column(name="firstName", type="string", length=255, nullable=true, unique=false)
     */
    protected $firstName;

    /**
     * @var string
     * @ORM\Column(name="lastName", type="string", length=255, nullable=true, unique=false)
     */
    protected $lastName;

    /**
     * @var string
     * @ORM\Column(name="login", type="string", length=255, nullable=true, unique=false)
     */
    protected $login;

    /**
     * @var string
     * @ORM\Column(name="password", type="string", length=255, nullable=true, unique=false)
     */
    protected $password;


    /**
     * @var Role
     * @ORM\ManyToOne(targetEntity="Backend\Entity\Role", inversedBy="users")
     * @ORM\JoinColumn(name="role", referencedColumnName="id", nullable=true, onDelete="CASCADE", unique=false)
     */
    protected $role;

    /**
     * @var City
     * @ORM\ManyToOne(targetEntity="Main\Entity\City", inversedBy="users")
     * @ORM\JoinColumn(name="city", referencedColumnName="id", nullable=true, onDelete="CASCADE", unique=false)
     */
    protected $city;

    /**
     * @var string
     * @ORM\Column(name="profile_photo", type="string", length=255, nullable=true, unique=false)
     */
    protected $profileImage;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string 
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string 
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $login
     */
    public function setLogin($login)
    {
        $this->login = $login;
    }

    /**
     * @return string 
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param integer $role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }

    /**
     * @return integer 
     */
    public function getRole()
    {
        return $this->role;
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