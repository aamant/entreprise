<?php namespace AppBundle\Entity;

use Carbon\Carbon;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="invoice")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\InvoiceRepository")
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Company")
     */
    protected $company;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Customer")
     */
    protected $customer;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Quotation", inversedBy="invoices")
     */
    protected $quotation;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Payment", mappedBy="invoice")
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
     * @ORM\Column(type="decimal", scale=2, options={"default":0})
     * @Assert\NotBlank()
     */
    protected $advance = 0;

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
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Invoice\Item", mappedBy="invoice", cascade={"persist"})
     */
    protected $items;

    /**
     * @ORM\Column(type="boolean", options={"default"=false})
     */
    protected $deposit_invoice = false;

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
     * @return mixed
     */
    public function getAdvance()
    {
        return $this->advance;
    }

    /**
     * @param mixed $advance
     */
    public function setAdvance($advance)
    {
        $this->advance = $advance;
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
     * @param \AppBundle\Entity\Company $company
     * @return Invoice
     */
    public function setCompany(\AppBundle\Entity\Company $company = null)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company
     *
     * @return \AppBundle\Entity\Company 
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Set customer
     *
     * @param \AppBundle\Entity\Customer $customer
     * @return Invoice
     */
    public function setCustomer(\AppBundle\Entity\Customer $customer = null)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Get customer
     *
     * @return \AppBundle\Entity\Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }


    /**
     * Add item
     *
     * @param \AppBundle\Entity\Invoice\Item $item
     * @return Invoice
     */
    public function addItem(\AppBundle\Entity\Invoice\Item $item)
    {
        $item->setInvoice($this);
        $this->items->add($item);

        return $this;
    }

    /**
     * Remove items
     *
     * @param \AppBundle\Entity\Invoice\Item $items
     */
    public function removeItem(\AppBundle\Entity\Invoice\Item $items)
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

    /**
     * @param $increment
     * @return $this
     */
    public function create($increment)
    {
        $this->setDate(Carbon::now());
        $this->setNumber(date('Ym').'-'.sprintf("%'.03d", $increment));
        return $this;
    }

    /**
     * @return $this
     */
    public function createDraft()
    {
        $this->setDate(Carbon::now());
        return $this;
    }

    /**
     * Set quotation
     *
     * @param \AppBundle\Entity\Quotation $quotation
     * @return Invoice
     */
    public function setQuotation(\AppBundle\Entity\Quotation $quotation = null)
    {
        $this->quotation = $quotation;

        return $this;
    }

    /**
     * Get quotation
     *
     * @return \AppBundle\Entity\Quotation 
     */
    public function getQuotation()
    {
        return $this->quotation;
    }

    /**
     * Add payments
     *
     * @param \AppBundle\Entity\Payment $payments
     * @return Invoice
     */
    public function addPayment(\AppBundle\Entity\Payment $payments)
    {
        $this->payments[] = $payments;
        return $this;
    }

    /**
     * Remove payments
     *
     * @param \AppBundle\Entity\Payment $payments
     */
    public function removePayment(\AppBundle\Entity\Payment $payments)
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
        if ($this->getDate())
            return Carbon::instance($this->getDate())->formatLocalized('%d %B %Y');
        else
            return Carbon::now()->formatLocalized('%d %B %Y');
    }

    /**
     * @return $this
     */
    public function calculate()
    {
        $total = 0;
        foreach ($this->getItems() as $item){
            $total += $item->getTotal();
        }
        $this->setSubTotal($total);
        $this->setTotal($total - $this->getAdvance());
        return $this;
    }

    /**
     * Set deposit_invoice
     *
     * @param boolean $depositInvoice
     * @return Invoice
     */
    public function setDepositInvoice($depositInvoice)
    {
        $this->deposit_invoice = $depositInvoice;

        return $this;
    }

    /**
     * Get deposit_invoice
     *
     * @return boolean 
     */
    public function getDepositInvoice()
    {
        return $this->deposit_invoice;
    }
}
