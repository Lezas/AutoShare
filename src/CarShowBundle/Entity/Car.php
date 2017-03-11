<?php

namespace CarShowBundle\Entity;

use CarShowBundle\Model\Car as BaseCar;
use CarShowBundle\Model\CarInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use MainBundle\Entity\User;

/**
 * Auto
 *
 * @ORM\Table(name="cars")
 * @ORM\Entity()
 * @ORM\EntityListeners({"CarShowBundle\Entity\Listener\AutoListener"})
 */
class Car extends BaseCar
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
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="brand", type="string", length=255)
     */
    protected $brand;

    /**
     * @var string
     *
     * @ORM\Column(name="model", type="string", length=255)
     */
    protected $model;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="year", type="date")
     */
    protected $year;

    /**
     * @var string
     *
     * @ORM\Column(name="bodyType", type="string", length=255)
     */
    protected $bodyType;

    /**
     * @var string
     *
     * @ORM\Column(name="powertrain", type="string", length=255)
     */
    protected $powerTrain;

    /**
     * @var string
     *
     * @ORM\Column(name="engineCapacity", type="string", length=255)
     */
    protected $engineCapacity;

    /**
     * @var string
     *
     * @ORM\Column(name="fuelType", type="string", length=255, nullable=true)
     */
    protected $fuelType;

    /**
     * @var string
     *
     * @ORM\Column(name="additionalInfo", type="text", nullable=true)
     */
    protected $additionalInfo;

    /**
     * @ORM\ManyToOne(targetEntity = "MainBundle\Entity\User", inversedBy = "car")
     * @ORM\JoinColumn(name = "user_id", referencedColumnName = "id")
     * @var User
     */
    protected $user;

    /**
     * @ORM\OneToMany(targetEntity="CarShowBundle\Entity\Post", mappedBy="car")
     * @var Post[]|ArrayCollection
     */
    protected $posts;

    /**
     * @ORM\OneToMany(targetEntity="CarShowBundle\Entity\ServiceHistory", mappedBy="car")
     * @var ServiceHistory[]|ArrayCollection
     */
    protected $serviceHistory;

    /**
     * @ORM\Column(type="string")
     *
     */
    protected $foto;

    /**
     * @ORM\Column(type="boolean")
     *
     */
    protected $private;

    /**
     * @ORM\ManyToMany(targetEntity="MainBundle\Entity\User", inversedBy="favorites")
     * @ORM\JoinColumn(name="car_id", referencedColumnName="id")
     * @ORM\JoinTable(name="user_favorites")
     * @var User[]|ArrayCollection
     */
    protected $favoritedUsers;

    /**
     * @ORM\ManyToMany(targetEntity="MainBundle\Entity\User", inversedBy="liked")
     * @ORM\JoinColumn(name="car_id", referencedColumnName="id")
     * @ORM\JoinTable(name="user_likes")
     * @var User[]|ArrayCollection
     */
    protected $likedUsers;

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
     * Set additionalInfo
     *
     * @param string $additionalInfo
     *
     * @return CarInterface
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
     * @return CarInterface
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
     * @return CarInterface
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
     * @return CarInterface
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
     * @return CarInterface
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

