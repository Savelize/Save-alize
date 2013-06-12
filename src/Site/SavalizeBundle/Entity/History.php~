<?php

namespace Site\SavalizeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * History
 *
 * @ORM\Table(name="history")
 * @ORM\Entity(repositoryClass="Site\SavalizeBundle\Entity\HistoryRepository")
 */
class History
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
     * @var integer
     *
     * @ORM\Column(name="price", type="integer")
     */
    private $price;

    /**
     * @var integer
     *
     * @ORM\Column(name="quantity", type="integer")
     */
    private $quantity;

    /**
     * @var \DateTime
     *
<<<<<<< HEAD
     * @ORM\ManyToOne(targetEntity="Brand" , inversedBy="brands")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="brand_id", referencedColumnName="id")
     * })
     */
    private $brands;
=======
     * @ORM\Column(name="baughtAt", type="datetime")
     */
    private $baughtAt;
>>>>>>> d1a447030e03cf700551546fedb9f196aae9e4d6

    /**
     *@ORM\ManyToOne(targetEntity="\Site\SavalizeBundle\Entity\ProductBrand", inversedBy="histories")
     *@ORM\JoinColumn(name="productBrand_id", referencedColumnName="id", onDelete = "CASCADE")
     */
    private $productBrand;

    /**
<<<<<<< HEAD
     * @var \Company
     *
     * @ORM\ManyToOne(targetEntity="Company" , inversedBy="companies")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="company_id", referencedColumnName="id")
     * })
     */
    private $companies;

    /**
     * @var \UserAccount
     *
     * @ORM\ManyToOne(targetEntity="UserAccount" , inversedBy="user_account")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @var \Product
     *
     * @ORM\ManyToOne(targetEntity="Product" , inversedBy="product")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     * })
     */
    private $products;


=======
     *@ORM\ManyToOne(targetEntity="\Site\SavalizeBundle\Entity\Customer", inversedBy="histories")
     *@ORM\JoinColumn(name="customer_id", referencedColumnName="id", onDelete = "CASCADE")
     */
    private $customer;
>>>>>>> d1a447030e03cf700551546fedb9f196aae9e4d6

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
     * Set price
     *
     * @param integer $price
     * @return History
     */
    public function setPrice($price)
    {
        $this->price = $price;
    
        return $this;
    }

    /**
     * Get price
     *
     * @return integer 
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     * @return History
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    
        return $this;
    }

    /**
     * Get quantity
     *
     * @return integer 
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set baughtAt
     *
     * @param \DateTime $baughtAt
     * @return History
     */
    public function setBaughtAt($baughtAt)
    {
        $this->baughtAt = $baughtAt;
    
        return $this;
    }

    /**
     * Get baughtAt
     *
     * @return \DateTime 
     */
    public function getBaughtAt()
    {
        return $this->baughtAt;
    }

    /**
     * Set productBrand
     *
     * @param \Site\SavalizeBundle\Entity\ProductBrand $productBrand
     * @return History
     */
    public function setProductBrand(\Site\SavalizeBundle\Entity\ProductBrand $productBrand = null)
    {
        $this->productBrand = $productBrand;
    
        return $this;
    }

    /**
     * Get productBrand
     *
     * @return \Site\SavalizeBundle\Entity\ProductBrand 
     */
    public function getProductBrand()
    {
        return $this->productBrand;
    }

    /**
     * Set customer
     *
     * @param \Site\SavalizeBundle\Entity\Customer $customer
     * @return History
     */
    public function setCustomer(\Site\SavalizeBundle\Entity\Customer $customer = null)
    {
<<<<<<< HEAD
        $this->products = $product;
=======
        $this->customer = $customer;
>>>>>>> d1a447030e03cf700551546fedb9f196aae9e4d6
    
        return $this;
    }

    /**
     * Get customer
     *
     * @return \Site\SavalizeBundle\Entity\Customer 
     */
    public function getCustomer()
    {
<<<<<<< HEAD
        return $this->products;
    }
    

    /**
     * Set brands
     *
     * @param \Site\SavalizeBundle\Entity\Brand $brands
     * @return History
     */
    public function setBrands(\Site\SavalizeBundle\Entity\Brand $brands = null)
    {
        $this->brands = $brands;
    
        return $this;
    }

    /**
     * Get brands
     *
     * @return \Site\SavalizeBundle\Entity\Brand 
     */
    public function getBrands()
    {
        return $this->brands;
    }

    /**
     * Set companies
     *
     * @param \Site\SavalizeBundle\Entity\Company $companies
     * @return History
     */
    public function setCompanies(\Site\SavalizeBundle\Entity\Company $companies = null)
    {
        $this->companies = $companies;
    
        return $this;
    }

    /**
     * Get companies
     *
     * @return \Site\SavalizeBundle\Entity\Company 
     */
    public function getCompanies()
    {
        return $this->companies;
    }

    /**
     * Set products
     *
     * @param \Site\SavalizeBundle\Entity\Product $products
     * @return History
     */
    public function setProducts(\Site\SavalizeBundle\Entity\Product $products = null)
    {
        $this->products = $products;
    
        return $this;
    }

    /**
     * Get products
     *
     * @return \Site\SavalizeBundle\Entity\Product 
     */
    public function getProducts()
    {
        return $this->products;
    }
=======
        return $this->customer;
    }
>>>>>>> d1a447030e03cf700551546fedb9f196aae9e4d6
}