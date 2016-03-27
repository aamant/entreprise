<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tax
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TaxRepository")
 */
class Tax
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
     * @var \DateTime
     *
     * @ORM\Column(name="_month", type="string", length=10)
     */
    private $month;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="_year", type="string", length=4)
     */
    private $year;

    /**
     * @var float
     *
     * @ORM\Column(name="estimate", type="float")
     */
    private $estimate;

    /**
     * @var float
     *
     * @ORM\Column(name="value", type="float")
     */
    private $value;

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
     * Set month
     *
     * @param string $month
     * @return Tax
     */
    public function setMonth($month)
    {
        $this->month = $month;

        return $this;
    }

    /**
     * Get month
     *
     * @return string 
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * Set year
     *
     * @param string $year
     * @return Tax
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return \year 
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set estimate
     *
     * @param float $estimate
     * @return Tax
     */
    public function setEstimate($estimate)
    {
        $this->estimate = $estimate;

        return $this;
    }

    /**
     * Get estimate
     *
     * @return float 
     */
    public function getEstimate()
    {
        return $this->estimate;
    }

    /**
     * Set value
     *
     * @param float $value
     * @return Tax
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return float 
     */
    public function getValue()
    {
        return $this->value;
    }
}
