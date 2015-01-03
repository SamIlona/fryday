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
 * @ORM\Entity(repositoryClass="Admin\Entity\Repository\NewsletterRepository")
 * @ORM\Table(name="newsletters")
 */
class Newsletter
{
	/**
     * @var integer
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var Event
     * @ORM\ManyToOne(targetEntity="Admin\Entity\Event", inversedBy="newsletters")
     * @ORM\JoinColumn(name="event", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     */
    protected $event;

    /**
     * @var Partner1
     * @ORM\ManyToOne(targetEntity="Admin\Entity\Partner", inversedBy="newsletters")
     * @ORM\JoinColumn(name="partner1", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     */
    protected $partner1;

    /**
     * @var Partner2
     * @ORM\ManyToOne(targetEntity="Admin\Entity\Partner", inversedBy="newsletters")
     * @ORM\JoinColumn(name="partner2", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     */
    protected $partner2;

    /**
     * @var Partner3
     * @ORM\ManyToOne(targetEntity="Admin\Entity\Partner", inversedBy="newsletters")
     * @ORM\JoinColumn(name="partner3", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     */
    protected $partner3;

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
     * @param null|Admin\Entity\Event $event
     */
    public function setEvent($event)
    {
        $this->event = $event;
    }

    /**
     * @return Admin\Entity\Event|null 
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @param null|Admin\Entity\Partner $partner1
     */
    public function setPartner1($partner1)
    {
        $this->partner1 = $partner1;
    }

    /**
     * @return Admin\Entity\Partner|null 
     */
    public function getPartner1()
    {
        return $this->partner1;
    }

    /**
     * @param null|Admin\Entity\Partner $partner2
     */
    public function setPartner2($partner2)
    {
        $this->partner2 = $partner2;
    }

    /**
     * @return Admin\Entity\Partner|null 
     */
    public function getPartner2()
    {
        return $this->partner2;
    }

    /**
     * @param null|Admin\Entity\Partner $partner3
     */
    public function setPartner3($partner3)
    {
        $this->partner3 = $partner3;
    }

    /**
     * @return Admin\Entity\Partner|null 
     */
    public function getPartner3()
    {
        return $this->partner3;
    }

    /**
    * @return \DateTime
    */
    public function getDateTimeCreated() {
        return $this->dateTimeCreated;
    }
}