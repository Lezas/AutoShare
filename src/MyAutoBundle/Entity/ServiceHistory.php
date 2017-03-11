<?php

namespace MyAutoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Post
 *
 * @ORM\Table(name="serviceHistory")
 * @ORM\Entity(repositoryClass="MyAutoBundle\Repository\ServiceHistoryRepository")
 */
class ServiceHistory
{

    public function __construct()
    {
        $this->date = new \DateTime();
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
     * @ORM\Column(name="mileage", type="string", length=255)
     */
    private $mileage;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="text")
     */
    private $text;

    /**
     * @ORM\ManyToOne(targetEntity = "MyAutoBundle\Entity\Auto", inversedBy = "serviceHistory")
     * @ORM\JoinColumn(name = "auto_id", referencedColumnName = "id")
     * @var Auto
     */
    private $auto;

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
     * Set mileage
     *
     * @param string $mileage
     *
     * @return ServiceHistory
     */
    public function setMileage($mileage)
    {
        $this->mileage = $mileage;

        return $this;
    }

    /**
     * Get mileage
     *
     * @return string
     */
    public function getMileage()
    {
        return $this->mileage;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return ServiceHistory
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set text
     *
     * @param string $text
     *
     * @return ServiceHistory
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set auto
     *
     * @param \MyAutoBundle\Entity\Auto $auto
     *
     * @return ServiceHistory
     */
    public function setAuto(Auto $auto = null)
    {
        $this->auto = $auto;

        return $this;
    }

    /**
     * Get auto
     *
     * @return \MyAutoBundle\Entity\Auto
     */
    public function getAuto()
    {
        return $this->auto;
    }

}

