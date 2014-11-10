<?php
/**
 * @category   Fryday_Application
 * @package    Content
 * @subpackage Entity
 * @author     Vadim Izmalkov <399115@gmail.com>
 * @link       http://fryday.net
 */

namespace Content\Entity;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass="Content\Entity\Repository\EventRepository")
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
     * @var \DateTime
     * @ORM\Column(name="date_time_event", type="datetime", nullable=true)
     */
    protected $dateTimeEvent;

    /**
     * @var string
     * @ORM\Column(name="profile_photo", type="string", length=255, nullable=true)
     */
    protected $profileImage;

    /**
     * @var Venue
     * @ORM\ManyToOne(targetEntity="Content\Entity\Venue", inversedBy="events")
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
     * @param null|Content\Entity\Venue $venue
     */
    public function setVenue($venue)
    {
        $this->venue = $venue;
    }

    /**
     * @return Content\Entity\Venue|null 
     */
    public function getVenue()
    {
        return $this->venue;
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
    * @return \DateTime
    */
    public function getDateTimeCreated() {
        return $this->dateTimeCreated;
    }
}