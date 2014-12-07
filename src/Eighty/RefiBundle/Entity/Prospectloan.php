<?php

namespace Eighty\RefiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Prospectloan
 *
 * @ORM\Table(name="prospectloan")
 * @ORM\Entity
 */
class Prospectloan
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
     * @ORM\Column(name="transaction_id", type="bigint", nullable=true)
     */
    private $transactionId;

    /**
     * @var float
     *
     * @ORM\Column(name="loan_amount", type="float", precision=10, scale=0, nullable=true)
     */
    private $loanAmount;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="loan_date", type="datetime", nullable=true)
     */
    private $loanDate;

    /**
     * @var string
     *
     * @ORM\Column(name="loan_bank", type="string", length=255, nullable=true)
     */
    private $loanBank;

    /**
     * @var integer
     *
     * @ORM\Column(name="loan_term", type="integer", nullable=true)
     */
    private $loanTerm;

    /**
     * @var float
     *
     * @ORM\Column(name="total_price", type="float", precision=10, scale=0, nullable=true)
     */
    private $totalPrice;

    /**
     * @var float
     *
     * @ORM\Column(name="interest_rate", type="float", precision=10, scale=0, nullable=true)
     */
    private $interestRate;

    /**
     * @var float
     *
     * @ORM\Column(name="other_debt", type="float", precision=10, scale=0, nullable=true)
     */
    private $otherDebt;

    /**
     * @var boolean
     *
     * @ORM\Column(name="self_declared", type="boolean", nullable=true)
     */
    private $selfDeclared;

    /**
     * @var boolean
     *
     * @ORM\Column(name="valid", type="boolean", nullable=true)
     */
    private $valid;
	
	/**
     * @var integer
     *
     * @ORM\Column(name="ltv", type="integer", nullable=true)
     */
    private $ltv;

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
     * @return Prospectloan
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
     * Set transactionId
     *
     * @param integer $transactionId
     * @return Prospectloan
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
     * Set loanAmount
     *
     * @param float $loanAmount
     * @return Prospectloan
     */
    public function setLoanAmount($loanAmount)
    {
        $this->loanAmount = $loanAmount;

        return $this;
    }

    /**
     * Get loanAmount
     *
     * @return float 
     */
    public function getLoanAmount()
    {
        return $this->loanAmount;
    }

    /**
     * Set loanDate
     *
     * @param \DateTime $loanDate
     * @return Prospectloan
     */
    public function setLoanDate($loanDate)
    {
        $this->loanDate = $loanDate;

        return $this;
    }

    /**
     * Get loanDate
     *
     * @return \DateTime 
     */
    public function getLoanDate()
    {
        return $this->loanDate;
    }

    /**
     * Set loanBank
     *
     * @param string $loanBank
     * @return Prospectloan
     */
    public function setLoanBank($loanBank)
    {
        $this->loanBank = $loanBank;

        return $this;
    }

    /**
     * Get loanBank
     *
     * @return string 
     */
    public function getLoanBank()
    {
        return $this->loanBank;
    }

    /**
     * Set loanTerm
     *
     * @param integer $loanTerm
     * @return Prospectloan
     */
    public function setLoanTerm($loanTerm)
    {
        $this->loanTerm = $loanTerm;

        return $this;
    }

    /**
     * Get loanTerm
     *
     * @return integer 
     */
    public function getLoanTerm()
    {
        return $this->loanTerm;
    }

    /**
     * Set totalPrice
     *
     * @param float $totalPrice
     * @return Prospectloan
     */
    public function setTotalPrice($totalPrice)
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    /**
     * Get totalPrice
     *
     * @return float 
     */
    public function getTotalPrice()
    {
        return $this->totalPrice;
    }

    /**
     * Set interestRate
     *
     * @param float $interestRate
     * @return Prospectloan
     */
    public function setInterestRate($interestRate)
    {
        $this->interestRate = $interestRate;

        return $this;
    }

    /**
     * Get interestRate
     *
     * @return float 
     */
    public function getInterestRate()
    {
        return $this->interestRate;
    }

    /**
     * Set otherDebt
     *
     * @param float $otherDebt
     * @return Prospectloan
     */
    public function setOtherDebt($otherDebt)
    {
        $this->otherDebt = $otherDebt;

        return $this;
    }

    /**
     * Get otherDebt
     *
     * @return float 
     */
    public function getOtherDebt()
    {
        return $this->otherDebt;
    }

    /**
     * Set selfDeclared
     *
     * @param boolean $selfDeclared
     * @return Prospectloan
     */
    public function setSelfDeclared($selfDeclared)
    {
        $this->selfDeclared = $selfDeclared;

        return $this;
    }

    /**
     * Get selfDeclared
     *
     * @return boolean 
     */
    public function getSelfDeclared()
    {
        return $this->selfDeclared;
    }

    /**
     * Set valid
     *
     * @param boolean $valid
     * @return Prospectloan
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
     * Set ltv
     *
     * @param integer $ltv
     * @return Prospectloan
     */
    public function setLtv($ltv)
    {
        $this->ltv = $ltv;

        return $this;
    }

    /**
     * Get ltv
     *
     * @return integer 
     */
    public function getLtv()
    {
        return $this->ltv;
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
