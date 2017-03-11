<?php
/**
 * Created by Lezas.
 * Date: 2017-03-11
 * Time: 18:31
 */

namespace CarShowBundle\Model;


interface CarInterface
{
    /**
     * Set brand
     *
     * @param string $brand
     *
     * @return CarInterface
     */
    public function setBrand($brand);

    /**
     * Get brand
     *
     * @return string
     */
    public function getBrand();

    /**
     * Set model
     *
     * @param string $model
     *
     * @return CarInterface
     */
    public function setModel($model);

    /**
     * Get model
     *
     * @return string
     */
    public function getModel();

    /**
     * Set year
     *
     * @param \DateTime $year
     *
     * @return CarInterface
     */
    public function setYear($year);

    /**
     * Get year
     *
     * @return \DateTime
     */
    public function getYear();

    /**
     * Set bodyType
     *
     * @param string $bodyType
     *
     * @return CarInterface
     */
    public function setBodyType($bodyType);

    /**
     * Get bodyType
     *
     * @return string
     */
    public function getBodyType();

    /**
     * Set powerTrain
     *
     * @param string $powerTrain
     *
     * @return CarInterface
     */
    public function setPowerTrain($powerTrain);

    /**
     * Get powerTrain
     *
     * @return string
     */
    public function getPowerTrain();

    /**
     * Set engineCapacity
     *
     * @param string $engineCapacity
     *
     * @return CarInterface
     */
    public function setEngineCapacity($engineCapacity);

    /**
     * Get engineCapacity
     *
     * @return string
     */
    public function getEngineCapacity();

    /**
     * Set fuelType
     *
     * @param string $fuelType
     *
     * @return CarInterface
     */
    public function setFuelType($fuelType);
    /**
     * Get fuelType
     *
     * @return string
     */
    public function getFuelType();
}