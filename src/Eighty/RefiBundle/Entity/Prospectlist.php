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
     * @ORM\Column(name="clientlist_id", type="bigint", nullable=true)
     */
    private $clientlistId;

    /**
     * @var integer
     *
     * @ORM\Column(name="prospect_id", type="bigint", nullable=true)
     */
    private $prospectId;
	
	/**
     * @var integer
     *
     * @ORM\Column(name="score", type="bigint", nullable=true)
     */
    private $score;

    /**
     * @var string
     *
     * @ORM\Column(name="note", type="text", nullable=true)
     */
    private $note;

    /**
     * @var boolean
     *
     * @ORM\Column(name="engaged", type="boolean", nullable=true)
     */
    private $engaged;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_engaged", type="datetime", nullable=true)
     */
    private $dateEngaged;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_assigned", type="datetime", nullable=true)
     */
    private $dateAssigned;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;



    /**
     * Set clientlistId
     *
     * @param integer $clientlistId
     * @return Prospectlist
     */
    public function setClientlistId($clientlistId)
    {
        $this->clientlistId = $clientlistId;

        return $this;
    }

    /**
     * Get clientlistId
     *
     * @return integer 
     */
    public function getClientlistId()
    {
        return $this->clientlistId;
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
     * Set score
     *
     * @param integer $score
     * @return Prospectlist
     */
    public function setScore($score)
    {
        $this->score = $score;

        return $this;
    }

    /**
     * Get score
     *
     * @return integer 
     */
    public function getScore()
    {
        return $this->score;
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
     * Set engaged
     *
     * @param boolean $engaged
     * @return Prospectlist
     */
    public function setEngaged($engaged)
    {
        $this->engaged = $engaged;

        return $this;
    }

    /**
     * Get engaged
     *
     * @return boolean 
     */
    public function getEngaged()
    {
        return $this->engaged;
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
     * Set dateAssigned
     *
     * @param \DateTime $dateAssigned
     * @return Prospectlist
     */
    public function setDateAssigned($dateAssigned)
    {
        $this->dateAssigned = $dateAssigned;

        return $this;
    }

    /**
     * Get dateAssigned
     *
     * @return \DateTime 
     */
    public function getDateAssigned()
    {
        return $this->dateAssigned;
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
