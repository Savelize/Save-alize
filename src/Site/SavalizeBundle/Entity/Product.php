<?php

namespace Site\SavalizeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="Site\SavalizeBundle\Entity\ProductRepository")
 */
class Product
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
     * @ORM\Column(name="name", type="string", length=30)
     */
    private $name;
    /**
     *@ORM\ManyToOne(targetEntity="\Site\SavalizeBundle\Entity\Category", inversedBy="products")
     *@ORM\JoinColumn(name="category_id", referencedColumnName="id", onDelete = "CASCADE")
     */
    private $category;

    /**
     * @var integer
     *
     * @ORM\Column(name="confirmed", type="integer")
     */
    private $confirmed;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isDeleted", type="boolean")
     */
    private $isDeleted;

    
    
    /**
    *@ORM\OneToMany(targetEntity="\Site\SavalizeBundle\Entity\ProductBrand", mappedBy="product")
    **/
    private $productBrands;


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
     * Set name
     *
     * @param string $name
     * @return Product
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set confirmed
     *
     * @param integer $confirmed
     * @return Product
     */
    public function setConfirmed($confirmed)
    {
        $this->confirmed = $confirmed;
    
        return $this;
    }

    /**
     * Get confirmed
     *
     * @return integer 
     */
    public function getConfirmed()
    {
        return $this->confirmed;
    }

    /**
     * Set isDeleted
     *
     * @param boolean $isDeleted
     * @return Product
     */
    public function setIsDeleted($isDeleted)
    {
        $this->isDeleted = $isDeleted;
    
        return $this;
    }

    /**
     * Get isDeleted
     *
     * @return boolean 
     */
    public function getIsDeleted()
    {
        return $this->isDeleted;
    }

    /**
     * Set category
     *
     * @param \Site\SavalizeBundle\Entity\Category $category
     * @return Product
     */
    public function setCategory(\Site\SavalizeBundle\Entity\Category $category = null)
    {
        $this->category = $category;
    
        return $this;
    }

    /**
     * Get category
     *
     * @return \Site\SavalizeBundle\Entity\Category 
     */
    public function getCategory()
    {
        return $this->category;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->productBrands = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add productBrands
     *
     * @param \Site\SavalizeBundle\Entity\ProductBrand $productBrands
     * @return Product
     */
    public function addProductBrand(\Site\SavalizeBundle\Entity\ProductBrand $productBrands)
    {
        $this->productBrands[] = $productBrands;
    
        return $this;
    }

    /**
     * Remove productBrands
     *
     * @param \Site\SavalizeBundle\Entity\ProductBrand $productBrands
     */
    public function removeProductBrand(\Site\SavalizeBundle\Entity\ProductBrand $productBrands)
    {
        $this->productBrands->removeElement($productBrands);
    }

    /**
     * Get productBrands
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProductBrands()
    {
        return $this->productBrands;
    }
}
