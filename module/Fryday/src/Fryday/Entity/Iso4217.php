<?php
/**
 * @category   Fryday_Application
 * @package    Admin
 * @subpackage Entity
 * @author     Vadim Izmalkov <399115@gmail.com>
 * @link       http://fryday.net
 */

namespace Fryday\Entity;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass="Fryday\Entity\Repository\Iso4217Repository")
 * @ORM\Table(name="iso4217")
 */
class Iso4217
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
     * @ORM\Column(name="code", type="string", length=255, nullable=false)
     */
    protected $code;

    /**
     * @var string
     * @ORM\Column(name="enumber", type="string", length=255, nullable=false)
     */
    protected $enumber;

    /**
     * @var string
     * @ORM\Column(name="currency", type="string", length=255, nullable=false)
     */
    protected $currency;

    /**
     * @var string
     * @ORM\Column(name="number", type="integer", nullable=false)
     */
    protected $number;

    /**
     * @var string
     * @ORM\Column(name="locations", type="string", length=255, nullable=false)
     */
    protected $locations;

    /**
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param integer $number
     */
    public function setNumber($number)
    {
        $this->number = $number;
    }

    /**
     * @return integer 
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param string $currency
     */
    public function setCurrency()
    {
        $this->currency = $currency;
    }

    /**
     * @return string 
     */
    public function getCurrency()
    {
        return $this->currency;
    }
}