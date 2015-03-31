<?php namespace Aamant\InvoiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="payment")
 * @ORM\Entity(repositoryClass="PaymentRepository")
 */
class Payment
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
     * @ORM\ManyToOne(targetEntity="Aamant\InvoiceBundle\Entity\Invoice", inversedBy="payments")
     */
    protected $invoice;

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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Payment
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

    /**
     * Set total
     *
     * @param string $total
     * @return Payment
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
     * @return Payment
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
     * @return Payment
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
     * Set invoice
     *
     * @param \Aamant\InvoiceBundle\Entity\Invoice $invoice
     * @return Payment
     */
    public function setInvoice(\Aamant\InvoiceBundle\Entity\Invoice $invoice = null)
    {
        $this->invoice = $invoice;
        return $this;
    }

    /**
     * Get invoice
     *
     * @return \Aamant\InvoiceBundle\Entity\Invoice 
     */
    public function getInvoice()
    {
        return $this->invoice;
    }
}
