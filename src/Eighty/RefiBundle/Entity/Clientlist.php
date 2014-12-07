<?php

namespace Eighty\RefiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Clientlist
 *
 * @ORM\Table(name="clientlist")
 * @ORM\Entity
 */
class Clientlist
{
    /**
     * @var integer
     *
     * @ORM\Column(name="client_id", type="bigint", nullable=true)
     */
    private $clientId;

    /**
     * @var string
     *
     * @ORM\Column(name="list_name", type="string", length=20, nullable=true)
     */
    private $listName;

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
     * @return Clientlist
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
     * Set listName
     *
     * @param string $listName
     * @return Clientlist
     */
    public function setListName($listName)
    {
        $this->listName = $listName;

        return $this;
    }

    /**
     * Get listName
     *
     * @return string 
     */
    public function getListName()
    {
        return $this->listName;
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
