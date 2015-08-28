<?php

namespace Aamant\InvoiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TimeTracker
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Aamant\InvoiceBundle\Entity\TimeTrackerRepository")
 */
class TimeTracker
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
     * @ORM\Column(name="time", type="integer")
     */
    private $time;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="string", length=255, nullable=true)
     */
    private $comment;

    /**
     * @ORM\ManyToOne(targetEntity="Aamant\InvoiceBundle\Entity\Quotation\Item", inversedBy="time_tracker")
     */
    protected $quotation_item;


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
     * Set time
     *
     * @param integer $time
     * @return TimeTracker
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get time
     *
     * @return integer 
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return TimeTracker
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set comment
     *
     * @param string $comment
     * @return TimeTracker
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
     * Set quotation_item
     *
     * @param \Aamant\InvoiceBundle\Entity\Quotation\Item $quotationItem
     * @return TimeTracker
     */
    public function setQuotationItem(\Aamant\InvoiceBundle\Entity\Quotation\Item $quotationItem = null)
    {
        $this->quotation_item = $quotationItem;

        return $this;
    }

    /**
     * Get quotation_item
     *
     * @return \Aamant\InvoiceBundle\Entity\Quotation\Item 
     */
    public function getQuotationItem()
    {
        return $this->quotation_item;
    }
}
