<?php
/**
 * Created by Lezas.
 * Date: 2017-06-28
 * Time: 20:38
 */

namespace CarShowBundle\DataFixtures\ORM;


use CarShowBundle\Entity\Car;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadCarsData extends AbstractFixture implements OrderedFixtureInterface
{
    public function getOrder()
    {
        return 2;
    }

    public function load(ObjectManager $manager)
    {
        $car = new Car();
        $car->setUser($this->getReference('admin-user'));
        $car->setBodyType('Avant');
        $car->setBrand('Tesla');
        $car->setModel('model X');
        $car->setFuelType('Electric');
        $car->setYear(new \DateTime('2016-04-04'));
        $car->setPowerTrain('AWD');
        $car->setEngineCapacity('0');
        $car->setPower('200');
        $car->setPrivate(false);
        $car->setDeleted(false);
        $car->setCreatedAt(new \DateTime('now'));
        $car->setUpdatedAt(new \DateTime('now'));

        $manager->persist($car);
        $manager->flush();
    }
}