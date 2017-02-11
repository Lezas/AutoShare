<?php

namespace MyAutoBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 * @ORM\Entity(repositoryClass="MyAutoBundle\Repository\UserRepository")
 */
class User extends BaseUser
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="MyAutoBundle\Entity\Auto", mappedBy="user")
     * @var Auto[]|ArrayCollection
     */
    protected $Auto;

    /**
     * @ORM\ManyToMany(targetEntity="MyAutoBundle\Entity\Auto", mappedBy="user_favorite")
     * @ORM\JoinColumn(name="auto_id", referencedColumnName="id")
     * @ORM\JoinTable(name="user_favorites")
     */
    protected $favorites;

    /**
     * @ORM\ManyToMany(targetEntity="MyAutoBundle\Entity\Auto", mappedBy="user_like")
     * @ORM\JoinTable(name="user_likes")
     */
    protected $liked;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     */
    private $foto;

    public function __construct()
    {

        // your own logic
        $this->Auto = new ArrayCollection();
        $this->favorites = new ArrayCollection();
        $this->liked = new ArrayCollection();
        parent::__construct();
    }

    /**
     * Add auto
     *
     * @param \MyAutoBundle\Entity\Auto $auto
     *
     * @return User
     */
    public function addAuto(\MyAutoBundle\Entity\Auto $auto)
    {
        $this->Auto[] = $auto;

        return $this;
    }

    /**
     * Remove auto
     *
     * @param \MyAutoBundle\Entity\Auto $auto
     */
    public function removeAuto(\MyAutoBundle\Entity\Auto $auto)
    {
        $this->Auto->removeElement($auto);
    }

    /**
     * Get auto
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAuto()
    {
        return $this->Auto;
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

    public function addAutoToFavorites(Auto $auto)
    {
        $this->favorites[] = $auto;
    }

    public function removeAutoFromFavorites(Auto $auto)
    {
        $this->favorites->removeElement($auto);
    }

    public function isAutoFavorited(Auto $auto)
    {
        return $this->favorites->contains($auto);
    }


    public function addAutoToLiked(Auto $auto)
    {
        $this->liked[] = $auto;
    }

    public function getLikedAutos()
    {
        return $this->liked;
    }

    public function removeAutoFromLiked(Auto $auto)
    {
        $this->liked->removeElement($auto);
    }

    public function isAutoLiked(Auto $auto)
    {
        return $this->liked->contains($auto);
    }
}
