<?php
/**
 * Created by PhpStorm.
 * User: asanka
 * Date: 11/10/14
 * Time: 3:43 AM
 */

namespace Gizmo\GizmoBundle\Entity;



use Symfony\Component\Security\Core\Role\RoleInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * Role
 *
 * @ORM\Table(name="roles")
 * @ORM\Entity
 */
class Role implements RoleInterface{

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string", length=30)
     */
    private $name;

    /**
     * @ORM\Column(name="role", type="string", length=20, unique=true)
     */
    private $role;

    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="Roles")
     */
    private $Users;

    public function __construct()
    {
        $this->Users = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param mixed $role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }



    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $Users
     */
    public function setUsers($Users)
    {
        $this->Users = $Users;
    }

    /**
     * @return mixed
     */
    public function getUsers()
    {
        return $this->Users;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }


} 