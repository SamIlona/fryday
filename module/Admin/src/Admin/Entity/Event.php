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
 * @ORM\Entity(repositoryClass="Admin\Entity\Repository\EventRepository")
 * @ORM\Table(name="events")
 */
class Event
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
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    protected $title;

    /**
     * @var string
     * @ORM\Column(name="titleslug", type="string", length=255, nullable=true)
     */
    protected $titleslug;

    /**
     * @var string
     * @ORM\Column(name="dateslug", type="string", length=255, nullable=true)
     */
    protected $dateslug;

    /**
     * @var \DateTime
     * @ORM\Column(name="date_time_event", type="datetime", nullable=true)
     */
    protected $dateTimeEvent;

    /**
     * @var string
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     */
    protected $image;

    /**
     * @var City
     * @ORM\ManyToOne(targetEntity="Admin\Entity\City", inversedBy="cities")
     * @ORM\JoinColumn(name="city", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     */
    protected $city;

    /**
     * @var Venue
     * @ORM\ManyToOne(targetEntity="Admin\Entity\Venue", inversedBy="events")
     * @ORM\JoinColumn(name="venue", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     */
    protected $venue;

    /**
     * @var text
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    protected $description;

    /**
     * @var text
     * @ORM\Column(name="details", type="text", nullable=true)
     */
    protected $details;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="Admin\Entity\User", inversedBy="events")
     * @ORM\JoinColumn(name="user", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     */
    protected $user;

    /**
     * @var integer
     * @ORM\Column(name="entrance_fee", type="integer", length=255, nullable=true)
     */
    protected $entrancefee;

    /**
     * @var boolean
     * @ORM\Column(name="pubished", type="boolean", nullable=true)
     */
    protected $pubished;

    /**
     * @var boolean
     * @ORM\Column(name="newsletter", type="boolean", nullable=true)
     */
    protected $newsletter;

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
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $titleslug
     */
    public function setTitleSlug($titleslug)
    {
        $this->titleslug = $titleslug;
    }

    /**
     * @return string 
     */
    public function getTitleSlug()
    {
        return $this->titleslug;
    }

    /**
     * @param string $dateslug
     */
    public function setDateSlug($dateslug)
    {
        $this->dateslug = $dateslug;
    }

    /**
     * @return string 
     */
    public function getDateSlug()
    {
        return $this->dateslug;
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
     * @param null|Admin\Entity\Venue $venue
     */
    public function setVenue($venue)
    {
        $this->venue = $venue;
    }

    /**
     * @return Admin\Entity\Venue|null 
     */
    public function getVenue()
    {
        return $this->venue;
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
    public function getDateTimeEvent() {
        return $this->dateTimeEvent;
    }

    /**
    * @param \DateTime $dateTimeEvent
    */
    public function setDateTimeEvent($dateTimeEvent) {
        $this->dateTimeEvent = $dateTimeEvent;
    }

    /**
     * @return string
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * @param string $details
     * @return void
     */
    public function setDetails($details)
    {
        $this->details = $details;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return void
     */
    public function setDescription($description)
    {
        $this->description = $description;
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
     * @param $entrancefee
     */
    public function setEntranceFee($entrancefee)
    {
        $this->entrancefee = $entrancefee;
    }

    /**
     * @return
     */
    public function getEntranceFee()
    {
        return $this->entrancefee;
    }

    /**
    * @return \DateTime
    */
    public function getDateTimeCreated() {
        return $this->dateTimeCreated;
    }

    /**
     * @param boolean $pubished
     */
    public function setPubished($pubished)
    {
        $this->pubished = $pubished;
    }

    /**
     * @return boolean $pubished
     */
    public function getPubished()
    {
        return $this->pubished;
    }

    /**
     * @param boolean $newsletter
     */
    public function setNewsletter($newsletter)
    {
        $this->newsletter = $newsletter;
    }

    /**
     * @return boolean $newsletter
     */
    public function getNewsletter()
    {
        return $this->newsletter;
    }
}