<?php

namespace Eighty\RefiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Transactions
 *
 * @ORM\Table(name="transactions", indexes={@ORM\Index(name="propertykey", columns={"propertykey"}), @ORM\Index(name="contractyear", columns={"contractyear"}), @ORM\Index(name="contractdate", columns={"contractdate"}), @ORM\Index(name="districtcode", columns={"districtcode"}), @ORM\Index(name="postalcode", columns={"postalcode"}), @ORM\Index(name="urakey", columns={"urakey"}), @ORM\Index(name="propertytypecode", columns={"propertytypecode"}), @ORM\Index(name="contractmonth", columns={"contractmonth"}), @ORM\Index(name="propertyname", columns={"propertyname"}), @ORM\Index(name="areafloorsqft", columns={"areafloorsqft", "propertyname"}), @ORM\Index(name="bedrooms", columns={"bedrooms"})})
 * @ORM\Entity
 */
class Transactions
{
    /**
     * @var string
     *
     * @ORM\Column(name="urakey", type="string", length=50, nullable=true)
     */
    private $urakey;

    /**
     * @var integer
     *
     * @ORM\Column(name="units", type="integer", nullable=true)
     */
    private $units;

    /**
     * @var string
     *
     * @ORM\Column(name="areafloorsqm", type="string", length=10, nullable=true)
     */
    private $areafloorsqm;

    /**
     * @var string
     *
     * @ORM\Column(name="areafloorsqft", type="string", length=10, nullable=true)
     */
    private $areafloorsqft;

    /**
     * @var string
     *
     * @ORM\Column(name="arealandsqm", type="string", length=10, nullable=true)
     */
    private $arealandsqm;

    /**
     * @var string
     *
     * @ORM\Column(name="arealandsqft", type="string", length=10, nullable=true)
     */
    private $arealandsqft;

    /**
     * @var string
     *
     * @ORM\Column(name="areatype", type="string", length=45, nullable=true)
     */
    private $areatype;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float", precision=10, scale=0, nullable=true)
     */
    private $price;

    /**
     * @var integer
     *
     * @ORM\Column(name="psmfloor", type="integer", nullable=true)
     */
    private $psmfloor;

    /**
     * @var integer
     *
     * @ORM\Column(name="psffloor", type="integer", nullable=true)
     */
    private $psffloor;

    /**
     * @var string
     *
     * @ORM\Column(name="psmland", type="string", length=10, nullable=true)
     */
    private $psmland;

    /**
     * @var string
     *
     * @ORM\Column(name="psfland", type="string", length=10, nullable=true)
     */
    private $psfland;

    /**
     * @var string
     *
     * @ORM\Column(name="contractdate", type="string", length=10, nullable=true)
     */
    private $contractdate;

    /**
     * @var integer
     *
     * @ORM\Column(name="contractyear", type="integer", nullable=true)
     */
    private $contractyear;

    /**
     * @var integer
     *
     * @ORM\Column(name="contractmonth", type="integer", nullable=true)
     */
    private $contractmonth;

    /**
     * @var integer
     *
     * @ORM\Column(name="contractquarter", type="integer", nullable=true)
     */
    private $contractquarter;

    /**
     * @var string
     *
     * @ORM\Column(name="propertytypetext", type="string", length=45, nullable=true)
     */
    private $propertytypetext;

    /**
     * @var string
     *
     * @ORM\Column(name="propertyname", type="string", length=60, nullable=true)
     */
    private $propertyname;

    /**
     * @var string
     *
     * @ORM\Column(name="propertytypecode", type="string", length=5, nullable=true)
     */
    private $propertytypecode;

    /**
     * @var string
     *
     * @ORM\Column(name="completedyear", type="string", length=4, nullable=true)
     */
    private $completedyear;

    /**
     * @var string
     *
     * @ORM\Column(name="saletype", type="string", length=45, nullable=true)
     */
    private $saletype;

    /**
     * @var string
     *
     * @ORM\Column(name="purchaser", type="string", length=45, nullable=true)
     */
    private $purchaser;

