<?php

namespace CarShowBundle\Controller;

use Application\Sonata\MediaBundle\Entity\Media;
use CarShowBundle\Entity\Car;
use CarShowBundle\Form\Type\AutoType;
use CarShowBundle\Form\Type\PictureSelectType;
use CarShowBundle\Form\Type\PicturesType;
use Doctrine\Common\Collections\ArrayCollection;
use Sonata\MediaBundle\Entity\MediaManager;
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

            $carManager->saveCar($car);

            $mediaManager = $this->get('sonata.media.manager.media');
            $fotoData = $form->get('foto')->getData();
            $ImagemimeTypes = array('image/jpeg', 'image/png');

            $media = new Media();
            $media->setContext('default');
            $media->setBinaryContent($fotoData);
            if (in_array($fotoData->getMimeType(), $ImagemimeTypes)) {
                $media->setProviderName('sonata.media.provider.image');
                $car->addImage($media);
                $media->setCar($car);
                $mediaManager->save($media);
            }
            $car->setMainPhoto($media);

            $carManager->saveCar($car);

            $this->addFlash(
                'notice',
                'Your car has been saved!'
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

        $form = $this->createForm(AutoType::class, $car, ['user' => $user]);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $user = $this->getUser();

            $car->setUser($user);

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

    /**
     * @param Request $request
     * @param null $id
     * @Route("/car/{id}/addPictures", name="car_show_add_pictures")
     * @return Response
     *
     */
    public function addPictureAction($id = null, Request $request)
    {
        /** @var Car $car */
        $car = $this->get('car_show.manager.car')->findCarById($id);
        $posts = $car->getPosts();

        $pictures = new ArrayCollection();

        $form = $this->createForm(PicturesType::class, $pictures);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            // $data is a simply array with your form fields
            // like "query" and "category" as defined above.

            $mediaManager = $this->get('sonata.media.manager.media');

            $data = $form->get('images')->getData();
            $ImagemimeTypes = array('image/jpeg', 'image/png');

            foreach ($data as $datum) {
                $media = new Media();
                $media->setContext('default');
                $media->setBinaryContent($datum);
                if (in_array($data[0]->getMimeType(), $ImagemimeTypes)) {
                    $media->setProviderName('sonata.media.provider.image');
                    $car->addImage($media);
                    $media->setCar($car);
                    $mediaManager->save($media);
                }
            }

            $this->get('car_show.manager.car')->saveCar($car);

        }

        $carImages = $car->getImages();
        return $this->render('@CarShow/Default/addPictures.html.twig', [
            'images' => $carImages,
            'form' => $form->createView(),
            'car' => $car,
        ]);
    }

    /**
     * @param Request $request
     * @param null $id
     * @Route("/car/{id}/changePicture", name="car_show_change_main_picture")
     * @return Response
     *
     */
    public function changeMainPictureAction($id = null, Request $request)
    {
        /** @var Car $car */
        $car = $this->get('car_show.manager.car')->findCarById($id);
        $posts = $car->getPosts();

        $pictures = $car->getImages();

        $formManager = $this->get('car_show.form_factory.picture_select');

        $form = $formManager->createForm($car, $pictures);

        $form->handleRequest($request);
        if ($request->isMethod('POST')) {
            $media = $form->get('mainPhoto')->getData();

            $car->setMainPhoto($media);

            $this->get('car_show.manager.car')->saveCar($car);
            return $this->redirectToRoute('car_show_get_car', ['id' => $car->getId()]);
        }

        $carImages = $car->getImages();
        return $this->render('@CarShow/Default/selectPicture.html.twig', [
            'images' => $carImages,
            'form' => $form->createView(),
            'car' => $car,
        ]);
    }

}