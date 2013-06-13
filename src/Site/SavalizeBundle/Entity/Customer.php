<?php

namespace Site\SavalizeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Customer
 *
 * @ORM\Table(name="customer")
 * @ORM\Entity(repositoryClass="Site\SavalizeBundle\Entity\CustomerRepository")
 */
class Customer
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
     * @ORM\Column(name="country", type="string", length=30,nullable=true)
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=30,nullable=true)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="region", type="string", length=30,nullable=true)
     */
    private $region;

    /**
     * @var integer
     *
     * @ORM\Column(name="age", type="integer",nullable=true)
     */
    private $age;

    /**
     * @var string
     *
     * @ORM\Column(name="marital_status", type="string", length=6,nullable=true)
     */
    private $maritalStatus;

    /**
     * @var boolean
     *
     * @ORM\Column(name="car", type="boolean",nullable=true)
     */
    private $car;

    /**
     * @var integer
     *
     * @ORM\Column(name="salary", type="integer",nullable=true)
     */
    private $salary;
     /**
      * @ORM\OneToOne(targetEntity="\Site\SavalizeBundle\Entity\User")
      * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
      */
    private $user;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="linked_to", type="integer",nullable=true)
     */
    private $linkedTo;

    /**
    *@ORM\OneToMany(targetEntity="\Site\SavalizeBundle\Entity\UserNotification", mappedBy="customer")
    **/
    private $notifications;
    
    /**
    *@ORM\OneToMany(targetEntity="\Site\SavalizeBundle\Entity\History", mappedBy="customer")
    **/
    private $histories;

    /**
    *@ORM\OneToMany(targetEntity="\Site\SavalizeBundle\Entity\ProductRating", mappedBy="customer")
    **/
    private $productRatings;
    
    /**
    *@ORM\OneToMany(targetEntity="\Site\SavalizeBundle\Entity\ProductComment", mappedBy="customer")
    **/
    private $productComments;

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
     * Set country
     *
     * @param string $country
     * @return Customer
     */
    public function setCountry($country)
    {
        $this->country = $country;
    
        return $this;
    }

    /**
     * Get country
     *
     * @return string 
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return Customer
     */
    public function setCity($city)
    {
        $this->city = $city;
    
        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set region
     *
     * @param string $region
     * @return Customer
     */
    public function setRegion($region)
    {
        $this->region = $region;
    
        return $this;
    }

    /**
     * Get region
     *
     * @return string 
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Set age
     *
     * @param integer $age
     * @return Customer
     */
    public function setAge($age)
    {
        $this->age = $age;
    
        return $this;
    }

    /**
     * Get age
     *
     * @return integer 
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * Set maritalStatus
     *
     * @param string $maritalStatus
     * @return Customer
     */
    public function setMaritalStatus($maritalStatus)
    {
        $this->maritalStatus = $maritalStatus;
    
        return $this;
    }

    /**
     * Get maritalStatus
     *
     * @return string 
     */
    public function getMaritalStatus()
    {
        return $this->maritalStatus;
    }

    /**
     * Set car
     *
     * @param boolean $car
     * @return Customer
     */
    public function setCar($car)
    {
        $this->car = $car;
    
        return $this;
    }

    /**
     * Get car
     *
     * @return boolean 
     */
    public function getCar()
    {
        return $this->car;
    }

    /**
     * Set salary
     *
     * @param integer $salary
     * @return Customer
     */
    public function setSalary($salary)
    {
        $this->salary = $salary;
    
        return $this;
    }

    /**
     * Get salary
     *
     * @return integer 
     */
    public function getSalary()
    {
        return $this->salary;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     * @return Customer
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    
        return $this;
    }

    /**
     * Get userId
     *
     * @return integer 
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set linkedTo
     *
     * @param integer $linkedTo
     * @return Customer
     */
    public function setLinkedTo($linkedTo)
    {
        $this->linkedTo = $linkedTo;
    
        return $this;
    }

    /**
     * Get linkedTo
     *
     * @return integer 
     */
    public function getLinkedTo()
    {
        return $this->linkedTo;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->notifications = new \Doctrine\Common\Collections\ArrayCollection();
        $this->histories = new \Doctrine\Common\Collections\ArrayCollection();
        $this->productRatings = new \Doctrine\Common\Collections\ArrayCollection();
        $this->productComments = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Set user
     *
     * @param \Site\SavalizeBundle\Entity\User $user
     * @return Customer
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

    /**
     * Add notifications
     *
     * @param \Site\SavalizeBundle\Entity\UserNotification $notifications
     * @return Customer
     */
    public function addNotification(\Site\SavalizeBundle\Entity\UserNotification $notifications)
    {
        $this->notifications[] = $notifications;
    
        return $this;
    }

    /**
     * Remove notifications
     *
     * @param \Site\SavalizeBundle\Entity\UserNotification $notifications
     */
    public function removeNotification(\Site\SavalizeBundle\Entity\UserNotification $notifications)
    {
        $this->notifications->removeElement($notifications);
    }

    /**
     * Get notifications
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getNotifications()
    {
        return $this->notifications;
    }

    /**
     * Add histories
     *
     * @param \Site\SavalizeBundle\Entity\History $histories
     * @return Customer
     */
    public function addHistorie(\Site\SavalizeBundle\Entity\History $histories)
    {
        $this->histories[] = $histories;
    
        return $this;
    }

    /**
     * Remove histories
     *
     * @param \Site\SavalizeBundle\Entity\History $histories
     */
    public function removeHistorie(\Site\SavalizeBundle\Entity\History $histories)
    {
        $this->histories->removeElement($histories);
    }

    /**
     * Get histories
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getHistories()
    {
        return $this->histories;
    }

    /**
     * Add productRatings
     *
     * @param \Site\SavalizeBundle\Entity\ProductRating $productRatings
     * @return Customer
     */
    public function addProductRating(\Site\SavalizeBundle\Entity\ProductRating $productRatings)
    {
        $this->productRatings[] = $productRatings;
    
        return $this;
    }

    /**
     * Remove productRatings
     *
     * @param \Site\SavalizeBundle\Entity\ProductRating $productRatings
     */
    public function removeProductRating(\Site\SavalizeBundle\Entity\ProductRating $productRatings)
    {
        $this->productRatings->removeElement($productRatings);
    }

    /**
     * Get productRatings
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProductRatings()
    {
        return $this->productRatings;
    }

    /**
     * Add productComments
     *
     * @param \Site\SavalizeBundle\Entity\ProductComment $productComments
     * @return Customer
     */
    public function addProductComment(\Site\SavalizeBundle\Entity\ProductComment $productComments)
    {
        $this->productComments[] = $productComments;
    
        return $this;
    }

    /**
     * Remove productComments
     *
     * @param \Site\SavalizeBundle\Entity\ProductComment $productComments
     */
    public function removeProductComment(\Site\SavalizeBundle\Entity\ProductComment $productComments)
    {
        $this->productComments->removeElement($productComments);
    }

    /**
     * Get productComments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProductComments()
    {
        return $this->productComments;
    }
}