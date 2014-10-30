<?php

namespace Eighty\RefiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Hometracker
 *
 * @ORM\Table(name="hometracker")
 * @ORM\Entity
 */
class Hometracker
{
    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=50, nullable=true)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=50, nullable=true)
     */
    private $password;

    /**
     * @var integer
     *
     * @ORM\Column(name="prospect_id", type="bigint", nullable=true)
     */
    private $prospectId;

    /**
     * @var string
     *
     * @ORM\Column(name="contact", type="string", length=20, nullable=true)
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
     * @var integer
     *
     * @ORM\Column(name="referrer", type="bigint", nullable=true)
     */
    private $referrer;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;



    /**
     * Set username
     *
     * @param string $username
     * @return Hometracker
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return Hometracker
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set prospectId
     *
     * @param integer $prospectId
     * @return Hometracker
     */
    public function setProspectId($prospectId)
    {
        $this->prospectId = $prospectId;

        return $this;
    }

    /**
     * Get prospectId
     *
     * @return integer 
     */
    public function getProspectId()
    {
        return $this->prospectId;
    }

    /**
     * Set contact
     *
     * @param string $contact
     * @return Hometracker
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
     * @return Hometracker
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
     * @return Hometracker
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
     * @return Hometracker
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
     * Set referrer
     *
     * @param integer $referrer
     * @return Hometracker
     */
    public function setReferrer($referrer)
    {
        $this->referrer = $referrer;

        return $this;
    }

    /**
     * Get referrer
     *
     * @return integer 
     */
    public function getReferrer()
    {
        return $this->referrer;
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
