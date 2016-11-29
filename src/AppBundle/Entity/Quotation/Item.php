<?php namespace AppBundle\Entity\Quotation;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="quotation_item")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Quotation\ItemRepository")
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Quotation", inversedBy="items", cascade={"persist"})
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
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\TimeTracker", mappedBy="quotation_item", cascade={"persist"})
     */
    protected $time_tracker;

    /**
     * @ORM\Column(type="decimal", scale=2, options={"default":0})
     * @Assert\NotBlank()
     */
    protected $past_time = 0;

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
     * @param \AppBundle\Entity\Quotation $quotation
     * @return Item
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
     * Constructor
     */
    public function __construct()
    {
        $this->time_tracker = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set past_time
     *
     * @param string $pastTime
     * @return Item
     */
    public function setPastTime($pastTime)
    {
        $this->past_time = $pastTime;

        return $this;
    }

    /**
     * Get past_time
     *
     * @return string 
     */
    public function getPastTime()
    {
        return $this->past_time;
    }

    /**
     * Add time_tracker
     *
     * @param \AppBundle\Entity\TimeTracker $timeTracker
     * @return Item
     */
    public function addTimeTracker(\AppBundle\Entity\TimeTracker $timeTracker)
    {
        $this->time_tracker[] = $timeTracker;

        return $this;
    }

    /**
     * Remove time_tracker
     *
     * @param \AppBundle\Entity\TimeTracker $timeTracker
     */
    public function removeTimeTracker(\AppBundle\Entity\TimeTracker $timeTracker)
    {
        $this->time_tracker->removeElement($timeTracker);
    }

    /**
     * Get time_tracker
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTimeTracker()
    {
        return $this->time_tracker;
    }
}