    /**
     * @var string
     *
     * @ORM\Column(name="districtcode", type="string", length=45, nullable=true)
     */
    private $districtcode;

    /**
     * @var string
     *
     * @ORM\Column(name="sector", type="string", length=5, nullable=true)
     */
    private $sector;

    /**
     * @var string
     *
     * @ORM\Column(name="postalcode", type="string", length=6, nullable=true)
     */
    private $postalcode;

    /**
     * @var string
     *
     * @ORM\Column(name="estateid", type="string", length=45, nullable=true)
     */
    private $estateid;

    /**
     * @var string
     *
     * @ORM\Column(name="estatename", type="string", length=45, nullable=true)
     */
    private $estatename;

    /**
     * @var string
     *
     * @ORM\Column(name="estatecode", type="string", length=45, nullable=true)
     */
    private $estatecode;

    /**
     * @var string
     *
     * @ORM\Column(name="streetname1", type="string", length=60, nullable=true)
     */
    private $streetname1;

    /**
     * @var string
     *
     * @ORM\Column(name="streetname2", type="string", length=60, nullable=true)
     */
    private $streetname2;

    /**
     * @var string
     *
     * @ORM\Column(name="streetnumber", type="string", length=45, nullable=true)
     */
    private $streetnumber;

    /**
     * @var string
     *
     * @ORM\Column(name="floor", type="string", length=10, nullable=true)
     */
    private $floor;

    /**
     * @var string
     *
     * @ORM\Column(name="stack", type="string", length=10, nullable=true)
     */
    private $stack;

    /**
     * @var integer
     *
     * @ORM\Column(name="enbloc", type="integer", nullable=true)
     */
    private $enbloc;

    /**
     * @var string
     *
     * @ORM\Column(name="tenurecode", type="string", length=5, nullable=true)
     */
    private $tenurecode;

    /**
     * @var string
     *
     * @ORM\Column(name="tenureyears", type="string", length=10, nullable=true)
     */
    private $tenureyears;

    /**
     * @var string
     *
     * @ORM\Column(name="tenureexpirydate", type="string", length=10, nullable=true)
     */
    private $tenureexpirydate;

    /**
     * @var integer
     *
     * @ORM\Column(name="bedrooms", type="integer", nullable=true)
     */
    private $bedrooms;

    /**
     * @var string
     *
     * @ORM\Column(name="bathrooms", type="string", length=5, nullable=true)
     */
    private $bathrooms;

    /**
     * @var string
     *
     * @ORM\Column(name="propertykey", type="string", length=45, nullable=true)
     */
    private $propertykey;

    /**
     * @var float
     *
     * @ORM\Column(name="longitude", type="float", precision=10, scale=0, nullable=true)
     */
    private $longitude;

    /**
     * @var float
     *
     * @ORM\Column(name="latitude", type="float", precision=10, scale=0, nullable=true)
     */
    private $latitude;

    /**
     * @var string
     *
     * @ORM\Column(name="unitnumber", type="string", length=10, nullable=true)
     */
    private $unitnumber;

    /**
     * @var string
     *
     * @ORM\Column(name="currencysign", type="string", length=3, nullable=true)
     */
    private $currencysign;

    /**
     * @var string
     *
     * @ORM\Column(name="currencycode", type="string", length=3, nullable=true)
     */
    private $currencycode;

    /**
     * @var string
     *
     * @ORM\Column(name="transactionkey", type="string", length=80, nullable=true)
     */
    private $transactionkey;

    /**
     * @var float
     *
     * @ORM\Column(name="newprice", type="float", precision=10, scale=0, nullable=true)
     */
    private $newprice;

    /**
     * @var string
     *
     * @ORM\Column(name="flatTypeName", type="string", length=40, nullable=true)
     */
    private $flattypename;

    /**
     * @var string
     *
     * @ORM\Column(name="flatModelCode", type="string", length=20, nullable=true)
     */
    private $flatmodelcode;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;



    /**
     * Set urakey
     *
     * @param string $urakey
     * @return Transactions
     */
    public function setUrakey($urakey)
    {
        $this->urakey = $urakey;

        return $this;
    }

