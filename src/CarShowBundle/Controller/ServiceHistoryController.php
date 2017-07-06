<?php

namespace CarShowBundle\Controller;

use CarShowBundle\Entity\Car;
use Doctrine\Common\Collections\ArrayCollection;

use CarShowBundle\Entity\ServiceHistory;
use CarShowBundle\Form\Type\ServiceHistoryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class DefaultController
 * @package CarShowBundle\Controller
 */
class ServiceHistoryController extends Controller
{
    /**
     * @param Car $car
     * @param Request $request
     * @return Response
     * @Route("/car/{car}/service/history", name="car_history")
     * @ParamConverter("car", class="CarShowBundle:Car")
     * @Security("user == car.getUser() and false == car.getDeleted() and has_role('ROLE_USER')")
     */
    public function newServiceHistoryAction(Car $car, Request $request)
    {
        $SHManager = $this->get('car_show.manager.service_history');
        $SH = $SHManager->createServiceHistory();
        //TODO move to proper form factory
        $form = $this->createForm(ServiceHistoryType::class, $SH);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $SH->setAuto($car);
            $car->addServiceHistory($SH);
            $SHManager->saveServiceHistory($SH);
            $this->get('car_show.manager.car')->saveCar($car);
            $this->addFlash(
                'notice',
                'Your service has been saved!'
            );
        }

        $serviceHistory = $car->getServiceHistory();

        $orderedCollection = new ArrayCollection();

        for ($i = $serviceHistory->count() - 1; $i >= 0; $i--) {
            $orderedCollection->add($serviceHistory->get($i));
        }

        $SH = $SHManager->createServiceHistory();
        $form = $this->createForm(ServiceHistoryType::class, $SH);
        return $this->render('@CarShow/Default/serviceHistory.html.twig', [
            'form' => $form->createView(),
            'auto' => $car,
            'serviceHistory' => $orderedCollection,
            'title' => 'New Auto'
        ]);
    }

    /**
     * @param Car $car
     * @param ServiceHistory $serviceHistory
     * @param Request $request
     * @return Response
     * @Route("/car/{car}/service/history/{serviceHistory}", name="car_history_edit")
     * @ParamConverter("car", class="CarShowBundle:Car")
     * @ParamConverter("serviceHistory", class="CarShowBundle:ServiceHistory")
     * @Security("user == car.getUser() and false == car.getDeleted() and car == serviceHistory.getAuto() and has_role('ROLE_USER')")
     */
    public function editServiceHistoryAction(Car $car, ServiceHistory $serviceHistory, Request $request)
    {
        //TODO move to form factory
        $form = $this->createForm(ServiceHistoryType::class, $serviceHistory);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $serviceHistory->setAuto($car);
            $car->addServiceHistory($serviceHistory);

            $this->get('car_show.manager.service_history')->saveServiceHistory($serviceHistory);
            $this->get('car_show.manager.car')->saveCar($car);
            $this->addFlash(
                'notice',
                'Your service has been saved!'
            );

            return $this->redirectToRoute('car_history', ['id' => $car->getId()]);
        }

        $serviceHistories = $car->getServiceHistory();

        $orderedCollection = new ArrayCollection();

        for ($i = $serviceHistories->count() - 1; $i >= 0; $i--) {
            $orderedCollection->add($serviceHistories->get($i));
        }

        $form = $this->createForm(ServiceHistoryType::class, $serviceHistory);
        return $this->render('@CarShow/Default/serviceHistory.html.twig', [
            'form' => $form->createView(),
            'auto' => $car,
            'serviceHistory' => $orderedCollection,
            'title' => 'New Auto'
        ]);
    }

    /**
     * @param $serviceHistory
     * @param Request $request
     * @return Response
     * @Route("/service/history/remove/{serviceHistory}", name="car_history_remove")
     * @ParamConverter("serviceHistory", class="CarShowBundle:ServiceHistory")
     * @Security("user == serviceHistory.getAuto().getUser() and false == serviceHistory.getAuto().getDeleted() and has_role('ROLE_USER')")
     */
    public function deleteServiceHistoryAction(ServiceHistory $serviceHistory, Request $request)
    {
        $car = $serviceHistory->getAuto();

        $car->removeServiceHistory($serviceHistory);

        $this->get('car_show.manager.car')->saveCar($car);
        $this->get('car_show.manager.service_history')->delete($serviceHistory);

        return new JsonResponse(['success' => true]);
    }
}