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
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getPagesAction()
    {
        $postManager = $this->get('multi_blog.manager.page');
        $posts = $postManager->findAllPages();

        return $this->render('@MultiBlog/Page/pages.html.twig',[
                'pages' => $posts
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
