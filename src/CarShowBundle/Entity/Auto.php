<?php

namespace CarShowBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use MainBundle\Entity\User;

/**
 * Auto
 *
 * @ORM\Table(name="auto")
 * @ORM\Entity(repositoryClass="CarShowBundle\Repository\AutoRepository")
 * @ORM\EntityListeners({"CarShowBundle\Entity\Listener\AutoListener"})
 */
class Auto
{

    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->serviceHistory = new ArrayCollection();
        $this->favoritedUsers = new ArrayCollection();
        $this->likedUsers = new ArrayCollection();
    }

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="brand", type="string", length=255)
     */
    private $brand;

    /**
     * @var string
     *
     * @ORM\Column(name="model", type="string", length=255)
     */
    private $model;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="year", type="date")
     */
    private $year;

    /**
     * @var string
     *
     * @ORM\Column(name="bodyType", type="string", length=255)
     */
    private $bodyType;

    /**
     * @var string
     *
     * @ORM\Column(name="powertrain", type="string", length=255)
     */
    private $powerTrain;

    /**
     * @var string
     *
     * @ORM\Column(name="engineCapacity", type="string", length=255)
     */
    private $engineCapacity;

    /**
     * @var string
     *
     * @ORM\Column(name="fuelType", type="string", length=255, nullable=true)
     */
    private $fuelType;

    /**
     * @var string
     *
     * @ORM\Column(name="additionalInfo", type="text", nullable=true)
     */
    private $additionalInfo;

    /**
     * @ORM\ManyToOne(targetEntity = "MainBundle\Entity\User", inversedBy = "Auto")
     * @ORM\JoinColumn(name = "user_id", referencedColumnName = "id")
     * @var User
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="CarShowBundle\Entity\Post", mappedBy="auto")
     * @var Post[]|ArrayCollection
     */
    protected $posts;

    /**
     * @ORM\OneToMany(targetEntity="CarShowBundle\Entity\ServiceHistory", mappedBy="auto")
     * @var ServiceHistory[]|ArrayCollection
     */
    protected $serviceHistory;

    /**
     * @ORM\Column(type="string")
     *
     */
    private $foto;

    /**
     * @ORM\Column(type="boolean")
     *
     */
    private $private;

    /**
     * @ORM\ManyToMany(targetEntity="MainBundle\Entity\User", inversedBy="favorites")
     * @ORM\JoinColumn(name="auto_id", referencedColumnName="id")
     * @ORM\JoinTable(name="user_favorites")
     * @var User[]|ArrayCollection
     */
    private $favoritedUsers;

    /**
     * @ORM\ManyToMany(targetEntity="MainBundle\Entity\User", inversedBy="liked")
     * @ORM\JoinColumn(name="auto_id", referencedColumnName="id")
     * @ORM\JoinTable(name="user_likes")
     * @var User[]|ArrayCollection
     */
    private $likedUsers;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set brand
     *
     * @param string $brand
     *
     * @return Auto
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
     * @return Auto
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
     * @return Auto
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
     * @return Auto
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
     * @return Auto
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
     * @return Auto
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
     * @return Auto
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

    /**
     * Set additionalInfo
     *
     * @param string $additionalInfo
     *
     * @return Auto
     */
    public function setAdditionalInfo($additionalInfo)
    {
        $this->additionalInfo = $additionalInfo;

        return $this;
    }

    /**
     * Get additionalInfo
     *
     * @return string
     */
    public function getAdditionalInfo()
    {
        return $this->additionalInfo;
    }

    /**
     * Set user
     *
     * @param \MainBundle\Entity\User $user
     *
     * @return Auto
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \MainBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Add post
     *
     * @param \CarShowBundle\Entity\Post $post
     *
     * @return Auto
     */
    public function addPost(\CarShowBundle\Entity\Post $post)
    {
        $this->posts[] = $post;

        return $this;
    }

    /**
     * Remove post
     *
     * @param \CarShowBundle\Entity\Post $post
     */
    public function removePost(\CarShowBundle\Entity\Post $post)
    {
        $this->posts->removeElement($post);
    }

    /**
     * Get posts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPosts()
    {
        return $this->posts;
    }


    /**
     * Add Service History
     *
     * @param \CarShowBundle\Entity\ServiceHistory $serviceHistory
     *
     * @return Auto
     */
    public function addServiceHistory(\CarShowBundle\Entity\ServiceHistory $serviceHistory)
    {
        $this->serviceHistory[] = $serviceHistory;

        return $this;
    }

    /**
     * Remove serviceHistory
     *
     * @param \CarShowBundle\Entity\ServiceHistory $serviceHistory
     */
    public function removeServiceHistory(\CarShowBundle\Entity\ServiceHistory $serviceHistory)
    {
        $this->serviceHistory->removeElement($serviceHistory);
    }

    /**
     * Get service History
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getServiceHistory()
    {
        return $this->serviceHistory;
    }


    public function getFoto()
    {
        return $this->foto;
    }

    public function setFoto($foto)
    {
        $this->foto = $foto;

        return $this;
    }

    /**
     * Set private
     *
     * @param boolean $private
     *
     * @return Auto
     */
    public function setPrivate($private)
    {
        $this->private = $private;

        return $this;
    }

    /**
     * Is private
     *
     * @return boolean
     */
    public function isPrivate()
    {
        return $this->private;
    }

    public function addUserToFavorites(User $user)
    {
        $this->favoritedUsers[] = $user;
    }

    public function getFavoritedUsers()
    {
        return $this->favoritedUsers;
    }

    public function isAutoFavorited(User $user)
    {
        return $this->favoritedUsers->contains($user);
    }

    public function removeUserFromFavorites(User $user)
    {
        $this->favoritedUsers->removeElement($user);
    }

    public function addUserToLiked(User $user)
    {
        $this->likedUsers[] = $user;
    }

    public function getLikedUsers()
    {
        return $this->likedUsers;
    }

    public function getLikedUsersCount()
    {
        return $this->likedUsers->count();
    }

    public function isAutoLiked(User $user)
    {
        return $this->likedUsers->contains($user);
    }

    public function removeUserFromLiked(User $user)
    {
        $this->likedUsers->removeElement($user);
    }
}

