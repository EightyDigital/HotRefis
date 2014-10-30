<?php

namespace Eighty\RefiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Property
 *
 * @ORM\Table(name="property", indexes={@ORM\Index(name="propertyname", columns={"propertyname"}), @ORM\Index(name="propertyid", columns={"propertyid"}), @ORM\Index(name="propertykey", columns={"propertykey"}), @ORM\Index(name="postalcode", columns={"postalcode"})})
 * @ORM\Entity
 */
class Property
{
    /**
     * @var integer
     *
     * @ORM\Column(name="propertyid", type="bigint", nullable=true)
     */
    private $propertyid;

    /**
     * @var string
     *
     * @ORM\Column(name="propertyname", type="string", length=50, nullable=true)
     */
    private $propertyname;

    /**
     * @var string
     *
     * @ORM\Column(name="typecode", type="string", length=5, nullable=true)
     */
    private $typecode;

    /**
     * @var string
     *
     * @ORM\Column(name="typename", type="string", length=45, nullable=true)
     */
    private $typename;

    /**
     * @var string
     *
     * @ORM\Column(name="typegroup", type="string", length=45, nullable=true)
     */
    private $typegroup;

    /**
     * @var string
     *
     * @ORM\Column(name="developername", type="string", length=124, nullable=true)
     */
    private $developername;

    /**
     * @var string
     *
     * @ORM\Column(name="floors", type="string", length=30, nullable=true)
     */
    private $floors;

    /**
     * @var string
     *
     * @ORM\Column(name="tenurecode", type="string", length=5, nullable=true)
     */
    private $tenurecode;

    /**
     * @var string
     *
     * @ORM\Column(name="completedyear", type="string", length=10, nullable=true)
     */
    private $completedyear;

    /**
     * @var string
     *
     * @ORM\Column(name="totalunits", type="string", length=10, nullable=true)
     */
    private $totalunits;

    /**
     * @var string
     *
     * @ORM\Column(name="postalcode", type="string", length=6, nullable=true)
     */
    private $postalcode;

    /**
     * @var string
     *
     * @ORM\Column(name="streetnumber", type="string", length=45, nullable=true)
     */
    private $streetnumber;

