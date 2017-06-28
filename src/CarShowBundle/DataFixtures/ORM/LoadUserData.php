<?php

namespace CarShowBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use MainBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Created by Lezas.
 * Date: 2017-06-28
 * Time: 20:09
 */
class LoadUserData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface $container
     */
    private $container;

    public function load(ObjectManager $manager)
    {
        $userManager = $this->container->get('fos_user.user_manager');
        // Create our user and set details
        $user = $userManager->createUser();
        $user->setUsername('test');
        $user->setEmail('test@test.com');
        $user->setPlainPassword('test');
        $user->setEnabled(true);
        $user->setRoles(array('ROLE_ADMIN'));

        $userManager->updateUser($user, true);

        $this->addReference('admin-user', $user);
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function getOrder()
    {
        return 1;
    }

}