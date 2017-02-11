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

        $car = $em->getRepository('MyAutoBundle:Auto')->find($carId);

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

        $car = $em->getRepository('MyAutoBundle:Auto')->find($carId);

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