<?php namespace Aamant\CustomerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="customers")
 * @ORM\Entity(repositoryClass="CustomerRepository")
 */
class Customer
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Aamant\UserBundle\Entity\Company",inversedBy="customers")
     */
    protected $company;

    /**
     * @ORM\Column(type="string", length=1, nullable=true)
     * @Assert\Choice(choices = {"m", "f"})
     */
    protected $gender;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Assert\Length(max="100")
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Assert\Length(max="100")
     */
    protected $firstname;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Assert\Length(max="100")
     */
    protected $lastname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(max="255")
     */
    protected $address;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(max="255")
     */
    protected $address_comp;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     * @Assert\Length(max="10")
     */
    protected $postcode;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Assert\Length(max="100")
     */
    protected $city;

    /**
     * @ORM\Column(type="string", length=2)
     * @Assert\NotBlank()
     * @Assert\Country
     */
    protected $country;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Assert\Email
     */
    protected $email;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param mixed $gender
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    /**
     * @return mixed
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param mixed $company
     */
    public function setCompany($company)
    {
        $this->company = $company;
    }

    /**
     * @return mixed
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param mixed $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * @return mixed
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param mixed $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return mixed
     */
    public function getAddressComp()
    {
        return $this->address_comp;
    }

    /**
     * @param mixed $address_comp
     */
    public function setAddressComp($address_comp)
    {
        $this->address_comp = $address_comp;
    }

    /**
     * @return mixed
     */
    public function getPostcode()
    {
        return $this->postcode;
    }

    /**
     * @param mixed $postcode
     */
    public function setPostcode($postcode)
    {
        $this->postcode = $postcode;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Customer
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
}
