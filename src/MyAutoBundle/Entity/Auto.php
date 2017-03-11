<?php

namespace MyAutoBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use MyAutoBundle\Entity\User;

/**
 * Auto
 *
 * @ORM\Table(name="auto")
 * @ORM\Entity(repositoryClass="MyAutoBundle\Repository\AutoRepository")
 * @ORM\EntityListeners({"MyAutoBundle\Entity\Listener\AutoListener"})
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
    private $powertrain;

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
     * @ORM\ManyToOne(targetEntity = "MyAutoBundle\Entity\User", inversedBy = "Auto")
     * @ORM\JoinColumn(name = "user_id", referencedColumnName = "id")
     * @var User
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="MyAutoBundle\Entity\Post", mappedBy="auto")
     * @var Post[]|ArrayCollection
     */
    protected $posts;

    /**
     * @ORM\OneToMany(targetEntity="MyAutoBundle\Entity\ServiceHistory", mappedBy="auto")
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
     * @ORM\ManyToMany(targetEntity="MyAutoBundle\Entity\User", inversedBy="favorites")
     * @ORM\JoinColumn(name="auto_id", referencedColumnName="id")
     * @ORM\JoinTable(name="user_favorites")
     * @var User[]|ArrayCollection
     */
    private $favoritedUsers;

    /**
     * @ORM\ManyToMany(targetEntity="MyAutoBundle\Entity\User", inversedBy="liked")
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
     * Set powertrain
     *
     * @param string $powertrain
     *
     * @return Auto
     */
    public function setPowertrain($powertrain)
    {
        $this->powertrain = $powertrain;

        return $this;
    }

    /**
     * Get powertrain
     *
     * @return string
     */
    public function getPowertrain()
    {
        return $this->powertrain;
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
     * @param \MyAutoBundle\Entity\User $user
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
     * @return \MyAutoBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Add post
     *
     * @param \MyAutoBundle\Entity\Post $post
     *
     * @return Auto
     */
    public function addPost(\MyAutoBundle\Entity\Post $post)
    {
        $this->posts[] = $post;

        return $this;
    }

    /**
     * Remove post
     *
     * @param \MyAutoBundle\Entity\Post $post
     */
    public function removePost(\MyAutoBundle\Entity\Post $post)
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
     * @param \MyAutoBundle\Entity\ServiceHistory $serviceHistory
     *
     * @return Auto
     */
    public function addServiceHistory(\MyAutoBundle\Entity\ServiceHistory $serviceHistory)
    {
        $this->serviceHistory[] = $serviceHistory;

        return $this;
    }

    /**
     * Remove serviceHistory
     *
     * @param \MyAutoBundle\Entity\ServiceHistory $serviceHistory
     */
    public function removeServiceHistory(\MyAutoBundle\Entity\ServiceHistory $serviceHistory)
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
    public function setPrivate( $private)
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

