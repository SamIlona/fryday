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
 * @ORM\Entity
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
     * @ORM\Column(name="date_time", type="datetime", nullable=true)
     */
    protected $dateTime;

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
     * @var City
     * @ORM\ManyToOne(targetEntity="Content\Entity\City", inversedBy="venues")
     * @ORM\JoinColumn(name="city", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     */
    protected $city;

    /**
     * @var text
     * @ORM\Column(name="text", type="text", nullable=true)
     */
    protected $text;

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
}