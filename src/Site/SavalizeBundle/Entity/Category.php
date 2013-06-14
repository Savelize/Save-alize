<?php

namespace Site\SavalizeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Category
 *
 * @ORM\Table(name="category")
 * @ORM\Entity(repositoryClass="Site\SavalizeBundle\Entity\CategoryRepository")
 */
class Category
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
    *@ORM\OneToMany(targetEntity="\Site\SavalizeBundle\Entity\Product", mappedBy="category")
    **/
    private $products;
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
     * @return Category
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
     * @return Category
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
     * @return Category
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
     * Constructor
     */
    public function __construct()
    {
        $this->products = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add products
     *
     * @param \Site\SavalizeBundle\Entity\Product $products
     * @return Category
     */
    public function addProduct(\Site\SavalizeBundle\Entity\Product $products)
    {
        $this->products[] = $products;
    
        return $this;
    }

    /**
     * Remove products
     *
     * @param \Site\SavalizeBundle\Entity\Product $products
     */
    public function removeProduct(\Site\SavalizeBundle\Entity\Product $products)
    {
        $this->products->removeElement($products);
    }

    /**
     * Get products
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * Add productBrands
     *
     * @param \Site\SavalizeBundle\Entity\ProductBrand $productBrands
     * @return Category
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
