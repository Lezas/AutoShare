<?php

namespace CarShowBundle\Controller;

use CarShowBundle\Entity\Car;
use CarShowBundle\Entity\Post;
use CarShowBundle\Form\Type\PostType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class DefaultController
 * @package CarShowBundle\Controller
 */
class CarPostController extends Controller
{

    /**
     * @Route("/{carId}/newCarPost", name="newCarPost")
     * @param null $carId
     * @param Request $request
     * @return Response
     */
    public function newCarPostAction($carId = null, Request $request)
    {
        $user = $this->getUser();

        /** @var Car $auto */
        $auto = $this->getDoctrine()->getRepository('CarShowBundle:Car')->find($carId);

        $post = new Post();
        $form = $this->createForm(PostType::class, $post, ['auto' => $auto]);

        $form->handleRequest($request);

        if($form->isValid()){
            $id = random_bytes(10);

            $thread = $this->get('fos_comment.manager.thread')->findThreadById($id);

            if (null === $thread) {
                $thread = $this->get('fos_comment.manager.thread')->createThread();
                $thread->setId((string)$id);

                $thread->setPermalink("");

                // Add the thread
                $this->get('fos_comment.manager.thread')->saveThread($thread);
            }
            $em = $this->getDoctrine()->getManager();

            $post->setAuto($auto);
            $post->setThread($thread);

            if ($post->isDeleted() === null) {
                $post->setDeleted(false);
            }

            $em->persist($post);
            $em->flush();
            $this->addFlash(
                'notice',
                'Your post has been saved!'
            );

        }

        return $this->render('@CarShow/Default/newPost.html.twig',[
            'form' => $form->createView(),
            'auto' => $auto,
            'title' => 'New Post',
        ]);
    }

    /**
     * @Route("/{carId}/editCarPost/{postId}", name="editCarPost")
     * @param null $carId
     * @param null $postId
     * @param Request $request
     * @return Response
     */
    public function editCarPostAction($carId = null, $postId = null, Request $request)
    {
        $user = $this->getUser();
        $auto = $this->getDoctrine()->getRepository('CarShowBundle:Car')->find($carId);

        $post = $this->getDoctrine()->getRepository('CarShowBundle:Post')->find($postId);
        $form = $this->createForm(PostType::class, $post, ['auto' => $auto]);

        $form->handleRequest($request);

        if($form->isValid()){
            $post->setAuto($auto);

            $em = $this->getDoctrine()->getManager();

            $em->persist($post);
            $em->flush();
            $this->addFlash(
                'notice',
                'Your post has been saved!'
            );

        }

        return $this->render('@CarShow/Default/newPost.html.twig',[
            'form' => $form->createView(),
            'title' => 'Edit post',
        ]);
    }

    /**
     * @Route("/{carId}/deleteCarPost/{postId}", name="deleteCarPost")
     * @param null $carId
     * @param null $postId
     * @param Request $request
     * @return Response
     */
    public function removeCarPostAction($carId = null, $postId = null, Request $request)
    {

        $post = $this->getDoctrine()->getRepository('CarShowBundle:Post')->find($postId);

        if ($post->getAuto()->getId() == $carId) {
            $post->setDeleted(true);

            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($post);
            $em->flush();
            $this->addFlash(
                'notice',
                'Your post has been deleted!'
            );

        }

        return $this->redirectToRoute('selectedAuto', ['id' => $carId]);


    }

    /**
     * @Route("/car/post/{postId}", name="car_show_get_car_post")
     * @param null $postId
     * @return Response
     */
    public function indexCarPostAction($postId = null)
    {
        if ($postId == null) {
            throw new NotFoundHttpException("Page not found");
        }

        $em = $this->getDoctrine()->getEntityManager();

        $user = $this->getUser();
        $post = $em->getRepository('CarShowBundle:Post')->find($postId);


        /** @var Post $post */
        if (!$post ) {
            throw new NotFoundHttpException("Page not found");
        }

        if ($post->isDeleted()) {
            throw new NotFoundHttpException("Page not found");
        }

        $car = $post->getAuto();

        return $this->render('@CarShow/Default/post.html.twig',[
            'auto' => $car,
            'post' => $post,
        ]);

    }


}
