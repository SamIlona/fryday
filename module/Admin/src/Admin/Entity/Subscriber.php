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

//@ORM\Table(name="subscribers", indexes={@ORM\Index(name="search_idx", columns={"email"})})

/**
 * @ORM\Entity(repositoryClass="Admin\Entity\Repository\SubscriberRepository")
 * @ORM\Table(name="subscriber", indexes={@ORM\Index(name="search_idx", columns={"email"})})
 */
class Subscriber
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
     * @ORM\Column(name="first_name", type="string", length=255, nullable=true)
     */
    protected $firstName;

    /**
     * @var string
     * @ORM\Column(name="last_name", type="string", length=255, nullable=true)
     */
    protected $lastName;

	/**
     * @var string
     * @ORM\Column(name="email", type="string", length=255, nullable=false, unique=true)
     */
    protected $email;

    /**
     * @var string
     * @ORM\Column(name="company", type="string", length=255, nullable=true)
     */
    protected $company;

    /**
     * @var string
     * @ORM\Column(name="phone", type="string", length=255, nullable=true)
     */
    protected $phone;

    /**
     * @var string
     * @ORM\Column(name="position", type="string", length=255, nullable=true)
     */
    protected $position;

    /**
     * @var City
     * @ORM\ManyToOne(targetEntity="Admin\Entity\City", inversedBy="cities")
     * @ORM\JoinColumn(name="city", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     */
    protected $city;

    /**
     * @var \DateTime
     * @ORM\Column(name="date_time_created", type="datetime", nullable=true)
     */
    protected $dateTimeCreated;

    public function __construct()
    {
        $this->dateTimeCreated = new \DateTime();
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
    * @return \DateTime
    */
    public function getDateTimeCreated() {
        return $this->dateTimeCreated;
    }

    /**
     * @param string $company
     */
    public function setCompany($company)
    {
        $this->company = $company;
    }

    /**
     * @return string 
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param string $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     * @return string 
     */
    public function getPosition()
    {
        return $this->position;
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
}