    /**
     * Get urakey
     *
     * @return string 
     */
    public function getUrakey()
    {
        return $this->urakey;
    }

    /**
     * Set units
     *
     * @param integer $units
     * @return Transactions
     */
    public function setUnits($units)
    {
        $this->units = $units;

        return $this;
    }

    /**
     * Get units
     *
     * @return integer 
     */
    public function getUnits()
    {
        return $this->units;
    }

    /**
     * Set areafloorsqm
     *
     * @param string $areafloorsqm
     * @return Transactions
     */
    public function setAreafloorsqm($areafloorsqm)
    {
        $this->areafloorsqm = $areafloorsqm;

        return $this;
    }

    /**
     * Get areafloorsqm
     *
     * @return string 
     */
    public function getAreafloorsqm()
    {
        return $this->areafloorsqm;
    }

    /**
     * Set areafloorsqft
     *
     * @param string $areafloorsqft
     * @return Transactions
     */
    public function setAreafloorsqft($areafloorsqft)
    {
        $this->areafloorsqft = $areafloorsqft;

        return $this;
    }

    /**
     * Get areafloorsqft
     *
     * @return string 
     */
    public function getAreafloorsqft()
    {
        return $this->areafloorsqft;
    }

    /**
     * Set arealandsqm
     *
     * @param string $arealandsqm
     * @return Transactions
     */
    public function setArealandsqm($arealandsqm)
    {
        $this->arealandsqm = $arealandsqm;

        return $this;
    }

    /**
     * Get arealandsqm
     *
     * @return string 
     */
    public function getArealandsqm()
    {
        return $this->arealandsqm;
    }

    /**
     * Set arealandsqft
     *
     * @param string $arealandsqft
     * @return Transactions
     */
    public function setArealandsqft($arealandsqft)
    {
        $this->arealandsqft = $arealandsqft;

        return $this;
    }

    /**
     * Get arealandsqft
     *
     * @return string 
     */
    public function getArealandsqft()
    {
        return $this->arealandsqft;
    }

    /**
     * Set areatype
     *
     * @param string $areatype
     * @return Transactions
     */
    public function setAreatype($areatype)
    {
        $this->areatype = $areatype;

        return $this;
    }

    /**
     * Get areatype
     *
     * @return string 
     */
    public function getAreatype()
    {
        return $this->areatype;
    }

    /**
     * Set price
     *
     * @param float $price
     * @return Transactions
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float 
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set psmfloor
     *
     * @param integer $psmfloor
     * @return Transactions
     */
    public function setPsmfloor($psmfloor)
    {
        $this->psmfloor = $psmfloor;

        return $this;
    }

    /**
     * Get psmfloor
     *
     * @return integer 
     */
    public function getPsmfloor()
    {
        return $this->psmfloor;
    }

    /**
     * Set psffloor
     *
     * @param integer $psffloor
     * @return Transactions
     */
    public function setPsffloor($psffloor)
    {
        $this->psffloor = $psffloor;

        return $this;
    }

    /**
     * Get psffloor
     *
     * @return integer 
     */
    public function getPsffloor()
    {
        return $this->psffloor;
    }

    /**
     * Set psmland
     *
     * @param string $psmland
     * @return Transactions
     */
    public function setPsmland($psmland)
    {
        $this->psmland = $psmland;

        return $this;
    }

    /**
     * Get psmland
     *
     * @return string 
     */
    public function getPsmland()
    {
        return $this->psmland;
    }

    /**
     * Set psfland
     *
     * @param string $psfland
     * @return Transactions
     */
    public function setPsfland($psfland)
    {
        $this->psfland = $psfland;

        return $this;
    }

    /**
     * Get psfland
     *
     * @return string 
     */
    public function getPsfland()
    {
        return $this->psfland;
    }

    /**
     * Set contractdate
     *
     * @param string $contractdate
     * @return Transactions
     */
    public function setContractdate($contractdate)
    {
        $this->contractdate = $contractdate;

        return $this;
    }

