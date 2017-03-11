<?php

namespace CarShowBundle\Model;

use CarShowBundle\Event\CarEvent;
use CarShowBundle\Event\CarPersistEvent;
use CarShowBundle\Events;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

abstract class CarManager implements ManagerInterface
{
    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * Constructor.
     *
     * @param EventDispatcherInterface $dispatcher A dispatcher instance.
     */
    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @return CarInterface
     */
    public function createCar()
    {
        $class = $this->getClass();

        /** @var Car $car */
        $car = new $class;

        $event = new CarEvent($car);
        $this->dispatcher->dispatch(Events::CAR_CREATE, $event);

        return $car;
    }

    /**
     * Persists Question.
     *
     * @param CarInterface $car
     * @return bool
     */
    public function saveCar(CarInterface $car)
    {

        $event = new CarPersistEvent($car);
        $this->dispatcher->dispatch(Events::CAR_PRE_PERSIST, $event);

        if ($event->isPersistenceAborted()) {
            return false;
        }

        $this->doSaveCar($car);

        $event = new CarEvent($car);
        $this->dispatcher->dispatch(Events::CAR_POST_PERSIST, $event);

        return true;
    }

    /**
     * Performs the persistence of a comment.
     *
     * @param CarInterface $car
     * @return
     * @internal param Answer $answer
     * @internal param Question $question
     */
    abstract protected function doSaveCar(CarInterface $car);
}