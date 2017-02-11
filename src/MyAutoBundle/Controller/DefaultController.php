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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class DefaultController
 * @package MyAutoBundle\Controller
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="Dashboard")
     */
    public function indexAction()
    {
        $autoRepository = $this->getDoctrine()->getManager()->getRepository('MyAutoBundle:Auto');

        $cars = $autoRepository->findBy(['private' => 0]);

        return $this->render('MyAutoBundle:Default:index.html.twig', [
            'ownedAutos' => $cars,
            'user' => $this->getUser(),
        ]);
    }

    /**
     * @param Request $request
     * @Route("/new", name="new_auto")
     * @return Response
     */
    public function newAutoAction(Request $request)
    {
        $user = $this->getUser();
        $auto = new Auto();
        $form = $this->createForm(AutoType::class, $auto, ['user' => $user]);

        $form->handleRequest($request);

        if ($form->isValid()) {
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

        return $this->render('@MyAuto/Default/newAuto.html.twig', [
            'form' => $form->createView(),
            'title' => 'New Auto'
        ]);
    }

    /**
     * @param Request $request
     * @param null $id
     * @Route("/edit/{id}", name="editAuto")
     * @return Response
     */
    public function editAutoAction($id = null, Request $request)
    {
        $user = $this->getUser();
        $auto = $this->getDoctrine()->getRepository('MyAutoBundle:Auto')->find($id);

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

        return $this->render('@MyAuto/Default/newAuto.html.twig', [
            'form' => $form->createView(),
            'title' => 'Edit Auto Information'
        ]);
    }

    /**
     * @param Request $request
     * @param null $id
     * @Route("/auto/{id}", name="selectedAuto")
     * @return Response
     *
     */
    public function selectedAutoAction($id = null, Request $request)
    {
        $auto = $this->getDoctrine()->getRepository('MyAutoBundle:Auto')->find($id);

        $posts = $auto->getPosts();

        return $this->render('@MyAuto/Default/autoBlog.html.twig', [
            'posts' => $posts,
            'auto' => $auto,
        ]);
    }

    /**
     * @Route("/garage", name="garage")
     */
    public function userCarListAction()
    {
        /**
         * @var $user User
         */
        $user = $this->getUser();
        $cars = $user->getAuto();

        return $this->render('MyAutoBundle:Default:garage.html.twig', [
            'ownedAutos' => $cars,
        ]);
    }
}
