<?php
/**
 * @category   Fryday_Application
 * @package    Backend
 * @subpackage Entity
 * @author     Vadim Izmalkov <399115@gmail.com>
 * @link       http://fryday.net
 */

namespace Backend\Entity;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass="Backend\Entity\Repository\RoleRepository")
 * @ORM\Table(name="roles")
 */
class Role
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
     * @var integer
     * @ORM\Column(name="role_id", type="integer", nullable=false)
     */
    protected $roleId;

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="Backend\Entity\User", mappedBy="role")
     */
    protected $users;

    /**
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
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
     * @param integer $roleId
     */
    public function setRoleId($roleId)
    {
        $this->roleId = $roleId;
    }

    /**
     * @return integer 
     */
    public function getRoleId()
    {
        return $this->roleId;
    }
}