<?php

namespace CarShowBundle\Controller;

use MainBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class FavoriteController
 * @package CarShowBundle\Controller
 */
class LikeController extends Controller
{
    /**
     * @param Request $request
     * @Route("/like", name="like_this")
     * @return Response
     */
    public function likeAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $carId = $request->request->get('carId');

        $em = $this->getDoctrine()->getEntityManager();

        $car = $em->getRepository('CarShowBundle:Auto')->find($carId);

        /** @var User $user */
        $user = $this->getUser();


        if (!$car || !$user || $car->isAutoLiked($user)) {
            throw new NotFoundHttpException("Page not found");
        }

        $car->addUserToLiked($user);


        $em->persist($car);
        $em->flush();

        $response['count'] = $car->getLikedUsersCount();
        return new JsonResponse($response);

    }

    /**
     * @param Request $request
     * @Route("/unlike", name="unlike_this")
     * @return Response
     */
    public function unlikeAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $carId = $request->request->get('carId');

        $em = $this->getDoctrine()->getEntityManager();

        $car = $em->getRepository('CarShowBundle:Auto')->find($carId);

        /** @var User $user */
        $user = $this->getUser();

        if (!$car || !$user || !$car->isAutoLiked($user)) {
            throw new NotFoundHttpException("Page not found");
        }

        $car->removeUserFromLiked($user);

        $em->persist($car);
        $em->flush();

        $response['count'] = $car->getLikedUsersCount();
        return new JsonResponse($response);

    }
}