<?php

namespace Eighty\RefiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Prospectproperty
 *
 * @ORM\Table(name="prospectproperty")
 * @ORM\Entity
 */
class Prospectproperty
{
    /**
     * @var integer
     *
     * @ORM\Column(name="prospect_id", type="bigint", nullable=true)
     */
    private $prospectId;

    /**
     * @var integer
     *
     * @ORM\Column(name="sales_id", type="bigint", nullable=true)
     */
    private $salesId;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255, nullable=true)
     */
    private $status;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;



    /**
     * Set prospectId
     *
     * @param integer $prospectId
     * @return Prospectproperty
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
     * Set salesId
     *
     * @param integer $salesId
     * @return Prospectproperty
     */
    public function setSalesId($salesId)
    {
        $this->salesId = $salesId;

        return $this;
    }

    /**
     * Get salesId
     *
     * @return integer 
     */
    public function getSalesId()
    {
        return $this->salesId;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return Prospectproperty
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
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
