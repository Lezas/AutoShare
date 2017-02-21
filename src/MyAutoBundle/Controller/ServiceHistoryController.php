<?php

namespace MyAutoBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use MyAutoBundle\Entity\Auto;
use MyAutoBundle\Entity\Post;
use MyAutoBundle\Entity\ServiceHistory;
use MyAutoBundle\Entity\Thread;
use MyAutoBundle\Entity\User;
use MyAutoBundle\Form\Type\AutoType;
use MyAutoBundle\Form\Type\PostType;
use MyAutoBundle\Form\Type\ServiceHistoryType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class DefaultController
 * @package MyAutoBundle\Controller
 */
class ServiceHistoryController extends Controller
{
    /**
     * @param Request $request
     * @Route("/car/{id}/service/history", name="car_history")
     * @return Response
     */
    public function newServiceHistoryAction($id = null, Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $em = $this->getDoctrine()->getEntityManager();
        $user = $this->getUser();
        $car = $em->getRepository('MyAutoBundle:Auto')->find($id);

        if (!$car) {
            throw new NotFoundHttpException("Page not found");
        }

        $SH = new ServiceHistory();
        $form = $this->createForm(ServiceHistoryType::class, $SH);

        $form->handleRequest($request);

        if($form->isValid()){

            $SH->setAuto($car);
            $car->addServiceHistory($SH);

            $em = $this->getDoctrine()->getManager();

            $em->persist($SH);
            $em->persist($car);
            $em->flush();
            $this->addFlash(
                'notice',
                'Your service has been saved!'
            );
        }

        $serviceHistory = $car->getServiceHistory();

        $orderedCollection = new ArrayCollection();

        for($i=$serviceHistory->count()-1; $i>=0; $i--){
            $orderedCollection->add($serviceHistory->get($i));
        }

        $SH = new ServiceHistory();
        $form = $this->createForm(ServiceHistoryType::class, $SH);
        return $this->render('@MyAuto/Default/serviceHistory.html.twig',[
            'form' => $form->createView(),
            'auto' => $car,
            'serviceHistory' => $orderedCollection,
            'title' => 'New Auto'
        ]);
    }

    /**
     * @param null $carId
     * @param null $serviceHistory
     * @param Request $request
     * @return Response
     * @Route("/car/{carId}/service/history/{serviceHistory}", name="car_history_edit")
     */
    public function editServiceHistoryAction($carId = null, $serviceHistory = null, Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $em = $this->getDoctrine()->getEntityManager();
        $user = $this->getUser();
        $car = $em->getRepository('MyAutoBundle:Auto')->find($carId);
        $SH = $em->getRepository('MyAutoBundle:ServiceHistory')->find($serviceHistory);

        if (!$car || !$SH || !$user) {
            throw new NotFoundHttpException("Page not found");
        }

        $form = $this->createForm(ServiceHistoryType::class, $SH);

        $form->handleRequest($request);

        if($form->isValid()){

            $SH->setAuto($car);
            $car->addServiceHistory($SH);

            $em = $this->getDoctrine()->getManager();

            $em->persist($SH);
            $em->persist($car);
            $em->flush();
            $this->addFlash(
                'notice',
                'Your service has been saved!'
            );

        }

        $serviceHistory = $car->getServiceHistory();

        $orderedCollection = new ArrayCollection();

        for($i=$serviceHistory->count()-1; $i>=0; $i--){
            $orderedCollection->add($serviceHistory->get($i));
        }

        $form = $this->createForm(ServiceHistoryType::class, $SH);
        return $this->render('@MyAuto/Default/serviceHistory.html.twig',[
            'form' => $form->createView(),
            'auto' => $car,
            'serviceHistory' => $orderedCollection,
            'title' => 'New Auto'
        ]);
    }

    /**
     * @param Request $request
     * @Route("/service/history/remove/{serviceHistory}", name="car_history_remove")
     * @return Response
     */
    public function deleteServiceHistoryAction($serviceHistory = null, Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $em = $this->getDoctrine()->getEntityManager();
        $user = $this->getUser();

        $SH = $em->getRepository('MyAutoBundle:ServiceHistory')->find($serviceHistory);

        if (!$SH) {
            throw new NotFoundHttpException("Page not found");
        }
        $car = $SH->getAuto();

        if (!$car) {
            throw new NotFoundHttpException("Page not found");
        }

        $car->removeServiceHistory($SH);

        $em->persist($car);
        $em->remove($SH);
        $em->flush();

        return new JsonResponse(['success' => true]);

    }
}