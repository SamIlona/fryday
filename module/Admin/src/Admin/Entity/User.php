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
 * @ORM\Entity(repositoryClass="Admin\Entity\Repository\UserRepository")
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
     * @ORM\Column(name="email", type="string", length=255, nullable=true, unique=true)
     */
    protected $email;

    /**
     * @var string
     * @ORM\Column(name="company", type="string", length=255, nullable=true, unique=false)
     */
    protected $company;

    /**
     * @var string
     * @ORM\Column(name="position", type="string", length=255, nullable=true, unique=false)
     */
    protected $position;

    /**
     * @var string
     * @ORM\Column(name="facebook", type="string", length=255, nullable=true, unique=false)
     */
    protected $facebook;

    /**
     * @var string
     * @ORM\Column(name="linkedin", type="string", length=255, nullable=true, unique=false)
     */
    protected $linkedin;

    /**
     * @var string
     * @ORM\Column(name="twitter", type="string", length=255, nullable=true, unique=false)
     */
    protected $twitter;

    /**
     * @var string
     * @ORM\Column(name="skype", type="string", length=255, nullable=true, unique=false)
     */
    protected $skype;

    /**
     * @var string
     * @ORM\Column(name="phone", type="string", length=255, nullable=true, unique=false)
     */
    protected $phone;

    /**
     * @var string
     * @ORM\Column(name="googleplus", type="string", length=255, nullable=true, unique=false)
     */
    protected $googleplus;

    /**
     * @var string
     * @ORM\Column(name="password", type="string", length=255, nullable=true, unique=false)
     */
    protected $password;

    /**
     * @var Role
     * @ORM\ManyToOne(targetEntity="Admin\Entity\Role", inversedBy="users")
     * @ORM\JoinColumn(name="role", referencedColumnName="id", nullable=true, onDelete="CASCADE", unique=false)
     */
    protected $role;

    /**
     * @var City
     * @ORM\ManyToOne(targetEntity="Admin\Entity\City", inversedBy="users")
     * @ORM\JoinColumn(name="city", referencedColumnName="id", nullable=true, onDelete="CASCADE", unique=false)
     */
    protected $city;

    /**
     * @var string
     * @ORM\Column(name="image", type="string", length=255, nullable=true, unique=false)
     */
    protected $image;

    /**
     * @var string
     * @ORM\Column(name="registration_token", type="string", length=255, nullable=true)
     */
    protected $registrationToken;

    /**
     * @var string
     * @ORM\Column(name="email_confirmed", type="boolean", nullable=false)
     */
    protected $emailConfirmed;

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
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $facebook
     */
    public function setFacebook($facebook)
    {
        $this->facebook = $facebook;
    }

    /**
     * @return string 
     */
    public function getFacebook()
    {
        return $this->facebook;
    }

    /**
     * @param string $linkedin
     */
    public function setLinkedin($linkedin)
    {
        $this->linkedin = $linkedin;
    }

    /**
     * @return string 
     */
    public function getLinkedin()
    {
        return $this->linkedin;
    }

    /**
     * @param string $twitter
     */
    public function setTwitter($twitter)
    {
        $this->twitter = $twitter;
    }

    /**
     * @return string 
     */
    public function getTwitter()
    {
        return $this->twitter;
    }

    /**
     * @param string $skype
     */
    public function setSkype($skype)
    {
        $this->skype = $skype;
    }

    /**
     * @return string 
     */
    public function getSkype()
    {
        return $this->skype;
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
    public function getPhone()
    {
        return $this->phone;
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
     * @param null|User\Entity\Role $role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }

    /**
     * @return User\Entity\Role|null
     */
    public function getRole()
    {
        return $this->role;
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
     * @param string $registrationToken
     */
    public function setRegistrationToken($registrationToken)
    {
        $this->registrationToken = $registrationToken;
    }

    /**
     * @return string 
     */
    public function getRegistrationToken()
    {
        return $this->registrationToken;
    }

    /**
     * @param string $emailConfirmed
     */
    public function setEmailConfirmed($emailConfirmed)
    {
        $this->emailConfirmed = $emailConfirmed;
    }

    /**
     * @return string 
     */
    public function getEmailConfirmed()
    {
        return $this->emailConfirmed;
    }
}