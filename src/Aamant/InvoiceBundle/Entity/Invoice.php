<?php namespace Aamant\InvoiceBundle\Entity;

use Carbon\Carbon;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="invoice")
 * @ORM\Entity(repositoryClass="InvoiceRepository")
 */
class Invoice
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Aamant\UserBundle\Entity\Company")
     */
    protected $company;

    /**
     * @ORM\ManyToOne(targetEntity="Aamant\CustomerBundle\Entity\Customer")
     */
    protected $customer;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Assert\Length(max="100")
     */
    protected $number;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Assert\Date
     */
    protected $date;

    /**
     * @ORM\Column(type="decimal", scale=2)
     * @Assert\NotBlank()
     */
    protected $sub_total;

    /**
     * @ORM\Column(type="decimal", scale=2)
     * @Assert\NotBlank()
     */
    protected $total;

    /**
     * @ORM\OneToMany(targetEntity="Aamant\InvoiceBundle\Entity\Invoice\Item", mappedBy="invoice", cascade={"persist"})
     */
    protected $items;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

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
     * Set number
     *
     * @param string $number
     * @return Invoice
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return string 
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set sub_total
     *
     * @param string $subTotal
     * @return Invoice
     */
    public function setSubTotal($subTotal)
    {
        $this->sub_total = $subTotal;

        return $this;
    }

    /**
     * Get sub_total
     *
     * @return string 
     */
    public function getSubTotal()
    {
        return $this->sub_total;
    }

    /**
     * Set total
     *
     * @param string $total
     * @return Invoice
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
     * Set company
     *
     * @param \Aamant\UserBundle\Entity\Company $company
     * @return Invoice
     */
    public function setCompany(\Aamant\UserBundle\Entity\Company $company = null)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company
     *
     * @return \Aamant\UserBundle\Entity\Company 
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Set customer
     *
     * @param \Aamant\CustomerBundle\Entity\Customer $customer
     * @return Invoice
     */
    public function setCustomer(\Aamant\CustomerBundle\Entity\Customer $customer = null)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Get customer
     *
     * @return \Aamant\CustomerBundle\Entity\Customer 
     */
    public function getCustomer()
    {
        return $this->customer;
    }


    /**
     * Add item
     *
     * @param \Aamant\InvoiceBundle\Entity\Invoice\Item $item
     * @return Invoice
     */
    public function addItem(\Aamant\InvoiceBundle\Entity\Invoice\Item $item)
    {
        $item->setInvoice($this);
        $this->items->add($item);

        return $this;
    }

    /**
     * Remove items
     *
     * @param \Aamant\InvoiceBundle\Entity\Invoice\Item $items
     */
    public function removeItem(\Aamant\InvoiceBundle\Entity\Invoice\Item $items)
    {
        $this->items->removeElement($items);
    }

    /**
     * Get items
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Invoice
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    public function create()
    {
        $this->setDate(Carbon::now());
        $this->setNumber(date('Ym').'-1');
    }
}
