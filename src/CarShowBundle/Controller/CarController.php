<?php

namespace CarShowBundle\Controller;

use CarShowBundle\Entity\Car;
use CarShowBundle\Form\Type\AutoType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CarController extends Controller
{
    /**
     * @param Request $request
     * @Route("/car/new", name="auto_new_car")
     * @return Response
     */
    public function newAutoAction(Request $request)
    {
        $carManager = $this->get('car_show.manager.car');

        /** @var Car $car */
        $car = $carManager->createCar();

        $user = $this->getUser();
        $form = $this->createForm(AutoType::class, $car, ['user' => $user]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();

            $car->setUser($user);

            /** @var File $foto */
            $foto = $car->getFoto();

            // Generate a unique name for the file before saving it
            $fileName = md5(uniqid()) . '.' . $foto->guessExtension();

            // Move the file to the directory where brochures are stored
            $foto->move(
                $this->getParameter('foto_directory'),
                $fileName
            );

            $car->setFoto($fileName);

            $carManager->saveCar($car);

            $this->addFlash(
                'notice',
                'Your expense has been saved!'
            );

            return $this->redirectToRoute('car_show_get_car', ['id' => $car->getId()]);

        }

        return $this->render('@CarShow/Default/newAuto.html.twig', [
            'form' => $form->createView(),
            'title' => 'New Auto'
        ]);
    }

    /**
     * @param Request $request
     * @param null $id
     * @Route("/car/{id}/edit", name="auto_car_edit")
     * @return Response
     */
    public function editAutoAction($id = null, Request $request)
    {
        $user = $this->getUser();

        $carManager = $this->get('car_show.manager.car');
        /** @var Car $car */
        $car = $carManager->findCarById($id);

        if (null == $car) {
            throw new NotFoundHttpException(sprintf("Sorry, no car was found"));
        }

        $fotoBeforeEdit = $car->getFoto();

        if ($fotoBeforeEdit != null) {
            $car->setFoto(
                new File($this->getParameter('foto_directory') . '/' . $auto->getFoto())
            );
        }

        $form = $this->createForm(AutoType::class, $car, ['user' => $user]);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $user = $this->getUser();

            $car->setUser($user);

            /** @var File $foto */
            $foto = $auto->getFoto();

            if ($foto != null) {

                // Generate a unique name for the file before saving it
                $fileName = md5(uniqid()) . '.' . $foto->guessExtension();

                // Move the file to the directory where brochures are stored
                $foto->move(
                    $this->getParameter('foto_directory'),
                    $fileName
                );

                $auto->setFoto($fileName);
            } else {
                $auto->setFoto($fotoBeforeEdit);
            }

            $carManager->saveCar($car);

            $this->addFlash(
                'notice',
                'Your auto info has been saved!'
            );
        }

        return $this->render('@CarShow/Default/newAuto.html.twig', [
            'form' => $form->createView(),
            'title' => 'Edit Auto Information'
        ]);
    }

    /**
     * @param Request $request
     * @param null $id
     * @Route("/car/{id}", name="car_show_get_car")
     * @return Response
     *
     */
    public function selectedAutoAction($id = null, Request $request)
    {
        /** @var Car $car */
        $car = $this->get('car_show.manager.car')->findCarById($id);
        $posts = $car->getPosts();

        return $this->render('@CarShow/Default/autoBlog.html.twig', [
            'posts' => $posts,
            'auto' => $car,
        ]);
    }

}