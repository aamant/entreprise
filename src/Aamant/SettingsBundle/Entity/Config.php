<?php

namespace Aamant\SettingsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Config
 *
 * @ORM\Entity
 * @ORM\Table(name="config")
 * @ORM\Entity(repositoryClass="ConfigRepository")
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
     * @ORM\OneToOne(targetEntity="Aamant\UserBundle\Entity\Company", inversedBy="config")
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
     * @param \Aamant\UserBundle\Entity\Company $company
     * @return Config
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
}
