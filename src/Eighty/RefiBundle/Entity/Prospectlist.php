<?php

namespace Eighty\RefiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Prospectlist
 *
 * @ORM\Table(name="prospectlist")
 * @ORM\Entity
 */
class Prospectlist
{
    /**
     * @var integer
     *
     * @ORM\Column(name="sectorlist_id", type="bigint", nullable=true)
     */
    private $sectorlistId;

    /**
     * @var integer
     *
     * @ORM\Column(name="prospect_id", type="bigint", nullable=true)
     */
    private $prospectId;
	
	/**
     * @var string
     *
     * @ORM\Column(name="note", type="text", nullable=true)
     */
    private $note;
	
	/**
     * @var \DateTime
     *
     * @ORM\Column(name="date_contacted", type="datetime", nullable=true)
     */
    private $dateContacted;
	
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_engaged", type="datetime", nullable=true)
     */
    private $dateEngaged;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="bigint", nullable=true)
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
     * Set sectorlistId
     *
     * @param integer $sectorlistId
     * @return Prospectlist
     */
    public function setSectorlistId($sectorlistId)
    {
        $this->sectorlistId = $sectorlistId;

        return $this;
    }

    /**
     * Get sectorlistId
     *
     * @return integer 
     */
    public function getSectorlistId()
    {
        return $this->sectorlistId;
    }

    /**
     * Set prospectId
     *
     * @param integer $prospectId
     * @return Prospectlist
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
     * Set note
     *
     * @param string $note
     * @return Prospectlist
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note
     *
     * @return string 
     */
    public function getNote()
    {
        return $this->note;
    }
	
	/**
     * Set dateContacted
     *
     * @param \DateTime $dateContacted
     * @return Prospectlist
     */
    public function setDateContacted($dateContacted)
    {
        $this->dateContacted = $dateContacted;

        return $this;
    }

    /**
     * Get dateContacted
     *
     * @return \DateTime 
     */
    public function getDateContacted()
    {
        return $this->dateContacted;
    }

    /**
     * Set dateEngaged
     *
     * @param \DateTime $dateEngaged
     * @return Prospectlist
     */
    public function setDateEngaged($dateEngaged)
    {
        $this->dateEngaged = $dateEngaged;

        return $this;
    }

    /**
     * Get dateEngaged
     *
     * @return \DateTime 
     */
    public function getDateEngaged()
    {
        return $this->dateEngaged;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return Prospectlist
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
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
