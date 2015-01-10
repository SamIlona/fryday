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
     * @ORM\Column(name="entrance_fee", type="integer", nullable=true)
     */
    protected $entrancefee;

    /**
     * @var boolean
     * @ORM\Column(name="published", type="boolean", nullable=true)
     */
    protected $published;

    /**
     * @var Newsletter
     * @ORM\ManyToOne(targetEntity="Admin\Entity\Newsletter")
     * @ORM\JoinColumn(name="newsletter", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     */
    protected $newsletter;

    /**
     * @var boolean
     * @ORM\Column(name="newsletter_created", type="boolean", nullable=true)
     */
    protected $newsletterCreated;

    /**
     * @var boolean
     * @ORM\Column(name="newsletter_send", type="boolean", nullable=true)
     */
    protected $newsletterSend;

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
     * @param int $entrancefee
     * @return void
     */
    public function setEntrancefee($entrancefee)
    {
        $this->entrancefee = $entrancefee;
    }

    /**
     * @return int
     */
    public function getEntrancefee()
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
     * @param boolean $published
     */
    public function setPublished($published)
    {
        $this->published = $published;
    }

    /**
     * @return boolean $published
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * @param null|Admin\Entity\Newsletter $newsletter
     */
    public function setNewsletter($newsletter)
    {
        $this->newsletter = $newsletter;
    }

    /**
     * @return Admin\Entity\Newsletter|null 
     */
    public function getNewsletter()
    {
        return $this->newsletter;
    }

    /**
     * @param boolean $newsletterCreated
     */
    public function setNewsletterCreated($newsletterCreated)
    {
        $this->newsletterCreated = $newsletterCreated;
    }

    /**
     * @return boolean $newsletterCreated
     */
    public function getNewsletterCreated()
    {
        return $this->newsletterCreated;
    }

    /**
     * @param boolean $newsletterSend
     */
    public function setNewsletterSend($newsletterSend)
    {
        $this->newsletterSend = $newsletterSend;
    }

    /**
     * @return boolean $newsletterSend
     */
    public function getNewsletterSend()
    {
        return $this->newsletterSend;
    }
}