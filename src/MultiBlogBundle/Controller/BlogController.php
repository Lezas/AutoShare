<?php

namespace MultiBlogBundle\Controller;

use MultiBlogBundle\Entity\Page;
use MultiBlogBundle\Entity\Tag;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BlogController extends Controller
{
    /**
     * @Route("/page/new", name="multi_blog_new_page")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newPageAction(Request $request)
    {
        $formFactory = $this->get('multi_blog.form_factory.page');
        $pageManager = $this->get('multi_blog.manager.page');
        $tagManager = $this->get('multi_blog.manager.tag');
        /** @var Page $page */
        $page = $pageManager->createPage();

        $form = $formFactory->createForm();
        $form->setData($page);
        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {
            if (null !== $pageManager->findPageById($page->getId())) {
                return new Response(sprintf("Duplicate pade id '%s'.", $form->getData()->getId()), 400);
            }

            $page->setAuthor($this->getUser());
            $tags = $tagManager->createTagsFromString($form->get('tags')->getData());
            foreach ($tags as $tag) {
                /** @var Tag $tag */
                $tag->incrementUsageCount();
                $page->addTag($tag);
            }

            $pageManager->savePage($page);

            return $this->redirectToRoute('multi_blog_get_page', ['id' => $page->getId()]);
        }

        return $this->render('@MultiBlog/Page/newPage.html.twig',[
            'form' => $form->createView()
            ]
            );
    }

    /**
     * @Route("/pages/", name="multi_blog_all_pages")
     * @param Request $request
     * @return Response
     */
    public function getPagesAction(Request $request)
    {
        $postManager = $this->get('multi_blog.manager.page');
        $posts = $postManager->findAllPages();

        $em = $this->get('doctrine.orm.entity_manager');
        $qb = $em->getRepository('MultiBlogBundle:Page')->createQueryBuilder('q')
            ->addSelect('q.createdAt as HIDDEN time')
            ->addSelect('q.title as HIDDEN title')
            ->addOrderBy('q.createdAt');
        $query = $qb->getQuery();

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 20),/*limit per page*/
            [
                'p.createdAt' => 'time'
            ]
        );

        return $this->render('@MultiBlog/Page/pages.html.twig',[
                'pages' => $posts,
                'pagination' => $pagination,
            ]
        );
    }

    /**
     * @Route("/page/{id}", name="multi_blog_get_page")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getPageAction($id)
    {
        $pageManager = $this->get('multi_blog.manager.page');
        $page = $pageManager->findPageById($id);

        return $this->render('@MultiBlog/Page/page.html.twig',[
                'page' => $page
            ]
        );
    }
}
