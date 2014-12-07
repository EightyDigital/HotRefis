<?php

namespace Eighty\RefiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sectorlist
 *
 * @ORM\Table(name="sectorlist")
 * @ORM\Entity
 */
class Sectorlist
{
	/**
     * @var integer
     *
     * @ORM\Column(name="client_id", type="bigint")
     */
    private $clientId;
	
    /**
     * @var string
     *
     * @ORM\Column(name="sector_code", type="string", length=4, nullable=true)
     */
    private $sectorCode;

    /**
     * @var string
     *
     * @ORM\Column(name="dateadded", type="string", length=20, nullable=true)
     */
    private $dateadded;
	
	/**
     * @var integer
     *
     * @ORM\Column(name="validity", type="bigint")
     */
    private $validity;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

	
	/**
     * Set clientId
     *
     * @param tinyint $clientId
     * @return Sectorlist
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;

        return $this;
    }

    /**
     * Get clientId
     *
     * @return string 
     */
    public function getClientId()
    {
        return $this->clientId;
    }
	
    /**
     * Set sectorCode
     *
     * @param string $sectorCode
     * @return Sectorlist
     */
    public function setSectorCode($sectorCode)
    {
        $this->sectorCode = $sectorCode;

        return $this;
    }

    /**
     * Get sectorCode
     *
     * @return string 
     */
    public function getSectorCode()
    {
        return $this->sectorCode;
    }

    /**
     * Set dateadded
     *
     * @param string $dateadded
     * @return Sectorlist
     */
    public function setDateadded($dateadded)
    {
        $this->dateadded = $dateadded;

        return $this;
    }

    /**
     * Get dateadded
     *
     * @return string 
     */
    public function getDateadded()
    {
        return $this->dateadded;
    }
	
	/**
     * Set validity
     *
     * @param tinyint $validity
     * @return Sectorlist
     */
    public function setValidity($validity)
    {
        $this->validity = $validity;

        return $this;
    }

    /**
     * Get validity
     *
     * @return string 
     */
    public function getValidity()
    {
        return $this->validity;
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
