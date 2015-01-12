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
 * @ORM\Entity(repositoryClass="Admin\Entity\Repository\LinkCityUserRepository")
 * @ORM\Table(name="link_city_user")
 */
class LinkCityUser
{
	/**
     * @var integer
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var City
     * @ORM\ManyToOne(targetEntity="Admin\Entity\City", inversedBy="cities")
     * @ORM\JoinColumn(name="city", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     */
    protected $city;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="Admin\Entity\User", inversedBy="events")
     * @ORM\JoinColumn(name="user", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     */
    protected $user;

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
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param null|Admin\Entity\User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return Admin\Entity\User|null 
     */
    public function getUser()
    {
        return $this->user;
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
    public function getDateTimeCreated() 
    {
        return $this->dateTimeCreated;
    }
}