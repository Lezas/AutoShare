<?php

namespace CarShowBundle\Controller;

use CarShowBundle\Entity\Auto;
use CarShowBundle\Form\Type\AutoType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CarController extends Controller
{
    /**
     * @param Request $request
     * @Route("/car/new", name="auto_new_car")
     * @return Response
     */
    public function newAutoAction(Request $request)
    {
        $user = $this->getUser();
        $auto = new Auto();
        $form = $this->createForm(AutoType::class, $auto, ['user' => $user]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();

            $auto->setUser($user);

            /** @var File $foto */
            $foto = $auto->getFoto();

            // Generate a unique name for the file before saving it
            $fileName = md5(uniqid()) . '.' . $foto->guessExtension();

            // Move the file to the directory where brochures are stored
            $foto->move(
                $this->getParameter('foto_directory'),
                $fileName
            );

            $auto->setFoto($fileName);

            $em = $this->getDoctrine()->getManager();

            $em->persist($auto);
            $em->flush();
            $this->addFlash(
                'notice',
                'Your expense has been saved!'
            );

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
        $auto = $this->getDoctrine()->getRepository('CarShowBundle:Auto')->find($id);

        $fotoBeforeEdit = $auto->getFoto();

        if ($fotoBeforeEdit != null) {
            $auto->setFoto(
                new File($this->getParameter('foto_directory') . '/' . $auto->getFoto())
            );
        }

        $form = $this->createForm(AutoType::class, $auto, ['user' => $user]);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $user = $this->getUser();

            $auto->setUser($user);

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
            $em = $this->getDoctrine()->getManager();

            $em->persist($auto);
            $em->flush();
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
     * @Route("/car/{id}", name="selectedAuto")
     * @return Response
     *
     */
    public function selectedAutoAction($id = null, Request $request)
    {
        $auto = $this->getDoctrine()->getRepository('CarShowBundle:Auto')->find($id);

        $posts = $auto->getPosts();

        return $this->render('@CarShow/Default/autoBlog.html.twig', [
            'posts' => $posts,
            'auto' => $auto,
        ]);
    }

}