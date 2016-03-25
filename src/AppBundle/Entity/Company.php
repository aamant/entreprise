<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="company")
 */
class Company
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank()
     * @Assert\Length(max="100")
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(max="255")
     */
    protected $address;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(max="255")
     */
    protected $address_comp;

    /**
     * @ORM\Column(type="string", length=10)
     * @Assert\NotBlank()
     * @Assert\Length(max="10")
     */
    protected $postcode;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank()
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
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank()
     * @Assert\Email
     */
    protected $email;

    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\NotBlank()
     * @Assert\Length(max="20")
     */
    protected $phone;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank()
     * @Assert\Url
     */
    protected $website;

    /**
     * @ORM\Column(type="string", length=14)
     * @Assert\NotBlank()
     * @Assert\Length(max="14")
     */
    protected $siret;

    /**
     * @ORM\Column(type="string", length=9)
     * @Assert\NotBlank()
     * @Assert\Length(max="9")
     */
    protected $siren;

    /**
     * @var ArrayCollection
     */
    protected $users;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Customer", mappedBy="company", cascade={"persist"})
     */
    protected $customers;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Config", mappedBy="company", cascade={"persist"})
     */
    protected $config;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(max="255")
     */
    protected $bank;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(max="255")
     */
    protected $indicatif;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(max="255")
     */
    protected $compte;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(max="255")
     */
    protected $keyrib;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(max="255")
     */
    protected $domiciliation;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(max="255")
     */
    protected $iban;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(max="255")
     */
    protected $bic;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->customers = new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     * @return Company
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
     * Set address
     *
     * @param string $address
     * @return Company
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set address_comp
     *
     * @param string $addressComp
     * @return Company
     */
    public function setAddressComp($addressComp)
    {
        $this->address_comp = $addressComp;

        return $this;
    }

    /**
     * Get address_comp
     *
     * @return string 
     */
    public function getAddressComp()
    {
        return $this->address_comp;
    }

    /**
     * Set postcode
     *
     * @param string $postcode
     * @return Company
     */
    public function setPostcode($postcode)
    {
        $this->postcode = $postcode;

        return $this;
    }

    /**
     * Get postcode
     *
     * @return string 
     */
    public function getPostcode()
    {
        return $this->postcode;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return Company
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set country
     *
     * @param string $country
     * @return Company
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string 
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Company
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return Company
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set website
     *
     * @param string $website
     * @return Company
     */
    public function setWebsite($website)
    {
        $this->website = $website;

        return $this;
    }

    /**
     * Get website
     *
     * @return string 
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * Set siret
     *
     * @param string $siret
     * @return Company
     */
    public function setSiret($siret)
    {
        $this->siret = $siret;

        return $this;
    }

    /**
     * Get siret
     *
     * @return string 
     */
    public function getSiret()
    {
        return $this->siret;
    }

    /**
     * Set siren
     *
     * @param string $siren
     * @return Company
     */
    public function setSiren($siren)
    {
        $this->siren = $siren;

        return $this;
    }

    /**
     * Get siren
     *
     * @return string 
     */
    public function getSiren()
    {
        return $this->siren;
    }

    /**
     * Add users
     *
     * @param \AppBundle\Entity\User $users
     * @return Company
     */
    public function addUser(\AppBundle\Entity\User $users)
    {
        $this->users[] = $users;

        return $this;
    }

    /**
     * Remove users
     *
     * @param \AppBundle\Entity\User $users
     */
    public function removeUser(\AppBundle\Entity\User $users)
    {
        $this->users->removeElement($users);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Add customers
     *
     * @param \AppBundle\Entity\Customer $customers
     * @return Company
     */
    public function addCustomer(\AppBundle\Entity\Customer $customers)
    {
        $this->customers[] = $customers;
        $customers->setCompany($this);
        return $this;
    }

    /**
     * Remove customers
     *
     * @param \AppBundle\Entity\Customer $customers
     */
    public function removeCustomer(\AppBundle\Entity\Customer $customers)
    {
        $this->customers->removeElement($customers);
    }

    /**
     * Get customers
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCustomers()
    {
        return $this->customers;
    }

    /**
     * Set bank
     *
     * @param string $bank
     * @return Company
     */
    public function setBank($bank)
    {
        $this->bank = $bank;

        return $this;
    }

    /**
     * Get bank
     *
     * @return string 
     */
    public function getBank()
    {
        return $this->bank;
    }

    /**
     * Set indicatif
     *
     * @param string $indicatif
     * @return Company
     */
    public function setIndicatif($indicatif)
    {
        $this->indicatif = $indicatif;

        return $this;
    }

    /**
     * Get indicatif
     *
     * @return string 
     */
    public function getIndicatif()
    {
        return $this->indicatif;
    }

    /**
     * Set compte
     *
     * @param string $compte
     * @return Company
     */
    public function setCompte($compte)
    {
        $this->compte = $compte;

        return $this;
    }

    /**
     * Get compte
     *
     * @return string 
     */
    public function getCompte()
    {
        return $this->compte;
    }

    /**
     * Set keyrib
     *
     * @param string $keyrib
     * @return Company
     */
    public function setKeyrib($keyrib)
    {
        $this->keyrib = $keyrib;

        return $this;
    }

    /**
     * Get keyrib
     *
     * @return string 
     */
    public function getKeyrib()
    {
        return $this->keyrib;
    }

    /**
     * Set iban
     *
     * @param string $iban
     * @return Company
     */
    public function setIban($iban)
    {
        $this->iban = $iban;

        return $this;
    }

    /**
     * Get iban
     *
     * @return string 
     */
    public function getIban()
    {
        return $this->iban;
    }

    /**
     * Set bic
     *
     * @param string $bic
     * @return Company
     */
    public function setBic($bic)
    {
        $this->bic = $bic;

        return $this;
    }

    /**
     * Get bic
     *
     * @return string 
     */
    public function getBic()
    {
        return $this->bic;
    }

    /**
     * Set domiciliation
     *
     * @param string $domiciliation
     * @return Company
     */
    public function setDomiciliation($domiciliation)
    {
        $this->domiciliation = $domiciliation;

        return $this;
    }

    /**
     * Get domiciliation
     *
     * @return string 
     */
    public function getDomiciliation()
    {
        return $this->domiciliation;
    }

    /**
     * Set config
     *
     * @param \AppBundle\Entity\Config $config
     * @return Company
     */
    public function setConfig(\AppBundle\Entity\Config $config = null)
    {
        $this->config = $config;
        $config->setCompany($this);
        return $this;
    }

    /**
     * Get config
     *
     * @return \AppBundle\Entity\Config 
     */
    public function getConfig()
    {
        return $this->config;
    }
}
