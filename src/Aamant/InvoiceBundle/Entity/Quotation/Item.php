<?php namespace Aamant\InvoiceBundle\Entity\Quotation;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="quotation_item")
 * @ORM\Entity(repositoryClass="ItemRepository")
 */
class Item
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Aamant\InvoiceBundle\Entity\Quotation", inversedBy="items", cascade={"persist"})
     * @ORM\JoinColumn(name="quotation_id", referencedColumnName="id", nullable=false)
     */
    protected $quotation;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    protected $name;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank()
     */
    protected $quantity;

    /**
     * @ORM\Column(type="decimal", scale=2)
     * @Assert\NotBlank()
     */
    protected $price;

    /**
     * @ORM\Column(type="decimal", scale=2)
     * @Assert\NotBlank()
     */
    protected $total;

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
     * @return Item
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
     * Set quantity
     *
     * @param float $quantity
     * @return Item
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return float 
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set price
     *
     * @param string $price
     * @return Item
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string 
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set total
     *
     * @param string $total
     * @return Item
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total
     *
     * @return string 
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set quotation
     *
     * @param \Aamant\InvoiceBundle\Entity\Quotation $quotation
     * @return Item
     */
    public function setQuotation(\Aamant\InvoiceBundle\Entity\Quotation $quotation)
    {
        $this->quotation = $quotation;

        return $this;
    }

    /**
     * Get quotation
     *
     * @return \Aamant\InvoiceBundle\Entity\Quotation 
     */
    public function getQuotation()
    {
        return $this->quotation;
    }
}
