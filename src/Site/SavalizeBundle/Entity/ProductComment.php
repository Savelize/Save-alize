<?php

namespace Site\SavalizeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProductComment
 *
 * @ORM\Table(name="product_comment")
 * @ORM\Entity(repositoryClass="Site\SavalizeBundle\Entity\ProductCommentRepository")
 */
class ProductComment
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
     * @ORM\Column(name="comment", type="text")
     */
    private $comment;

    /**
     *@ORM\ManyToOne(targetEntity="\Site\SavalizeBundle\Entity\ProductBrand", inversedBy="productComments")
     *@ORM\JoinColumn(name="productBrand_id", referencedColumnName="id", onDelete = "CASCADE")
     */
    private $productBrand;

    /**
     *@ORM\ManyToOne(targetEntity="\Site\SavalizeBundle\Entity\Customer", inversedBy="productComments")
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
     * Set comment
     *
     * @param string $comment
     * @return ProductComment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    
        return $this;
    }

    /**
     * Get comment
     *
     * @return string 
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set productBrand
     *
     * @param \Site\SavalizeBundle\Entity\ProductBrand $productBrand
     * @return ProductComment
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
     * @return ProductComment
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