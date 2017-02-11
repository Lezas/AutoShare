<?php

namespace MyAutoBundle\Controller;

use MyAutoBundle\Entity\Auto;
use MyAutoBundle\Entity\Post;
use MyAutoBundle\Entity\Thread;
use MyAutoBundle\Entity\User;
use MyAutoBundle\Form\Type\AutoType;
use MyAutoBundle\Form\Type\PostType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class FavoriteController
 * @package MyAutoBundle\Controller
 */
class FavoriteController extends Controller
{
    /**
     * @param Request $request
     * @Route("/add", name="add_to_favorites")
     * @return Response
     */
    public function addToFavoriteAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $carId = $request->request->get('carId');

        $em = $this->getDoctrine()->getEntityManager();

        $car = $em->getRepository('MyAutoBundle:Auto')->find($carId);

        /** @var User $user */
        $user = $this->getUser();


        if (!$car || !$user || $car->isAutoFavorited($user)) {
            throw new NotFoundHttpException("Page not found");
        }

        $car->addUserToFavorites($user);

        $em->persist($car);
        $em->flush();

        $response['success'] = true;
        return new JsonResponse($response);

    }

    /**
     * @param Request $request
     * @Route("/remove", name="remove_from_favorites")
     * @return Response
     */
    public function RemoveFromFavoriteAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $carId = $request->request->get('carId');

        $em = $this->getDoctrine()->getEntityManager();

        $car = $em->getRepository('MyAutoBundle:Auto')->find($carId);

        /** @var User $user */
        $user = $this->getUser();

        if (!$car || !$user || !$car->isAutoFavorited($user)) {
            throw new NotFoundHttpException("Page not found");
        }

        $car->removeUserFromFavorites($user);

        $em->persist($car);
        $em->flush();

        $response['success'] = true;
        return new JsonResponse($response);

    }
}