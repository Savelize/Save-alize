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
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     *@ORM\ManyToOne(targetEntity="\Site\SavalizeBundle\Entity\Customer", inversedBy="notifications")
     *@ORM\JoinColumn(name="customer_id", referencedColumnName="id", onDelete = "CASCADE")
     */
    private $customer;

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
}
