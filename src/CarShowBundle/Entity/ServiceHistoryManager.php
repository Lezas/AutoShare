<?php
/**
 * Created by PhpStorm.
 * User: pkupe
 * Date: 2017-02-14
 * Time: 19:03
 */

namespace CarShowBundle\Entity;

use CarShowBundle\Model\CarInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use CarShowBundle\Model\ServiceHistoryManager as BaseServiceHistoryManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ServiceHistoryManager extends BaseServiceHistoryManager
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var EntityRepository
     */
    protected $repository;

    /**
     * @var string
     */
    protected $class;

    /**
     * Constructor.
     *
     * @param EventDispatcherInterface $dispatcher
     * @param \Doctrine\ORM\EntityManager $em
     */
    public function __construct(EventDispatcherInterface $dispatcher, EntityManager $em)
    {
        parent::__construct($dispatcher);

        $this->em = $em;
        $this->repository = $em->getRepository(Car::class);

        $metadata = $em->getClassMetadata(Car::class);

        $this->class = $metadata->name;
    }

    /**
     * Finds one comment thread by the given criteria
     *
     * @param  array           $criteria
     * @return CarInterface
     */
    public function findCarBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }

    /**
     * {@inheritDoc}
     */
    public function findCarsBy(array $criteria)
    {
        return $this->repository->findBy($criteria);
    }

    /**
     * @param  string          $id
     * @return CarInterface
     *
     */
    public function findCarById($id)
    {
        return $this->findCarBy(array('id' => $id));
    }

    /**
     * Finds all Cars.
     *
     * @return array of Car
     */
    public function findAllCars()
    {
        return $this->repository->findAll();
    }

    /**
     * @return string
     **/
    public function getClass()
    {
        return $this->class;
    }


    /**
     * Saves Car
     *
     * @param ServiceHistory $SH
     */
    protected function doSaveServiceHistory(ServiceHistory $SH)
    {
        $this->em->persist($SH);
        $this->em->flush();
    }

    public function delete(ServiceHistory $serviceHistory)
    {
        $this->em->remove($serviceHistory);
        $this->em->flush();
    }


}