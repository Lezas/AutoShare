<?php

namespace MainBundle\Controller;

use MainBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class DefaultController
 * @package CarShowBundle\Controller
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="Dashboard")
     */
    public function indexAction()
    {
        $autoRepository = $this->getDoctrine()->getManager()->getRepository('CarShowBundle:Auto');

        $cars = $autoRepository->findBy(['private' => 0]);

        return $this->render('MainBundle:Default:index.html.twig', [
            'ownedAutos' => $cars,
            'user' => $this->getUser(),
        ]);
    }


    /**
     * @Route("/garage", name="garage")
     */
    public function userCarListAction()
    {
        /**
         * @var $user User
         */
        $user = $this->getUser();
        $cars = $user->getAuto();

        return $this->render('MainBundle:Default:garage.html.twig', [
            'ownedAutos' => $cars,
        ]);
    }
}
