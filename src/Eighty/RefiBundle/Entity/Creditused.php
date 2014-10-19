<?php

namespace Eighty\RefiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Creditused
 *
 * @ORM\Table(name="creditused")
 * @ORM\Entity
 */
class Creditused
{
    /**
     * @var integer
     *
     * @ORM\Column(name="client_id", type="bigint", nullable=true)
     */
    private $clientId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=true)
     */
    private $date;

    /**
     * @var integer
     *
     * @ORM\Column(name="credit_used", type="integer", nullable=true)
     */
    private $creditUsed;

    /**
     * @var integer
     *
     * @ORM\Column(name="sms_used", type="integer", nullable=true)
     */
    private $smsUsed;

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
     * @return Creditused
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
     * Set date
     *
     * @param \DateTime $date
     * @return Creditused
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set creditUsed
     *
     * @param integer $creditUsed
     * @return Creditused
     */
    public function setCreditUsed($creditUsed)
    {
        $this->creditUsed = $creditUsed;

        return $this;
    }

    /**
     * Get creditUsed
     *
     * @return integer 
     */
    public function getCreditUsed()
    {
        return $this->creditUsed;
    }

    /**
     * Set smsUsed
     *
     * @param integer $smsUsed
     * @return Creditused
     */
    public function setSmsUsed($smsUsed)
    {
        $this->smsUsed = $smsUsed;

        return $this;
    }

    /**
     * Get smsUsed
     *
     * @return integer 
     */
    public function getSmsUsed()
    {
        return $this->smsUsed;
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
