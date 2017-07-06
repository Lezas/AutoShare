<?php

namespace CarShowBundle\Controller;

use CarShowBundle\Entity\Car;
use MainBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
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
     * @Security("has_role('ROLE_USER')")
     * @return Response
     */
    public function likeAction(Request $request)
    {
        $carId = $request->request->get('carId');

        $carManager = $this->get('car_show.manager.car');

        /** @var Car $car */
        $car = $carManager->findCarById($carId);

        /** @var User $user */
        $user = $this->getUser();

        if (!$car || !$user || $car->isAutoLiked($user)) {
            throw new NotFoundHttpException("Page not found");
        }

        $car->addUserToLiked($user);

        $carManager->saveCar($car);

        $response['count'] = $car->getLikedUsersCount();
        return new JsonResponse($response);

    }

    /**
     * @param Request $request
     * @Route("/unlike", name="unlike_this")
     * @Security("has_role('ROLE_USER')")
     * @return Response
     */
    public function unlikeAction(Request $request)
    {
        $carId = $request->request->get('carId');

        $carManager = $this->get('car_show.manager.car');

        /** @var Car $car */
        $car = $carManager->findCarById($carId);

        /** @var User $user */
        $user = $this->getUser();

        if (!$car || !$user || !$car->isAutoLiked($user)) {
            throw new NotFoundHttpException("Page not found");
        }

        $car->removeUserFromLiked($user);

        $carManager->saveCar($car);

        $response['count'] = $car->getLikedUsersCount();
        return new JsonResponse($response);
    }
}