    /**
     * @var string
     *
     * @ORM\Column(name="buildingname", type="string", length=7, nullable=true)
     */
    private $buildingname;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="latitude", type="text", nullable=true)
     */
    private $latitude;

    /**
     * @var string
     *
     * @ORM\Column(name="longitude", type="text", nullable=true)
     */
    private $longitude;

    /**
     * @var string
     *
     * @ORM\Column(name="districtcode", type="string", length=5, nullable=true)
     */
    private $districtcode;

    /**
     * @var string
     *
     * @ORM\Column(name="areacode", type="string", length=45, nullable=true)
     */
    private $areacode;

    /**
     * @var string
     *
     * @ORM\Column(name="regioncode", type="string", length=45, nullable=true)
     */
    private $regioncode;

    /**
     * @var string
     *
     * @ORM\Column(name="fulladdress", type="string", length=100, nullable=true)
     */
    private $fulladdress;

    /**
     * @var string
     *
     * @ORM\Column(name="streetname1", type="string", length=45, nullable=true)
     */
    private $streetname1;

    /**
     * @var string
     *
     * @ORM\Column(name="countrycode", type="string", length=45, nullable=true)
     */
    private $countrycode;

    /**
     * @var string
     *
     * @ORM\Column(name="estatename", type="string", length=45, nullable=true)
     */
    private $estatename;

    /**
     * @var string
     *
     * @ORM\Column(name="propertykey", type="string", length=45, nullable=true)
     */
    private $propertykey;

    /**
     * @var string
     *
     * @ORM\Column(name="facilitynames", type="string", length=300, nullable=true)
     */
    private $facilitynames;

    /**
     * @var string
     *
     * @ORM\Column(name="facilitycodes", type="string", length=300, nullable=true)
     */
    private $facilitycodes;

    /**
     * @var integer
     *
     * @ORM\Column(name="isprimarybuilding", type="integer", nullable=true)
     */
    private $isprimarybuilding;

    /**
     * @var string
     *
     * @ORM\Column(name="issingleunit", type="string", length=5, nullable=true)
     */
    private $issingleunit;

    /**
     * @var string
     *
     * @ORM\Column(name="existingfloors", type="string", length=1000, nullable=true)
     */
    private $existingfloors;

    /**
     * @var string
     *
     * @ORM\Column(name="existingstacks", type="string", length=1000, nullable=true)
     */
    private $existingstacks;

    /**
     * @var string
     *
     * @ORM\Column(name="estateid", type="string", length=45, nullable=true)
     */
    private $estateid;

    /**
     * @var string
     *
     * @ORM\Column(name="estatecode", type="string", length=45, nullable=true)
     */
    private $estatecode;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;



    /**
     * Set propertyid
     *
     * @param integer $propertyid
     * @return Property
     */
    public function setPropertyid($propertyid)
    {
        $this->propertyid = $propertyid;

        return $this;
    }

    /**
     * Get propertyid
     *
     * @return integer 
     */
    public function getPropertyid()
    {
        return $this->propertyid;
    }

    /**
     * Set propertyname
     *
     * @param string $propertyname
     * @return Property
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
     * Set typecode
     *
     * @param string $typecode
     * @return Property
     */
    public function setTypecode($typecode)
    {
        $this->typecode = $typecode;

        return $this;
    }

    /**
     * Get typecode
     *
     * @return string 
     */
    public function getTypecode()
    {
        return $this->typecode;
    }

    /**
     * Set typename
     *
     * @param string $typename
     * @return Property
     */
    public function setTypename($typename)
    {
        $this->typename = $typename;

        return $this;
    }

    /**
     * Get typename
     *
     * @return string 
     */
    public function getTypename()
    {
        return $this->typename;
    }

    /**
     * Set typegroup
     *
     * @param string $typegroup
     * @return Property
     */
    public function setTypegroup($typegroup)
    {
        $this->typegroup = $typegroup;

        return $this;
    }

    /**
     * Get typegroup
     *
     * @return string 
     */
    public function getTypegroup()
    {
        return $this->typegroup;
    }

    /**
     * Set developername
     *
     * @param string $developername
     * @return Property
     */
    public function setDevelopername($developername)
    {
        $this->developername = $developername;

        return $this;
    }

    /**
     * Get developername
     *
     * @return string 
     */
    public function getDevelopername()
    {
        return $this->developername;
    }

    /**
     * Set floors
     *
     * @param string $floors
     * @return Property
     */
    public function setFloors($floors)
    {
        $this->floors = $floors;

        return $this;
    }

    /**
     * Get floors
     *
     * @return string 
     */
    public function getFloors()
    {
        return $this->floors;
    }

    /**
     * Set tenurecode
     *
     * @param string $tenurecode
     * @return Property
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
     * Set completedyear
     *
     * @param string $completedyear
     * @return Property
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
     * Set totalunits
     *
     * @param string $totalunits
     * @return Property
     */
    public function setTotalunits($totalunits)
    {
        $this->totalunits = $totalunits;

        return $this;
    }

    /**
     * Get totalunits
     *
     * @return string 
     */
    public function getTotalunits()
    {
        return $this->totalunits;
    }

    /**
     * Set postalcode
     *
     * @param string $postalcode
     * @return Property
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
     * Set streetnumber
     *
     * @param string $streetnumber
     * @return Property
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
     * Set buildingname
     *
     * @param string $buildingname
     * @return Property
     */
    public function setBuildingname($buildingname)
    {
        $this->buildingname = $buildingname;

        return $this;
    }

    /**
     * Get buildingname
     *
     * @return string 
     */
    public function getBuildingname()
    {
        return $this->buildingname;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Property
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set latitude
     *
     * @param string $latitude
     * @return Property
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return string 
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param string $longitude
     * @return Property
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return string 
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set districtcode
     *
     * @param string $districtcode
     * @return Property
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
     * Set areacode
     *
     * @param string $areacode
     * @return Property
     */
    public function setAreacode($areacode)
    {
        $this->areacode = $areacode;

        return $this;
    }

    /**
     * Get areacode
     *
     * @return string 
     */
    public function getAreacode()
    {
        return $this->areacode;
    }

    /**
     * Set regioncode
     *
     * @param string $regioncode
     * @return Property
     */
    public function setRegioncode($regioncode)
    {
        $this->regioncode = $regioncode;

        return $this;
    }

    /**
     * Get regioncode
     *
     * @return string 
     */
    public function getRegioncode()
    {
        return $this->regioncode;
    }

    /**
     * Set fulladdress
     *
     * @param string $fulladdress
     * @return Property
     */
    public function setFulladdress($fulladdress)
    {
        $this->fulladdress = $fulladdress;

        return $this;
    }

    /**
     * Get fulladdress
     *
     * @return string 
     */
    public function getFulladdress()
    {
        return $this->fulladdress;
    }

    /**
     * Set streetname1
     *
     * @param string $streetname1
     * @return Property
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
     * Set countrycode
     *
     * @param string $countrycode
     * @return Property
     */
    public function setCountrycode($countrycode)
    {
        $this->countrycode = $countrycode;

        return $this;
    }

    /**
     * Get countrycode
     *
     * @return string 
     */
    public function getCountrycode()
    {
        return $this->countrycode;
    }

    /**
     * Set estatename
     *
     * @param string $estatename
     * @return Property
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
     * Set propertykey
     *
     * @param string $propertykey
     * @return Property
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
     * Set facilitynames
     *
     * @param string $facilitynames
     * @return Property
     */
    public function setFacilitynames($facilitynames)
    {
        $this->facilitynames = $facilitynames;

        return $this;
    }

    /**
     * Get facilitynames
     *
     * @return string 
     */
    public function getFacilitynames()
    {
        return $this->facilitynames;
    }

    /**
     * Set facilitycodes
     *
     * @param string $facilitycodes
     * @return Property
     */
    public function setFacilitycodes($facilitycodes)
    {
        $this->facilitycodes = $facilitycodes;

        return $this;
    }

    /**
     * Get facilitycodes
     *
     * @return string 
     */
    public function getFacilitycodes()
    {
        return $this->facilitycodes;
    }

    /**
     * Set isprimarybuilding
     *
     * @param integer $isprimarybuilding
     * @return Property
     */
    public function setIsprimarybuilding($isprimarybuilding)
    {
        $this->isprimarybuilding = $isprimarybuilding;

        return $this;
    }

    /**
     * Get isprimarybuilding
     *
     * @return integer 
     */
    public function getIsprimarybuilding()
    {
        return $this->isprimarybuilding;
    }

    /**
     * Set issingleunit
     *
     * @param string $issingleunit
     * @return Property
     */
    public function setIssingleunit($issingleunit)
    {
        $this->issingleunit = $issingleunit;

        return $this;
    }

    /**
     * Get issingleunit
     *
     * @return string 
     */
    public function getIssingleunit()
    {
        return $this->issingleunit;
    }

    /**
     * Set existingfloors
     *
     * @param string $existingfloors
     * @return Property
     */
    public function setExistingfloors($existingfloors)
    {
        $this->existingfloors = $existingfloors;

        return $this;
    }

    /**
     * Get existingfloors
     *
     * @return string 
     */
    public function getExistingfloors()
    {
        return $this->existingfloors;
    }

    /**
     * Set existingstacks
     *
     * @param string $existingstacks
     * @return Property
     */
    public function setExistingstacks($existingstacks)
    {
        $this->existingstacks = $existingstacks;

        return $this;
    }

    /**
     * Get existingstacks
     *
     * @return string 
     */
    public function getExistingstacks()
    {
        return $this->existingstacks;
    }

    /**
     * Set estateid
     *
     * @param string $estateid
     * @return Property
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
     * Set estatecode
     *
     * @param string $estatecode
     * @return Property
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}
