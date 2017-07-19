<?php

namespace CarShowBundle\Controller;

use Application\Sonata\MediaBundle\Entity\Media;
use CarShowBundle\Entity\Car;
use CarShowBundle\Event\CarEvent;
use CarShowBundle\Events;
use CarShowBundle\Form\Type\PicturesType;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class CarController extends Controller
{
    /**
     * @param Request $request
     * @Route   ("/car/new", name="auto_new_car")
     * @Security("has_role('ROLE_USER')")
     * @return Response
     */
    public function newAutoAction(Request $request)
    {
        $carManager = $this->get('car_show.manager.car');

        /** @var Car $car */
        $car = $carManager->createCar();

        $form = $this->get('car_show.form_factory.car')->createForm();
        $form->setData($car);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $car->setUser($user);
            $carManager->saveCar($car);
            $mediaManager = $this->get('sonata.media.manager.media');

            if ($media = $this->createMedia($form->get('foto')->getData())){
                $car->addImage($media);
                $media->setCar($car);
                $mediaManager->save($media);
                $car->setMainPhoto($media);
            }

            $carManager->saveCar($car);

            $this->addFlash(
                'notice',
                'Tavo automobilis išsaugotas!'
            );

            return $this->redirectToRoute('car_show_get_car', ['id' => $car->getId()]);
        }

        return $this->render('@CarShow/Default/newAuto.html.twig', [
            'form' => $form->createView(),
            'title' => 'Naujas automobilis'
        ]);
    }

    /**
     * @param Car $car
     * @param Request $request
     * @return Response
     * @internal param null $id
     * @Route("/car/{id}/edit", name="auto_car_edit")
     * @ParamConverter("car", class="CarShowBundle:Car")
     * @Security("user == car.getUser() and false == car.getDeleted()")
     */
    public function editAutoAction(Car $car, Request $request)
    {
        $form = $this->get('car_show.form_factory.car')->createForm();
        $form->setData($car);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $car->setUser($this->getUser());

            $event = new CarEvent($car);
            $this->get('event_dispatcher')->dispatch(Events::CAR_PRE_UPDATE, $event);
            $this->get('car_show.manager.car')->saveCar($car);
            $this->get('event_dispatcher')->dispatch(Events::CAR_POST_UPDATE, $event);

            $this->addFlash(
                'notice',
                'Your auto info has been saved!'
            );
        }

        return $this->render('@CarShow/Default/newAuto.html.twig', [
            'form' => $form->createView(),
            'title' => 'Edit Auto Information',
            'car' => $car
        ]);
    }

    /**
     * @param Car $car
     * @param Request $request
     * @return Response
     * @internal param null $id
     * @Route("/car/{id}", name="car_show_get_car")
     * @ParamConverter("car", class="CarShowBundle:Car")
     * @Security("false == car.getDeleted()")
     */
    public function showCarAction(Car $car, Request $request)
    {
        return $this->render('@CarShow/Default/autoBlog.html.twig', [
            'posts' => $car->getPosts(),
            'auto' => $car,
        ]);
    }

    /**
     * @param Car $car
     * @param Request $request
     * @return Response
     * @internal param null $id
     * @Route("/car/{id}/addPictures", name="car_show_add_pictures")
     * @ParamConverter("car", class="CarShowBundle:Car")
     * @Security("user == car.getUser() and false == car.getDeleted()")
     */
    public function addPictureAction(Car $car, Request $request)
    {
        $pictures = new ArrayCollection();

        $form = $this->createForm(PicturesType::class, $pictures);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            $mediaManager = $this->get('sonata.media.manager.media');
            $data = $form->get('images')->getData();

            foreach ($data as $datum) {
                if ($media = $this->createMedia($datum)){
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
     * @param Car $car
     * @param Request $request
     * @return Response
     * @internal param null $id
     * @Route("/car/{id}/changePicture", name="car_show_change_main_picture")
     * @ParamConverter("car", class="CarShowBundle:Car")
     * @Security("user == car.getUser() and false == car.getDeleted()")
     */
    public function changeMainPictureAction(Car $car, Request $request)
    {
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

    /**
     * @param Car $car
     * @param Request $request
     * @return Response
     * @internal param $id
     * @Route   ("/car/{id}/delete", name="car_show_delete_car")
     * @ParamConverter("car", class="CarShowBundle:Car")
     * @Security("false == car.getDeleted()")
     */
    public function deleteCarAction(Car $car, Request $request)
    {
        $form = $this->getConfirmForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('yes')->isClicked()) {
                $car->setDeleted(true);
                $this->get('car_show.manager.car')->saveCar($car);
                $event = new CarEvent($car);
                $this->get('event_dispatcher')->dispatch(Events::CAR_DELETE, $event);
                $this->addFlash(
                    'notice',
                    'Your car has been deleted!'
                );
            } elseif ($form->get('no')->isClicked()) {
                $this->addFlash(
                    'notice',
                    'You have canceled car deletion!'
                );
            }

            return $this->redirectToRoute('garage');
        }

        return $this->render('@CarShow/Default/deleteCar.html.twig', [
            'form' => $form->createView(),
            'car' => $car,
        ]);
    }

    /**
     * @return mixed
     */
    protected function getConfirmForm()
    {
        $form = $this->createFormBuilder()
            ->add('yes', SubmitType::class, array('label' => 'Taip'))
            ->add('no', SubmitType::class, array('label' => 'Ne'))
            ->getForm();

        return $form;
    }

    /**
     * @param Car $car
     * @param Request $request
     * @return Response
     * @internal param $id
     * @Route   ("/car/{id}/makePrivate", name="car_show_make_private_car")
     * @ParamConverter("car", class="CarShowBundle:Car")
     * @Security("false == car.getDeleted()")
     */
    public function privateCarAction(Car $car, Request $request)
    {
        $form = $this->getConfirmForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('yes')->isClicked()) {
                $car->setPrivate(true);
                $this->get('car_show.manager.car')->saveCar($car);
                $this->addFlash(
                    'notice',
                    'Your car has been made private!'
                );
            } elseif ($form->get('no')->isClicked()) {
                return $this->redirectToRoute('car_show_get_car', ['id' => $car->getId()]);
            }

            return $this->redirectToRoute('garage');
        }

        return $this->render('@CarShow/Default/privateCar.html.twig', [
            'form' => $form->createView(),
            'car' => $car,
        ]);
    }

    /**
     * @param Car $car
     * @param Request $request
     * @return Response
     * @internal param $id
     * @Route   ("/car/{id}/makePublic", name="car_show_make_public_car")
     * @ParamConverter("car", class="CarShowBundle:Car")
     * @Security("false == car.getDeleted()")
     */
    public function publicCarAction(Car $car, Request $request)
    {
        $form = $this->getConfirmForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('yes')->isClicked()) {
                $car->setPrivate(false);
                $this->get('car_show.manager.car')->saveCar($car);
                $this->addFlash(
                    'notice',
                    'Your car has been made public!'
                );
            }

            return $this->redirectToRoute('car_show_get_car', ['id' => $car->getId()]);
        }

        return $this->render('@CarShow/Default/publicCar.html.twig', [
            'form' => $form->createView(),
            'car' => $car,
        ]);
    }

    protected function createMedia($imageData)
    {
        if (empty($imageData))
            return null;

        $ImagemimeTypes = array('image/jpeg', 'image/png');

        $media = new Media();
        $media->setContext('default');
        $media->setBinaryContent($imageData);

        if (in_array($imageData->getMimeType(), $ImagemimeTypes)) {
            $media->setProviderName('sonata.media.provider.image');
            return $media;
        }

        return null;
    }

}