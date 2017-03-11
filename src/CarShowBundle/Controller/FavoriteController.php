<?php

namespace CarShowBundle\Controller;

use CarShowBundle\Entity\Car;
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

        $carManager = $this->get('car_show.manager.car');

        /** @var Car $car */
        $car = $carManager->findCarById($carId);

        /** @var User $user */
        $user = $this->getUser();


        if (!$car || !$user || $car->isAutoFavorited($user)) {
            throw new NotFoundHttpException("Page not found");
        }

        $car->addUserToFavorites($user);
        $carManager->saveCar($car);

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

        $carManager = $this->get('car_show.manager.car');

        /** @var Car $car */
        $car = $carManager->findCarById($carId);

        /** @var User $user */
        $user = $this->getUser();

        if (!$car || !$user || !$car->isAutoFavorited($user)) {
            throw new NotFoundHttpException("Page not found");
        }

        $car->removeUserFromFavorites($user);
        $carManager->saveCar($car);

        $response['success'] = true;
        return new JsonResponse($response);

    }
}