    /**
     * Get contractdate
     *
     * @return string 
     */
    public function getContractdate()
    {
        return $this->contractdate;
    }

    /**
     * Set contractyear
     *
     * @param integer $contractyear
     * @return Transactions
     */
    public function setContractyear($contractyear)
    {
        $this->contractyear = $contractyear;

        return $this;
    }

    /**
     * Get contractyear
     *
     * @return integer 
     */
    public function getContractyear()
    {
        return $this->contractyear;
    }

    /**
     * Set contractmonth
     *
     * @param integer $contractmonth
     * @return Transactions
     */
    public function setContractmonth($contractmonth)
    {
        $this->contractmonth = $contractmonth;

        return $this;
    }

    /**
     * Get contractmonth
     *
     * @return integer 
     */
    public function getContractmonth()
    {
        return $this->contractmonth;
    }

    /**
     * Set contractquarter
     *
     * @param integer $contractquarter
     * @return Transactions
     */
    public function setContractquarter($contractquarter)
    {
        $this->contractquarter = $contractquarter;

        return $this;
    }

    /**
     * Get contractquarter
     *
     * @return integer 
     */
    public function getContractquarter()
    {
        return $this->contractquarter;
    }

    /**
     * Set propertytypetext
     *
     * @param string $propertytypetext
     * @return Transactions
     */
    public function setPropertytypetext($propertytypetext)
    {
        $this->propertytypetext = $propertytypetext;

        return $this;
    }

    /**
     * Get propertytypetext
     *
     * @return string 
     */
    public function getPropertytypetext()
    {
        return $this->propertytypetext;
    }

    /**
     * Set propertyname
     *
     * @param string $propertyname
     * @return Transactions
     */
    public function setPropertyname($propertyname)
    {
        $this->propertyname = $propertyname;

        return $this;
    }

    /**
     * Get propertyname
     *
     * @return string 
     */
    public function getPropertyname()
    {
        return $this->propertyname;
    }

    /**
     * Set propertytypecode
     *
     * @param string $propertytypecode
     * @return Transactions
     */
    public function setPropertytypecode($propertytypecode)
    {
        $this->propertytypecode = $propertytypecode;

        return $this;
    }

    /**
     * Get propertytypecode
     *
     * @return string 
     */
    public function getPropertytypecode()
    {
        return $this->propertytypecode;
    }

    /**
     * Set completedyear
     *
     * @param string $completedyear
     * @return Transactions
     */
    public function setCompletedyear($completedyear)
    {
        $this->completedyear = $completedyear;

        return $this;
    }

    /**
     * Get completedyear
     *
     * @return string 
     */
    public function getCompletedyear()
    {
        return $this->completedyear;
    }

    /**
     * Set saletype
     *
     * @param string $saletype
     * @return Transactions
     */
    public function setSaletype($saletype)
    {
        $this->saletype = $saletype;

        return $this;
    }

    /**
     * Get saletype
     *
     * @return string 
     */
    public function getSaletype()
    {
        return $this->saletype;
    }

    /**
     * Set purchaser
     *
     * @param string $purchaser
     * @return Transactions
     */
    public function setPurchaser($purchaser)
    {
        $this->purchaser = $purchaser;

        return $this;
    }

    /**
     * Get purchaser
     *
     * @return string 
     */
    public function getPurchaser()
    {
        return $this->purchaser;
    }

    /**
     * Set districtcode
     *
     * @param string $districtcode
     * @return Transactions
     */
    public function setDistrictcode($districtcode)
    {
        $this->districtcode = $districtcode;

        return $this;
    }

    /**
     * Get districtcode
     *
     * @return string 
     */
    public function getDistrictcode()
    {
        return $this->districtcode;
    }

    /**
     * Set sector
     *
     * @param string $sector
     * @return Transactions
     */
    public function setSector($sector)
    {
        $this->sector = $sector;

        return $this;
    }

    /**
     * Get sector
     *
     * @return string 
     */
    public function getSector()
    {
        return $this->sector;
    }

    /**
     * Set postalcode
     *
     * @param string $postalcode
     * @return Transactions
     */
    public function setPostalcode($postalcode)
    {
        $this->postalcode = $postalcode;

        return $this;
    }

