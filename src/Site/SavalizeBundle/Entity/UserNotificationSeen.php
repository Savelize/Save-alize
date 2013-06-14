<?php

namespace Site\SavalizeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserNotificationSeen
 *
 * @ORM\Table(name = "user_notification_seen")
 * @ORM\Entity(repositoryClass="Site\SavalizeBundle\Entity\UserNotificationSeenRepository")
 */
class UserNotificationSeen
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
     * @var boolean
     *
     * @ORM\Column(name="seen", type="boolean", options={"default"=false})
     */
    private $seen;

    /**
     *@ORM\ManyToOne(targetEntity="\Site\SavalizeBundle\Entity\Customer", inversedBy="userNotificationsSeen")
     *@ORM\JoinColumn(name="customer_id", referencedColumnName="id", onDelete = "CASCADE")
     */
    private $customer;
    
    /**
     *@ORM\ManyToOne(targetEntity="\Site\SavalizeBundle\Entity\UserNotification", inversedBy="userNotificationsSeen")
     *@ORM\JoinColumn(name="user_notification_id", referencedColumnName="id", onDelete = "CASCADE")
     */
    private $userNotification;
    
   
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
     * Set seen
     *
     * @param boolean $seen
     * @return UserNotificationSeen
     */
    public function setSeen($seen)
    {
        $this->seen = $seen;
    
        return $this;
    }

    /**
     * Get seen
     *
     * @return boolean 
     */
    public function getSeen()
    {
        return $this->seen;
    }
    /**
     * Set customer
     *
     * @param \Site\SavalizeBundle\Entity\Customer $customer
     * @return UserNotificationSeen
     */
    public function setCustomer(\Site\SavalizeBundle\Entity\Customer $customer = null)
    {
        $this->customer = $customer;
    
        return $this;
    }

    /**
     * Get customer
     *
     * @return \Site\SavalizeBundle\Entity\Customer 
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * Set userNotification
     *
     * @param \Site\SavalizeBundle\Entity\UserNotification $userNotification
     * @return UserNotificationSeen
     */
    public function setUserNotification(\Site\SavalizeBundle\Entity\UserNotification $userNotification = null)
    {
        $this->userNotification = $userNotification;
    
        return $this;
    }

    /**
     * Get userNotification
     *
     * @return \Site\SavalizeBundle\Entity\UserNotification 
     */
    public function getUserNotification()
    {
        return $this->userNotification;
    }
}