<?php

namespace Site\SavalizeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Admin
 *
 * @ORM\Table(name="admin")
 * @ORM\Entity(repositoryClass="Site\SavalizeBundle\Entity\AdminRepository")
 */
class Admin
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
      * @ORM\OneToOne(targetEntity="\Site\SavalizeBundle\Entity\User")
      * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
      */
    private $user;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set user
     *
     * @param \Site\SavalizeBundle\Entity\User $user
     * @return Admin
     */
    public function setUser(\Site\SavalizeBundle\Entity\User $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \Site\SavalizeBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}