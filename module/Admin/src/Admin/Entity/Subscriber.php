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
 * @ORM\Entity(repositoryClass="Admin\Entity\Repository\SubscriberRepository")
 * @ORM\Table(name="subscriber")
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
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    protected $name;

	/**
     * @var string
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     */
    protected $email;

    /**
     * @var City
     * @ORM\ManyToOne(targetEntity="Content\Entity\City", inversedBy="cities")
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
     * @param null|Content\Entity\City $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return Content\Entity\City|null 
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
}