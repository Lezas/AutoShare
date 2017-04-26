<?php

namespace MainBundle\Controller;

use MainBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class DefaultController
 * @package CarShowBundle\Controller
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="Dashboard")
     */
    public function indexAction(Request $request)
    {
        $cars = $this->get('car_show.manager.car')->findCarsBy(['private' => 0]);

        $popular = $this->get('car_show.manager.car')->findMostPopularCars();

        $newest = $this->get('car_show.manager.car')->findNewestCarts(5);

        $em = $this->get('doctrine.orm.entity_manager');

        $qb = $em->getRepository('CarShowBundle:Car')->createQueryBuilder('c')
            ->addSelect('c.createdAt as HIDDEN createdAt')
            ->addSelect('c.updatedAt as HIDDEN updatedAt')
            ->leftjoin('c.posts', 'p')
            ->addSelect('count(p.id) as HIDDEN postAmount ')
            ->leftJoin('c.images ', 'img')
            ->addSelect('count(img.id) as HIDDEN imgAmount')
            ->where('c.deleted != 1')
            ->andWhere('c.private != 1')
            ->addOrderBy('c.brand')
            ->addOrderBy('c.model')
            ->addOrderBy('c.year')
            ->groupBy('c.id');
        $query = $qb->getQuery();

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 12),/*limit per page*/
            array('defaultSortFieldName' => 'updatedAt', 'defaultSortDirection' => 'desc')
        );


        return $this->render('MainBundle:Default:index.html.twig', [
            'newestCars' => $newest,
            'popularCars' => $popular,
            'ownedAutos' => $cars,
            'user' => $this->getUser(),
            'pagination' => $pagination,

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
        $cars = $user->getCars();

        return $this->render('MainBundle:Default:garage.html.twig', [
            'ownedAutos' => $cars,
        ]);
    }
}
