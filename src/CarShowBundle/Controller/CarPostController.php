<?php

namespace CarShowBundle\Controller;

use CarShowBundle\Entity\Car;
use CarShowBundle\Entity\Post;
use CarShowBundle\Form\Type\PostType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Class DefaultController
 * @package CarShowBundle\Controller
 */
class CarPostController extends Controller
{

    /**
     * @Route("/{car}/newCarPost", name="newCarPost")
     * @param Car $car
     * @param Request $request
     * @return Response
     * @ParamConverter("car", class="CarShowBundle:Car")
     * @Security("user == car.getUser() and false == car.getDeleted()")
     */
    public function newCarPostAction(Car $car, Request $request)
    {
        $postManager = $this->get('car_show.manager.post');
        $post = $postManager->createPost();

        $form = $this->createForm(PostType::class, $post, ['auto' => $car]);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $id = random_bytes(10);

            $thread = $this->get('fos_comment.manager.thread')->findThreadById($id);

            if (null === $thread) {
                $thread = $this->get('fos_comment.manager.thread')->createThread();
                $thread->setId((string)$id);
                $thread->setPermalink("");
                $this->get('fos_comment.manager.thread')->saveThread($thread);
            }

            $post->setAuto($car);
            $post->setThread($thread);

            //TODO move to listener
            if (null === $post->isDeleted()) {
                $post->setDeleted(false);
            }

            $postManager->savePost($post);
            $this->addFlash(
                'notice',
                'Your post has been saved!'
            );

            return $this->redirectToRoute('car_show_get_car_post', ['postId' => $post->getId()]);

        }

        return $this->render('@CarShow/Default/newPost.html.twig', [
            'form' => $form->createView(),
            'car' => $car,
            'title' => 'Naujas įrašas',
        ]);
    }

    /**
     * @Route("/{car}/editCarPost/{post}", name="editCarPost")
     * @param Car $car
     * @param Post $post
     * @param Request $request
     * @return Response
     * @ParamConverter("car", class="CarShowBundle:Car")
     * @ParamConverter("post", class="CarShowBundle:Post")
     * @Security("user == car.getUser() and false == car.getDeleted()")
     */
    public function editCarPostAction(Car $car, Post $post, Request $request)
    {
        //move to form factory
        $form = $this->createForm(PostType::class, $post, ['auto' => $car]);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $post->setAuto($car);

            $this->get('car_show.manager.post')->savePost($post);

            $this->addFlash(
                'notice',
                'Your post has been saved!'
            );
        }

        return $this->render('@CarShow/Default/newPost.html.twig', [
            'car' => $car,
            'form' => $form->createView(),
            'title' => 'Edit post',
        ]);
    }

    /**
     * @Route("/{car}/deleteCarPost/{post}", name="deleteCarPost")
     * @param Car $car
     * @param Post $post
     * @param Request $request
     * @return Response
     * @ParamConverter("car", class="CarShowBundle:Car")
     * @ParamConverter("post", class="CarShowBundle:Post")
     * @Security("user == car.getUser() and false == car.getDeleted() and car == post.getAuto()")
     */
    public function removeCarPostAction(Car $car, Post $post, Request $request)
    {
        $postManager = $this->get('car_show.manager.post');

        $postManager->deletePost($post);
        $postManager->savePost($post);

        $this->addFlash(
            'notice',
            'Your post has been deleted!'
        );

        return $this->redirectToRoute('car_show_get_car', ['id' => $car->getId()]);
    }

    /**
     * @Route("/car/post/{post}", name="car_show_get_car_post")
     * @param Post $post
     * @return Response
     * @ParamConverter("post", class="CarShowBundle:Post")
     * @Security("false == post.isDeleted()")
     */
    public function indexCarPostAction(Post $post)
    {
        $em = $this->getDoctrine()->getEntityManager();

        //TODO move to PostManager or CarManager
        $sidePosts = $em->getRepository('CarShowBundle:Post')->createQueryBuilder('p')
            ->where('p.id != :np')
            ->andWhere('p.car = :car')
            ->andWhere('p.deleted != 1')
            ->addOrderBy('p.date', 'DESC')
            ->setParameter('np', $post->getId())
            ->setParameter('car', $post->getAuto())
            ->getQuery()->getResult();

        $car = $post->getAuto();

        return $this->render('@CarShow/Default/post.html.twig', [
            'auto' => $car,
            'post' => $post,
            'sidePosts' => $sidePosts
        ]);

    }


}
