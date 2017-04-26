<?php

namespace CarShowBundle\Event;

use CarShowBundle\Model\CarInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * An event that occurs related to a comment.
 *
 * @author Tim Nagel <tim@nagel.com.au>
 */
class CarEvent extends Event
{
    private $car;

    /**
     * Constructs an event.
     * @param CarInterface $car
     */
    public function __construct(CarInterface $car)
    {
        $this->car = $car;
    }

    /**
     * @return CarInterface
     */
    public function getCar()
    {
        return $this->car;
    }
}
