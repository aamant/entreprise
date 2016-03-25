<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Config
 *
 * @ORM\Entity
 * @ORM\Table(name="config")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ConfigRepository")
 */
class Config
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
     * @var integer
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Company", inversedBy="config")
     */
    protected $company;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    protected $invoice_increment;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(max="255")
     */
    protected $invoice_export;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(max="255")
     */
    protected $quotation_export;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(max="255")
     */
    protected $deposit_invoice_text = 'Acompte de 30% sur la proposition commerciale %s d\'un montant total de %s€';

    /**
     * @var string
     *
     * @ORM\Column(type="float")
     * @Assert\NotBlank()
     */
    protected $deposit_invoice_percent = 0.3;

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
     * Set company
     *
     * @param \AppBundle\Entity\Company $company
     * @return Config
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
     * Set invoice_increment
     *
     * @param integer $invoiceIncrement
     * @return Config
     */
    public function setInvoiceIncrement($invoiceIncrement)
    {
        $this->invoice_increment = $invoiceIncrement;

        return $this;
    }

    /**
     * Get invoice_increment
     *
     * @return integer 
     */
    public function getInvoiceIncrement()
    {
        return $this->invoice_increment;
    }

    /**
     * Set invoice_export
     *
     * @param string $invoiceExport
     * @return Config
     */
    public function setInvoiceExport($invoiceExport)
    {
        $this->invoice_export = $invoiceExport;

        return $this;
    }

    /**
     * Get invoice_export
     *
     * @return string 
     */
    public function getInvoiceExport()
    {
        return $this->invoice_export;
    }

    /**
     * Set deposit_invoice_text
     *
     * @param string $depositInvoiceText
     * @return Config
     */
    public function setDepositInvoiceText($depositInvoiceText)
    {
        $this->deposit_invoice_text = $depositInvoiceText;

        return $this;
    }

    /**
     * Get deposit_invoice_text
     *
     * @return string 
     */
    public function getDepositInvoiceText()
    {
        return $this->deposit_invoice_text;
    }

    /**
     * Set deposit_invoice_percent
     *
     * @param float $depositInvoicePercent
     * @return Config
     */
    public function setDepositInvoicePercent($depositInvoicePercent)
    {
        $this->deposit_invoice_percent = $depositInvoicePercent;

        return $this;
    }

    /**
     * Get deposit_invoice_percent
     *
     * @return float 
     */
    public function getDepositInvoicePercent()
    {
        return $this->deposit_invoice_percent;
    }

    /**
     * Set quotation_export
     *
     * @param string $quotationExport
     * @return Config
     */
    public function setQuotationExport($quotationExport)
    {
        $this->quotation_export = $quotationExport;

        return $this;
    }

    /**
     * Get quotation_export
     *
     * @return string 
     */
    public function getQuotationExport()
    {
        return $this->quotation_export;
    }
}
