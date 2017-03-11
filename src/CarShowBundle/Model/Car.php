<?php

namespace CarShowBundle\Model;


abstract class Car implements CarInterface
{
    /**
     * @var string
     *
     */
    protected $brand;

    /**
     * @var string
     *
     */
    protected $model;

    /**
     * @var \DateTime
     *
     */
    protected $year;

    /**
     * @var string
     *
     */
    protected $bodyType;

    /**
     * @var string
     *
     */
    protected $powerTrain;

    /**
     * @var string
     *
     */
    protected $engineCapacity;

    /**
     * @var string
     *
     */
    protected $fuelType;

    /**
     * Set brand
     *
     * @param string $brand
     *
     * @return CarInterface
     */
    public function setBrand($brand)
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * Get brand
     *
     * @return string
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * Set model
     *
     * @param string $model
     *
     * @return CarInterface
     */
    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Get model
     *
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Set year
     *
     * @param \DateTime $year
     *
     * @return CarInterface
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return \DateTime
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set bodyType
     *
     * @param string $bodyType
     *
     * @return CarInterface
     */
    public function setBodyType($bodyType)
    {
        $this->bodyType = $bodyType;

        return $this;
    }

    /**
     * Get bodyType
     *
     * @return string
     */
    public function getBodyType()
    {
        return $this->bodyType;
    }

    /**
     * Set powerTrain
     *
     * @param string $powerTrain
     *
     * @return CarInterface
     */
    public function setPowerTrain($powerTrain)
    {
        $this->powerTrain = $powerTrain;

        return $this;
    }

    /**
     * Get powerTrain
     *
     * @return string
     */
    public function getPowerTrain()
    {
        return $this->powerTrain;
    }

    /**
     * Set engineCapacity
     *
     * @param string $engineCapacity
     *
     * @return CarInterface
     */
    public function setEngineCapacity($engineCapacity)
    {
        $this->engineCapacity = $engineCapacity;

        return $this;
    }

    /**
     * Get engineCapacity
     *
     * @return string
     */
    public function getEngineCapacity()
    {
        return $this->engineCapacity;
    }

    /**
     * Set fuelType
     *
     * @param string $fuelType
     *
     * @return CarInterface
     */
    public function setFuelType($fuelType)
    {
        $this->fuelType = $fuelType;

        return $this;
    }

    /**
     * Get fuelType
     *
     * @return string
     */
    public function getFuelType()
    {
        return $this->fuelType;
    }

}