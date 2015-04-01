<?php namespace Aamant\InvoiceBundle\Entity;

use Carbon\Carbon;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="quotation")
 * @ORM\Entity(repositoryClass="QuotationRepository")
 */
class Quotation
{
    const STATUS_ACCEPT = "accept";
    const STATUS_PARTIAL_INVOICED = "partial_invoiced";
    const STATUS_WAIT = "wait";
    const STATUS_INVOICED = "invoiced";
    const STATUS_REFUSED = "refused";
    const STATUS_CANCELLED = "cancelled";

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
     * @ORM\OneToMany(targetEntity="Aamant\InvoiceBundle\Entity\Invoice", mappedBy="quotation")
     */
    protected $invoices;

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
    protected $total;

    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\Length(max="20")
     */
    protected $status;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->invoices = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Quotation
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
     * Set date
     *
     * @param \DateTime $date
     * @return Quotation
     */
    public function setDate(\DateTime $date)
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

    /**
     * Set total
     *
     * @param string $total
     * @return Quotation
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
     * Set status
     *
     * @param string $status
     * @return Quotation
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set company
     *
     * @param \Aamant\UserBundle\Entity\Company $company
     * @return Quotation
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
     * @return Quotation
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

    public function getStatusName()
    {
        switch ($this->status){
            case static::STATUS_WAIT:
                return "An attente";
            case static::STATUS_ACCEPT:
                return "Accepté";
            case static::STATUS_REFUSED:
                return "Refusé";
            case static::STATUS_CANCELLED:
                return "Annulé";
            case static::STATUS_PARTIAL_INVOICED:
                return "Facturation partielle";
            case static::STATUS_INVOICED:
                return 'facturé';
        }
    }

    /**
     * Add invoices
     *
     * @param \Aamant\InvoiceBundle\Entity\Invoice $invoices
     * @return Quotation
     */
    public function addInvoice(\Aamant\InvoiceBundle\Entity\Invoice $invoices)
    {
        $this->invoices[] = $invoices;

        return $this;
    }

    /**
     * Remove invoices
     *
     * @param \Aamant\InvoiceBundle\Entity\Invoice $invoices
     */
    public function removeInvoice(\Aamant\InvoiceBundle\Entity\Invoice $invoices)
    {
        $this->invoices->removeElement($invoices);
    }

    /**
     * Get invoices
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getInvoices()
    {
        return $this->invoices;
    }

    public function getInvoicedValue()
    {
        $value = 0;
        foreach ($this->getInvoices() as $invoice){
            $value += $invoice->getTotal();
        }
        return $value;
    }

    public function getBilled()
    {
        $value = 0;
        foreach ($this->getInvoices() as $invoice){
            $value += $invoice->getTotal();
        }
        return $value;
    }

    public function getPaid()
    {
        $value = 0;
        foreach ($this->getInvoices() as $invoice){
            $value += $invoice->getPaid();
        }
        return $value;
    }

    public function getFullname()
    {
        return $this->getNumber().' / '.$this->getCustomer()->getName().' / '.$this->getTotal();
    }
}
