<?php

namespace Eighty\RefiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reportlist
 *
 * @ORM\Table(name="reportlist")
 * @ORM\Entity
 */
class Reportlist
{
    /**
     * @var integer
     *
     * @ORM\Column(name="client_id", type="bigint", nullable=true)
     */
    private $clientId;

    /**
     * @var integer
     *
     * @ORM\Column(name="transaction_id", type="bigint", nullable=true)
     */
    private $transactionId;
	
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
     * @var string
     *
     * @ORM\Column(name="calculator_values", type="text", nullable=true)
     */
    private $calculatorValues;
	
	/**
     * @var string
     *
     * @ORM\Column(name="hash", type="string", length=64, nullable=true)
     */
    private $hash;

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
     * @param integer $clientId
     * @return Reportlist
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;

        return $this;
    }

    /**
     * Get clientId
     *
     * @return integer 
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * Set transactionId
     *
     * @param integer $transactionId
     * @return Reportlist
     */
    public function setTransactionId($transactionId)
    {
        $this->transactionId = $transactionId;

        return $this;
    }

    /**
     * Get transactionId
     *
     * @return integer 
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }
	
	/**
     * Set note
     *
     * @param string $note
     * @return Reportlist
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
     * @return Reportlist
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
     * @return Reportlist
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
     * @return Reportlist
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
     * Set calculatorValues
     *
     * @param string $calculatorValues
     * @return Reportlist
     */
    public function setCalculatorValues($calculatorValues)
    {
        $this->calculatorValues = $calculatorValues;

        return $this;
    }

    /**
     * Get calculatorValues
     *
     * @return string 
     */
    public function getCalculatorValues()
    {
        return $this->calculatorValues;
    }
	
	/**
     * Set hash
     *
     * @param string $hash
     * @return Sectorlist
     */
    public function setHash($hash)
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * Get hash
     *
     * @return string 
     */
    public function getHash()
    {
        return $this->hash;
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
