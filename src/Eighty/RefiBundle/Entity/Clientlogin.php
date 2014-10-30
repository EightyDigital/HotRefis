<?php

namespace Eighty\RefiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Clientlogin
 *
 * @ORM\Table(name="clientlogin")
 * @ORM\Entity
 */
class Clientlogin
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
     * @ORM\Column(name="login_date", type="datetime", nullable=true)
     */
    private $loginDate;

    /**
     * @var string
     *
     * @ORM\Column(name="client_ip", type="string", length=100, nullable=true)
     */
    private $clientIp;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="logoff_date", type="datetime", nullable=true)
     */
    private $logoffDate;

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
     * @return Clientlogin
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
     * Set loginDate
     *
     * @param \DateTime $loginDate
     * @return Clientlogin
     */
    public function setLoginDate($loginDate)
    {
        $this->loginDate = $loginDate;

        return $this;
    }

    /**
     * Get loginDate
     *
     * @return \DateTime 
     */
    public function getLoginDate()
    {
        return $this->loginDate;
    }

    /**
     * Set clientIp
     *
     * @param string $clientIp
     * @return Clientlogin
     */
    public function setClientIp($clientIp)
    {
        $this->clientIp = $clientIp;

        return $this;
    }

    /**
     * Get clientIp
     *
     * @return string 
     */
    public function getClientIp()
    {
        return $this->clientIp;
    }

    /**
     * Set logoffDate
     *
     * @param \DateTime $logoffDate
     * @return Clientlogin
     */
    public function setLogoffDate($logoffDate)
    {
        $this->logoffDate = $logoffDate;

        return $this;
    }

    /**
     * Get logoffDate
     *
     * @return \DateTime 
     */
    public function getLogoffDate()
    {
        return $this->logoffDate;
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
