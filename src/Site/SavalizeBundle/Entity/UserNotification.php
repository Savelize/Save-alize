<?php

namespace Site\SavalizeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserNotification
 *
 * @ORM\Table(name="user_notification")
 * @ORM\Entity(repositoryClass="Site\SavalizeBundle\Entity\UserNotificationRepository")
 */
class UserNotification
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
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=100)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;
    
    
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="released_at", type="datetime")
     */
    private $releasedAt;

    /**
    *@ORM\OneToMany(targetEntity="\Site\SavalizeBundle\Entity\UserNotificationSeen", mappedBy="userNotification")
    **/
    private $userNotificationsSeen;
    
    

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
     * Set content
     *
     * @param string $content
     * @return UserNotification
     */
    public function setContent($content)
    {
        $this->content = $content;
    
        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set customer
     *
     * @param \Site\SavalizeBundle\Entity\Customer $customer
     * @return UserNotification
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
     * Set title
     *
     * @param string $title
     * @return UserNotification
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set releasedAt
     *
     * @param \DateTime $releasedAt
     * @return UserNotification
     */
    public function setReleasedAt($releasedAt)
    {
        $this->releasedAt = $releasedAt;
    
        return $this;
    }

    /**
     * Get releasedAt
     *
     * @return \DateTime 
     */
    public function getReleasedAt()
    {
        return $this->releasedAt;
    }

    /**
     * Set seen
     *
     * @param boolean $seen
     * @return UserNotification
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
     * Constructor
     */
    public function __construct()
    {
        $this->userNotificationsSeen = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add userNotificationsSeen
     *
     * @param \Site\SavalizeBundle\Entity\UserNotificationSeen $userNotificationsSeen
     * @return UserNotification
     */
    public function addUserNotificationsSeen(\Site\SavalizeBundle\Entity\UserNotificationSeen $userNotificationsSeen)
    {
        $this->userNotificationsSeen[] = $userNotificationsSeen;
    
        return $this;
    }

    /**
     * Remove userNotificationsSeen
     *
     * @param \Site\SavalizeBundle\Entity\UserNotificationSeen $userNotificationsSeen
     */
    public function removeUserNotificationsSeen(\Site\SavalizeBundle\Entity\UserNotificationSeen $userNotificationsSeen)
    {
        $this->userNotificationsSeen->removeElement($userNotificationsSeen);
    }

    /**
     * Get userNotificationsSeen
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUserNotificationsSeen()
    {
        return $this->userNotificationsSeen;
    }
}

