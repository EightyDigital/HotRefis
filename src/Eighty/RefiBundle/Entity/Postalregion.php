<?php

namespace Eighty\RefiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Postalregion
 *
 * @ORM\Table(name="postalregion")
 * @ORM\Entity
 */
class Postalregion
{
    /**
     * @var string
     *
     * @ORM\Column(name="region_type", type="string", length=10, nullable=true)
     */
    private $regionType;

    /**
     * @var string
     *
     * @ORM\Column(name="region_code", type="string", length=4, nullable=true)
     */
    private $regionCode;

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
     * @var float
     *
     * @ORM\Column(name="min_temp", type="float", precision=10, scale=0, nullable=true)
     */
    private $minTemp;

    /**
     * @var float
     *
     * @ORM\Column(name="max_temp", type="float", precision=10, scale=0, nullable=true)
     */
    private $maxTemp;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;



    /**
     * Set regionType
     *
     * @param string $regionType
     * @return Postalregion
     */
    public function setRegionType($regionType)
    {
        $this->regionType = $regionType;

        return $this;
    }

    /**
     * Get regionType
     *
     * @return string 
     */
    public function getRegionType()
    {
        return $this->regionType;
    }

    /**
     * Set regionCode
     *
     * @param string $regionCode
     * @return Postalregion
     */
    public function setRegionCode($regionCode)
    {
        $this->regionCode = $regionCode;

        return $this;
    }

    /**
     * Get regionCode
     *
     * @return string 
     */
    public function getRegionCode()
    {
        return $this->regionCode;
    }

    /**
     * Set longitude
     *
     * @param float $longitude
     * @return Postalregion
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
     * @return Postalregion
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
     * Set minTemp
     *
     * @param float $minTemp
     * @return Postalregion
     */
    public function setMinTemp($minTemp)
    {
        $this->minTemp = $minTemp;

        return $this;
    }

    /**
     * Get minTemp
     *
     * @return float 
     */
    public function getMinTemp()
    {
        return $this->minTemp;
    }

    /**
     * Set maxTemp
     *
     * @param float $maxTemp
     * @return Postalregion
     */
    public function setMaxTemp($maxTemp)
    {
        $this->maxTemp = $maxTemp;

        return $this;
    }

    /**
     * Get maxTemp
     *
     * @return float 
     */
    public function getMaxTemp()
    {
        return $this->maxTemp;
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
