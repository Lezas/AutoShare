<?php

namespace CarShowBundle\Document;

use ONGR\ElasticsearchBundle\Annotation as ES;
use ONGR\ElasticsearchBundle\Collection\Collection;


/**
 * @ES\Document(type="car")
 */
class CarProfileDocument
{
    /**
     * @var string
     *
     * @ES\Id()
     */
    public $id;

    /**
     * @var string
     *
     * @ES\Property(name="car_id", type="text")
     */
    public $carId;

    /**
     * @var ContentMetaObject
     *
     * @ES\Embedded(class="CarShowBundle:PostDocument", multiple=true)
     */
    private $posts;

    /**
     * @var string
     *
     * @ES\Property(name="brand", type="string")
     */
    public $brand;

    /**
     * @var string
     *
     * @ES\Property(name="model", type="string")
     */
    public $model;

    /**
     * @var string
     *
     * @ES\Property(name="year", type="string")
     */
    public $year;

    /**
     * @var string
     *
     * @ES\Property(name="bodyType", type="string")
     */
    public $bodyType;

    /**
     * @var string
     *
     * @ES\Property(name="powerTrain", type="string")
     */
    public $powerTrain;

    /**
     * @var string
     *
     * @ES\Property(name="engineCapacity", type="string")
     */
    public $engineCapacity;

    /**
     * @var string
     *
     * @ES\Property(name="fuelType", type="string")
     */
    public $fuelType;

    /**
     * @var string
     *
     * @ES\Property(name="additionalInfo", type="string")
     */
    public $additionalInfo;

    /**
     * @var string
     *
     * @ES\Property(name="createdAt", type="string")
     */
    public $createdAt;

    /**
     * @var string
     *
     * @ES\Property(name="power", type="string")
     */
    public $power;

    /**
     * @var string
     *
     * @ES\Property(name="owner", type="string")
     */
    public $owner;

    public function __construct()
    {
        $this->posts = new Collection();
    }

    /**
     * @return string
     */
    public function getPower()
    {
        return $this->power;
    }

    /**
     * @param string $power
     */
    public function setPower($power)
    {
        $this->power = $power;
    }

    /**
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param string $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return string
     */
    public function getAdditionalInfo()
    {
        return $this->additionalInfo;
    }

    /**
     * @param string $additionalInfo
     */
    public function setAdditionalInfo($additionalInfo)
    {
        $this->additionalInfo = $additionalInfo;
    }

    /**
     * @return string
     */
    public function getFuelType()
    {
        return $this->fuelType;
    }

    /**
     * @param string $fuelType
     */
    public function setFuelType($fuelType)
    {
        $this->fuelType = $fuelType;
    }

    /**
     * @return string
     */
    public function getEngineCapacity()
    {
        return $this->engineCapacity;
    }

    /**
     * @param string $engineCapacity
     */
    public function setEngineCapacity($engineCapacity)
    {
        $this->engineCapacity = $engineCapacity;
    }

    /**
     * @return string
     */
    public function getPowerTrain()
    {
        return $this->powerTrain;
    }

    /**
     * @param string $powerTrain
     */
    public function setPowerTrain($powerTrain)
    {
        $this->powerTrain = $powerTrain;
    }

    /**
     * @return string
     */
    public function getBodyType()
    {
        return $this->bodyType;
    }

    /**
     * @param string $bodyType
     */
    public function setBodyType($bodyType)
    {
        $this->bodyType = $bodyType;
    }

    /**
     * @return string
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @param string $year
     */
    public function setYear($year)
    {
        $this->year = $year;
    }

    /**
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param string $model
     */
    public function setModel($model)
    {
        $this->model = $model;
    }

    /**
     * @return string
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * @param string $brand
     */
    public function setBrand($brand)
    {
        $this->brand = $brand;
    }

    /**
     * @return string
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @param string $owner
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;
    }

    /**
     * @return string
     */
    public function getCarId()
    {
        return $this->carId;
    }

    /**
     * @param string $carId
     */
    public function setCarId($carId)
    {
        $this->carId = $carId;
    }

    /**
     * @return ContentMetaObject
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * @param ContentMetaObject $posts
     */
    public function setPosts($posts)
    {
        $this->posts = $posts;
    }

    /**
     * Adds variant to the collection.
     *
     * @param PostDocument $postDocument
     * @return $this
     */
    public function addPost(PostDocument $postDocument)
    {
        $this->posts[] = $postDocument;

        return $this;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }
}