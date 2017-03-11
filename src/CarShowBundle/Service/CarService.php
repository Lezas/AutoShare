<?php

namespace CarShowBundle\Service;
use Doctrine\ORM\EntityManager;
use CarShowBundle\Entity\Car;
use MainBundle\Entity\User;

class CarService
{
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
    public function getFavorites(User $user)
    {
        $qb = $this->em->createQueryBuilder();

        $qb->select('a')
            ->from(Car::class, 'a')
            ->leftJoin('a.favoritedUsers', 'j')
            ->where('j.id = :id')
            ->setParameter('id', $user->getId());

        return $qb->getQuery()->getResult();

    }
}