    /**
     * Get postalcode
     *
     * @return string 
     */
    public function getPostalcode()
    {
        return $this->postalcode;
    }

    /**
     * Set estateid
     *
     * @param string $estateid
     * @return Transactions
     */
    public function setEstateid($estateid)
    {
        $this->estateid = $estateid;

        return $this;
    }

    /**
     * Get estateid
     *
     * @return string 
     */
    public function getEstateid()
    {
        return $this->estateid;
    }

    /**
     * Set estatename
     *
     * @param string $estatename
     * @return Transactions
     */
    public function setEstatename($estatename)
    {
        $this->estatename = $estatename;

        return $this;
    }

    /**
     * Get estatename
     *
     * @return string 
     */
    public function getEstatename()
    {
        return $this->estatename;
    }

    /**
     * Set estatecode
     *
     * @param string $estatecode
     * @return Transactions
     */
    public function setEstatecode($estatecode)
    {
        $this->estatecode = $estatecode;

        return $this;
    }

    /**
     * Get estatecode
     *
     * @return string 
     */
    public function getEstatecode()
    {
        return $this->estatecode;
    }

    /**
     * Set streetname1
     *
     * @param string $streetname1
     * @return Transactions
     */
    public function setStreetname1($streetname1)
    {
        $this->streetname1 = $streetname1;

        return $this;
    }

    /**
     * Get streetname1
     *
     * @return string 
     */
    public function getStreetname1()
    {
        return $this->streetname1;
    }

    /**
     * Set streetname2
     *
     * @param string $streetname2
     * @return Transactions
     */
    public function setStreetname2($streetname2)
    {
        $this->streetname2 = $streetname2;

        return $this;
    }

    /**
     * Get streetname2
     *
     * @return string 
     */
    public function getStreetname2()
    {
        return $this->streetname2;
    }

    /**
     * Set streetnumber
     *
     * @param string $streetnumber
     * @return Transactions
     */
    public function setStreetnumber($streetnumber)
    {
        $this->streetnumber = $streetnumber;

        return $this;
    }

    /**
     * Get streetnumber
     *
     * @return string 
     */
    public function getStreetnumber()
    {
        return $this->streetnumber;
    }

    /**
     * Set floor
     *
     * @param string $floor
     * @return Transactions
     */
    public function setFloor($floor)
    {
        $this->floor = $floor;

        return $this;
    }

    /**
     * Get floor
     *
     * @return string 
     */
    public function getFloor()
    {
        return $this->floor;
    }

    /**
     * Set stack
     *
     * @param string $stack
     * @return Transactions
     */
    public function setStack($stack)
    {
        $this->stack = $stack;

        return $this;
    }

    /**
     * Get stack
     *
     * @return string 
     */
    public function getStack()
    {
        return $this->stack;
    }

    /**
     * Set enbloc
     *
     * @param integer $enbloc
     * @return Transactions
     */
    public function setEnbloc($enbloc)
    {
        $this->enbloc = $enbloc;

        return $this;
    }

    /**
     * Get enbloc
     *
     * @return integer 
     */
    public function getEnbloc()
    {
        return $this->enbloc;
    }

    /**
     * Set tenurecode
     *
     * @param string $tenurecode
     * @return Transactions
     */
    public function setTenurecode($tenurecode)
    {
        $this->tenurecode = $tenurecode;

        return $this;
    }

    /**
     * Get tenurecode
     *
     * @return string 
     */
    public function getTenurecode()
    {
        return $this->tenurecode;
    }

    /**
     * Set tenureyears
     *
     * @param string $tenureyears
     * @return Transactions
     */
    public function setTenureyears($tenureyears)
    {
        $this->tenureyears = $tenureyears;

        return $this;
    }

    /**
     * Get tenureyears
     *
     * @return string 
     */
    public function getTenureyears()
    {
        return $this->tenureyears;
    }

