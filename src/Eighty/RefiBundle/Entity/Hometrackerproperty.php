<?php

namespace Eighty\RefiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Hometrackerproperty
 *
 * @ORM\Table(name="hometrackerproperty")
 * @ORM\Entity
 */
class Hometrackerproperty
{
    /**
     * @var integer
     *
     * @ORM\Column(name="hometracker_id", type="bigint", nullable=true)
     */
    private $hometrackerId;

    /**
     * @var integer
     *
     * @ORM\Column(name="property_id", type="bigint", nullable=true)
     */
    private $propertyId;

    /**
     * @var string
     *
     * @ORM\Column(name="propertyname", type="string", length=255, nullable=true)
     */
    private $propertyname;

    /**
     * @var string
     *
     * @ORM\Column(name="unit_number", type="string", length=10, nullable=true)
     */
    private $unitNumber;

    /**
     * @var integer
     *
     * @ORM\Column(name="sma_alert", type="integer", nullable=true)
     */
    private $smaAlert;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="registered_date", type="datetime", nullable=true)
     */
    private $registeredDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;



    /**
     * Set hometrackerId
     *
     * @param integer $hometrackerId
     * @return Hometrackerproperty
     */
    public function setHometrackerId($hometrackerId)
    {
        $this->hometrackerId = $hometrackerId;

        return $this;
    }

    /**
     * Get hometrackerId
     *
     * @return integer 
     */
    public function getHometrackerId()
    {
        return $this->hometrackerId;
    }

    /**
     * Set propertyId
     *
     * @param integer $propertyId
     * @return Hometrackerproperty
     */
    public function setPropertyId($propertyId)
    {
        $this->propertyId = $propertyId;

        return $this;
    }

    /**
     * Get propertyId
     *
     * @return integer 
     */
    public function getPropertyId()
    {
        return $this->propertyId;
    }

    /**
     * Set propertyname
     *
     * @param string $propertyname
     * @return Hometrackerproperty
     */
    public function setPropertyname($propertyname)
    {
        $this->propertyname = $propertyname;

        return $this;
    }

    /**
     * Get propertyname
     *
     * @return string 
     */
    public function getPropertyname()
    {
        return $this->propertyname;
    }

    /**
     * Set unitNumber
     *
     * @param string $unitNumber
     * @return Hometrackerproperty
     */
    public function setUnitNumber($unitNumber)
    {
        $this->unitNumber = $unitNumber;

        return $this;
    }

    /**
     * Get unitNumber
     *
     * @return string 
     */
    public function getUnitNumber()
    {
        return $this->unitNumber;
    }

    /**
     * Set smaAlert
     *
     * @param integer $smaAlert
     * @return Hometrackerproperty
     */
    public function setSmaAlert($smaAlert)
    {
        $this->smaAlert = $smaAlert;

        return $this;
    }

    /**
     * Get smaAlert
     *
     * @return integer 
     */
    public function getSmaAlert()
    {
        return $this->smaAlert;
    }

    /**
     * Set registeredDate
     *
     * @param \DateTime $registeredDate
     * @return Hometrackerproperty
     */
    public function setRegisteredDate($registeredDate)
    {
        $this->registeredDate = $registeredDate;

        return $this;
    }

    /**
     * Get registeredDate
     *
     * @return \DateTime 
     */
    public function getRegisteredDate()
    {
        return $this->registeredDate;
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
