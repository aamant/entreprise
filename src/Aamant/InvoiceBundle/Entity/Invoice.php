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
    const STATUS_WAIT = "wait";
    const STATUS_PARTIAL = "partial";
    const STATUS_CLOSE = "close";

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
     * @ORM\ManyToOne(targetEntity="Aamant\InvoiceBundle\Entity\Quotation", inversedBy="invoices")
     */
    protected $quotation;

    /**
     * @ORM\OneToMany(targetEntity="Aamant\InvoiceBundle\Entity\Payment", mappedBy="invoice")
     */
    protected $payments;

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
     * @ORM\Column(type="string", length=20)
     * @Assert\NotBlank()
     */
    protected $status = "wait";

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
    protected function setNumber($number)
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
    protected function setDate($date)
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

    public function create($increment)
    {
        $this->setDate(Carbon::now());
        $this->setNumber(date('Ym').'-'.sprintf("%'.03d", $increment));
    }

    /**
     * Set quotation
     *
     * @param \Aamant\InvoiceBundle\Entity\Quotation $quotation
     * @return Invoice
     */
    public function setQuotation(\Aamant\InvoiceBundle\Entity\Quotation $quotation = null)
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

    /**
     * Add payments
     *
     * @param \Aamant\InvoiceBundle\Entity\Payment $payments
     * @return Invoice
     */
    public function addPayment(\Aamant\InvoiceBundle\Entity\Payment $payments)
    {
        $this->payments[] = $payments;
        return $this;
    }

    /**
     * Remove payments
     *
     * @param \Aamant\InvoiceBundle\Entity\Payment $payments
     */
    public function removePayment(\Aamant\InvoiceBundle\Entity\Payment $payments)
    {
        $this->payments->removeElement($payments);
    }

    /**
     * Get payments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPayments()
    {
        return $this->payments;
    }

    /**
     * @return int
     */
    public function getPaid()
    {
        $value = 0;
        foreach ($this->getPayments() as $payment){
            $value += $payment->getTotal();
        }
        return $value;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return Invoice
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
     * @return string
     */
    public function getFullname()
    {
        return $this->getNumber().' / '.$this->getCustomer()->getName().' / '.$this->getTotal();
    }

    /**
     * @return string
     */
    public function getLocalizedDate()
    {
        setlocale(LC_TIME, 'fr_FR.UTF-8');
        return Carbon::instance($this->getDate())->formatLocalized('%d %B %Y');
    }
}
