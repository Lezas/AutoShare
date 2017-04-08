<?php

namespace Application\Sonata\MediaBundle\Entity;

use CarShowBundle\Entity\Car;
use Sonata\MediaBundle\Entity\BaseMedia as BaseMedia;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Table(name="media__media")
 * @ORM\Entity()
 */
class Media extends BaseMedia
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Get id
     *
     * @return int $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @var Car
     *
     * @ORM\ManyToOne(targetEntity="CarShowBundle\Entity\Car", inversedBy="images")
     * @ORM\JoinColumn(name="car", referencedColumnName="id")
     */
    private $car;

    /**
     * @return Car
     */
    public function getCar()
    {
        return $this->car;
    }

    /**
     * @param Car $car
     */
    public function setCar($car)
    {
        $this->car = $car;
    }
}
