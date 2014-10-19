<?php

namespace Eighty\RefiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Clientoffer
 *
 * @ORM\Table(name="clientoffer")
 * @ORM\Entity
 */
class Clientoffer
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
     * @ORM\Column(name="prospect_id", type="bigint", nullable=true)
     */
    private $prospectId;

    /**
     * @var string
     *
     * @ORM\Column(name="offer_params", type="text", nullable=true)
     */
    private $offerParams;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=20, nullable=true)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="response_params", type="text", nullable=true)
     */
    private $responseParams;

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
     * @return Clientoffer
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
     * Set prospectId
     *
     * @param integer $prospectId
     * @return Clientoffer
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
     * Set offerParams
     *
     * @param string $offerParams
     * @return Clientoffer
     */
    public function setOfferParams($offerParams)
    {
        $this->offerParams = $offerParams;

        return $this;
    }

    /**
     * Get offerParams
     *
     * @return string 
     */
    public function getOfferParams()
    {
        return $this->offerParams;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return Clientoffer
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
     * Set responseParams
     *
     * @param string $responseParams
     * @return Clientoffer
     */
    public function setResponseParams($responseParams)
    {
        $this->responseParams = $responseParams;

        return $this;
    }

    /**
     * Get responseParams
     *
     * @return string 
     */
    public function getResponseParams()
    {
        return $this->responseParams;
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
