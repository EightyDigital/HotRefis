<?php

namespace Eighty\RefiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Prospect
 *
 * @ORM\Table(name="prospect")
 * @ORM\Entity
 */
class Prospect
{
    /**
     * @var integer
     *
     * @ORM\Column(name="amicus_person_id", type="bigint", nullable=true)
     */
    private $amicusPersonId;

    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=255, nullable=true)
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=255, nullable=true)
     */
    private $lastname;

    /**
     * @var integer
     *
     * @ORM\Column(name="age", type="integer", nullable=true)
     */
    private $age;

    /**
     * @var string
     *
     * @ORM\Column(name="contact", type="string", length=25, nullable=true)
     */
    private $contact;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="text", nullable=true)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="postcode", type="string", length=6, nullable=true)
     */
    private $postcode;

    /**
     * @var string
     *
     * @ORM\Column(name="profession", type="string", length=255, nullable=true)
     */
    private $profession;

    /**
     * @var integer
     *
     * @ORM\Column(name="districtcode", type="integer", nullable=true)
     */
    private $districtcode;

    /**
     * @var float
     *
     * @ORM\Column(name="derived_income", type="float", precision=10, scale=0, nullable=true)
     */
    private $derivedIncome;

    /**
     * @var integer
     *
     * @ORM\Column(name="isDNC", type="integer", nullable=true)
     */
    private $isdnc;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_contact", type="datetime", nullable=true)
     */
    private $lastContact;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_refinance", type="datetime", nullable=true)
     */
    private $lastRefinance;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;



    /**
     * Set amicusPersonId
     *
     * @param integer $amicusPersonId
     * @return Prospect
     */
    public function setAmicusPersonId($amicusPersonId)
    {
        $this->amicusPersonId = $amicusPersonId;

        return $this;
    }

    /**
     * Get amicusPersonId
     *
     * @return integer 
     */
    public function getAmicusPersonId()
    {
        return $this->amicusPersonId;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     * @return Prospect
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string 
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     * @return Prospect
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string 
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set age
     *
     * @param integer $age
     * @return Prospect
     */
    public function setAge($age)
    {
        $this->age = $age;

        return $this;
    }

    /**
     * Get age
     *
     * @return integer 
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * Set contact
     *
     * @param string $contact
     * @return Prospect
     */
    public function setContact($contact)
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * Get contact
     *
     * @return string 
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Prospect
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
     * Set address
     *
     * @param string $address
     * @return Prospect
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
     * Set postcode
     *
     * @param string $postcode
     * @return Prospect
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
     * Set profession
     *
     * @param string $profession
     * @return Prospect
     */
    public function setProfession($profession)
    {
        $this->profession = $profession;

        return $this;
    }

    /**
     * Get profession
     *
     * @return string 
     */
    public function getProfession()
    {
        return $this->profession;
    }

    /**
     * Set districtcode
     *
     * @param integer $districtcode
     * @return Prospect
     */
    public function setDistrictcode($districtcode)
    {
        $this->districtcode = $districtcode;

        return $this;
    }

    /**
     * Get districtcode
     *
     * @return integer 
     */
    public function getDistrictcode()
    {
        return $this->districtcode;
    }

    /**
     * Set derivedIncome
     *
     * @param float $derivedIncome
     * @return Prospect
     */
    public function setDerivedIncome($derivedIncome)
    {
        $this->derivedIncome = $derivedIncome;

        return $this;
    }

    /**
     * Get derivedIncome
     *
     * @return float 
     */
    public function getDerivedIncome()
    {
        return $this->derivedIncome;
    }

    /**
     * Set isdnc
     *
     * @param integer $isdnc
     * @return Prospect
     */
    public function setIsdnc($isdnc)
    {
        $this->isdnc = $isdnc;

        return $this;
    }

    /**
     * Get isdnc
     *
     * @return integer 
     */
    public function getIsdnc()
    {
        return $this->isdnc;
    }

    /**
     * Set lastContact
     *
     * @param \DateTime $lastContact
     * @return Prospect
     */
    public function setLastContact($lastContact)
    {
        $this->lastContact = $lastContact;

        return $this;
    }

    /**
     * Get lastContact
     *
     * @return \DateTime 
     */
    public function getLastContact()
    {
        return $this->lastContact;
    }

    /**
     * Set lastRefinance
     *
     * @param \DateTime $lastRefinance
     * @return Prospect
     */
    public function setLastRefinance($lastRefinance)
    {
        $this->lastRefinance = $lastRefinance;

        return $this;
    }

    /**
     * Get lastRefinance
     *
     * @return \DateTime 
     */
    public function getLastRefinance()
    {
        return $this->lastRefinance;
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
}