    /**
     * Set tenureexpirydate
     *
     * @param string $tenureexpirydate
     * @return Transactions
     */
    public function setTenureexpirydate($tenureexpirydate)
    {
        $this->tenureexpirydate = $tenureexpirydate;

        return $this;
    }

    /**
     * Get tenureexpirydate
     *
     * @return string 
     */
    public function getTenureexpirydate()
    {
        return $this->tenureexpirydate;
    }

    /**
     * Set bedrooms
     *
     * @param integer $bedrooms
     * @return Transactions
     */
    public function setBedrooms($bedrooms)
    {
        $this->bedrooms = $bedrooms;

        return $this;
    }

    /**
     * Get bedrooms
     *
     * @return integer 
     */
    public function getBedrooms()
    {
        return $this->bedrooms;
    }

    /**
     * Set bathrooms
     *
     * @param string $bathrooms
     * @return Transactions
     */
    public function setBathrooms($bathrooms)
    {
        $this->bathrooms = $bathrooms;

        return $this;
    }

    /**
     * Get bathrooms
     *
     * @return string 
     */
    public function getBathrooms()
    {
        return $this->bathrooms;
    }

    /**
     * Set propertykey
     *
     * @param string $propertykey
     * @return Transactions
     */
    public function setPropertykey($propertykey)
    {
        $this->propertykey = $propertykey;

        return $this;
    }

    /**
     * Get propertykey
     *
     * @return string 
     */
    public function getPropertykey()
    {
        return $this->propertykey;
    }

    /**
     * Set longitude
     *
     * @param float $longitude
     * @return Transactions
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return float 
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set latitude
     *
     * @param float $latitude
     * @return Transactions
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return float 
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set unitnumber
     *
     * @param string $unitnumber
     * @return Transactions
     */
    public function setUnitnumber($unitnumber)
    {
        $this->unitnumber = $unitnumber;

        return $this;
    }

    /**
     * Get unitnumber
     *
     * @return string 
     */
    public function getUnitnumber()
    {
        return $this->unitnumber;
    }

    /**
     * Set currencysign
     *
     * @param string $currencysign
     * @return Transactions
     */
    public function setCurrencysign($currencysign)
    {
        $this->currencysign = $currencysign;

        return $this;
    }

    /**
     * Get currencysign
     *
     * @return string 
     */
    public function getCurrencysign()
    {
        return $this->currencysign;
    }

    /**
     * Set currencycode
     *
     * @param string $currencycode
     * @return Transactions
     */
    public function setCurrencycode($currencycode)
    {
        $this->currencycode = $currencycode;

        return $this;
    }

    /**
     * Get currencycode
     *
     * @return string 
     */
    public function getCurrencycode()
    {
        return $this->currencycode;
    }

    /**
     * Set transactionkey
     *
     * @param string $transactionkey
     * @return Transactions
     */
    public function setTransactionkey($transactionkey)
    {
        $this->transactionkey = $transactionkey;

        return $this;
    }

    /**
     * Get transactionkey
     *
     * @return string 
     */
    public function getTransactionkey()
    {
        return $this->transactionkey;
    }

    /**
     * Set newprice
     *
     * @param float $newprice
     * @return Transactions
     */
    public function setNewprice($newprice)
    {
        $this->newprice = $newprice;

        return $this;
    }

    /**
     * Get newprice
     *
     * @return float 
     */
    public function getNewprice()
    {
        return $this->newprice;
    }

    /**
     * Set flattypename
     *
     * @param string $flattypename
     * @return Transactions
     */
    public function setFlattypename($flattypename)
    {
        $this->flattypename = $flattypename;

        return $this;
    }

    /**
     * Get flattypename
     *
     * @return string 
     */
    public function getFlattypename()
    {
        return $this->flattypename;
    }

    /**
     * Set flatmodelcode
     *
     * @param string $flatmodelcode
     * @return Transactions
     */
    public function setFlatmodelcode($flatmodelcode)
    {
        $this->flatmodelcode = $flatmodelcode;

        return $this;
    }

    /**
     * Get flatmodelcode
     *
     * @return string 
     */
    public function getFlatmodelcode()
    {
        return $this->flatmodelcode;
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
