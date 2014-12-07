<?php

namespace Eighty\RefiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Package
 *
 * @ORM\Table(name="package")
 * @ORM\Entity
 */
class Package
{
    /**
     * @var string
     *
     * @ORM\Column(name="package_name", type="string", length=20, nullable=true)
     */
    private $packageName;

    /**
     * @var string
     *
     * @ORM\Column(name="package_desc", type="text", nullable=true)
     */
    private $packageDesc;

    /**
     * @var integer
     *
     * @ORM\Column(name="no_sms_monthly", type="integer", nullable=true)
     */
    private $noSmsMonthly;

    /**
     * @var integer
     *
     * @ORM\Column(name="no_sms_daily", type="integer", nullable=true)
     */
    private $noSmsDaily;

    /**
     * @var integer
     *
     * @ORM\Column(name="no_credits", type="integer", nullable=true)
     */
    private $noCredits;

    /**
     * @var boolean
     *
     * @ORM\Column(name="valid", type="boolean", nullable=true)
     */
    private $valid;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;



    /**
     * Set packageName
     *
     * @param string $packageName
     * @return Package
     */
    public function setPackageName($packageName)
    {
        $this->packageName = $packageName;

        return $this;
    }

    /**
     * Get packageName
     *
     * @return string 
     */
    public function getPackageName()
    {
        return $this->packageName;
    }

    /**
     * Set packageDesc
     *
     * @param string $packageDesc
     * @return Package
     */
    public function setPackageDesc($packageDesc)
    {
        $this->packageDesc = $packageDesc;

        return $this;
    }

    /**
     * Get packageDesc
     *
     * @return string 
     */
    public function getPackageDesc()
    {
        return $this->packageDesc;
    }

    /**
     * Set noSmsMonthly
     *
     * @param integer $noSmsMonthly
     * @return Package
     */
    public function setNoSmsMonthly($noSmsMonthly)
    {
        $this->noSmsMonthly = $noSmsMonthly;

        return $this;
    }

    /**
     * Get noSmsMonthly
     *
     * @return integer 
     */
    public function getNoSmsMonthly()
    {
        return $this->noSmsMonthly;
    }

    /**
     * Set noSmsDaily
     *
     * @param integer $noSmsDaily
     * @return Package
     */
    public function setNoSmsDaily($noSmsDaily)
    {
        $this->noSmsDaily = $noSmsDaily;

        return $this;
    }

    /**
     * Get noSmsDaily
     *
     * @return integer 
     */
    public function getNoSmsDaily()
    {
        return $this->noSmsDaily;
    }

    /**
     * Set noCredits
     *
     * @param integer $noCredits
     * @return Package
     */
    public function setNoCredits($noCredits)
    {
        $this->noCredits = $noCredits;

        return $this;
    }

    /**
     * Get noCredits
     *
     * @return integer 
     */
    public function getNoCredits()
    {
        return $this->noCredits;
    }

    /**
     * Set valid
     *
     * @param boolean $valid
     * @return Package
     */
    public function setValid($valid)
    {
        $this->valid = $valid;

        return $this;
    }

    /**
     * Get valid
     *
     * @return boolean 
     */
    public function getValid()
    {
        return $this->valid;
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
