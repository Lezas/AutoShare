<?php

namespace CarShowBundle\Model;

use CarShowBundle\Entity\ServiceHistory;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

abstract class ServiceHistoryManager implements ManagerInterface
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
     * @return ServiceHistory
     */
    public function createServiceHistory()
    {
        $class = $this->getClass();
        $SH = new $class;


        return $SH;
    }

    /**
     * Persists Question.
     *
     * @param ServiceHistory $SH
     * @return bool
     */
    public function saveServiceHistory(ServiceHistory $SH)
    {
        $this->doSaveServiceHistory($SH);

        return true;
    }

    /**
     * Performs the persistence of a comment.
     *
     * @param ServiceHistory $SH
     * @return
     */
    abstract protected function doSaveServiceHistory(ServiceHistory $SH);
}