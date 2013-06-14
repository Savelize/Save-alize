<?php

namespace Site\SavalizeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProductBrand
 *
 * @ORM\Table(name="product_brand")
 * @ORM\Entity(repositoryClass="Site\SavalizeBundle\Entity\ProductBrandRepository")
 */
class ProductBrand
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
     * @ORM\Column(name="picture", type="string", length=100)
     */
    private $picture;

    /**
     *@ORM\ManyToOne(targetEntity="\Site\SavalizeBundle\Entity\Brand", inversedBy="productBrands")
     *@ORM\JoinColumn(name="brand_id", referencedColumnName="id", onDelete = "CASCADE")
     */
    private $brand;
    
    /**
     *@ORM\ManyToOne(targetEntity="\Site\SavalizeBundle\Entity\Product", inversedBy="productBrands")
     *@ORM\JoinColumn(name="product_id", referencedColumnName="id", onDelete = "CASCADE")
     */
    private $product;
    
    /**
    *@ORM\OneToMany(targetEntity="\Site\SavalizeBundle\Entity\History", mappedBy="productBrand")
    **/
    private $histories;
    
    /**
    *@ORM\OneToMany(targetEntity="\Site\SavalizeBundle\Entity\ProductRating", mappedBy="productBrand")
    **/
    private $productRatings;
    
    /**
    *@ORM\OneToMany(targetEntity="\Site\SavalizeBundle\Entity\ProductComment", mappedBy="productBrand")
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
     * Set picture
     *
     * @param string $picture
     * @return ProductBrand
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;
    
        return $this;
    }

    /**
     * Get picture
     *
     * @return string 
     */
    public function getPicture()
    {
        return $this->picture;
    }
//    /**
//     * Constructor
//     */
//    public function __construct()
//    {
//        $this->histories = new \Doctrine\Common\Collections\ArrayCollection();
//        $this->productRatings = new \Doctrine\Common\Collections\ArrayCollection();
//        $this->productComments = new \Doctrine\Common\Collections\ArrayCollection();
//    }
//    
    /**
     * Set brand
     *
     * @param \Site\SavalizeBundle\Entity\Brand $brand
     * @return ProductBrand
     */
    public function setBrand(\Site\SavalizeBundle\Entity\Brand $brand = null)
    {
        $this->brand = $brand;
    
        return $this;
    }

    /**
     * Get brand
     *
     * @return \Site\SavalizeBundle\Entity\Brand 
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * Set product
     *
     * @param \Site\SavalizeBundle\Entity\Product $product
     * @return ProductBrand
     */
    public function setProduct(\Site\SavalizeBundle\Entity\Product $product = null)
    {
        $this->product = $product;
    
        return $this;
    }

    /**
     * Get product
     *
     * @return \Site\SavalizeBundle\Entity\Product 
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Add histories
     *
     * @param \Site\SavalizeBundle\Entity\History $histories
     * @return ProductBrand
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
     * @return ProductBrand
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
     * @return ProductBrand
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

    /**
     * Set category
     *
     * @param \Site\SavalizeBundle\Entity\Category $category
     * @return ProductBrand
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
}
