<?php

namespace MultiBlogBundle\Controller;

use MultiBlogBundle\Entity\Post;
use MultiBlogBundle\Form\PostType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class BlogController extends Controller
{
    /**
     * @Route("/page/new")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newPostAction(Request $request)
    {
        $form = $this->createForm(PostType::class, new Post());


        return $this->render('@MultiBlog/Post/newPost.html.twig',[
            'form' => $form->createView()
            ]
            );
    }
}
