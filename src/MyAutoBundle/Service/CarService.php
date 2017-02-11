<?php

namespace MyAutoBundle\Service;
use Doctrine\ORM\EntityManager;
use MyAutoBundle\Entity\Auto;
use MyAutoBundle\Entity\User;

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
            ->from(Auto::class, 'a')
            ->leftJoin('a.favoritedUsers', 'j')
            ->where('j.id = :id')
            ->setParameter('id', $user->getId());

        return $qb->getQuery()->getResult();

    }
}