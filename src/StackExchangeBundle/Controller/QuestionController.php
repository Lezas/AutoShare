<?php

namespace StackExchangeBundle\Controller;

use FOS\RestBundle\View\View;
use MyAutoBundle\Entity\User;
use StackExchangeBundle\Entity\Question;
use StackExchangeBundle\Entity\Tag;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Templating\TemplateReference;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class QuestionController extends Controller
{
    /**
     * @Route("/question/new", name="question_new")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newQuestionAction(Request $request)
    {
        $this->getUser();

        $questionManager = $this->get('stack_exchange.manager.question');
        $tagManager = $this->get('stack_exchange.manager.tag');
        $question = $questionManager->createQuestion();

        $form = $this->get('stack_exchange.form_factory.question')->createForm();
        $form->setData($question);
        $form->handleRequest($request);

        if ($form->isValid()) {
            if (null !== $questionManager->findQuestionById($question->getId())) {
                return new Response(sprintf("Duplicate question id '%s'.", $form->getData()->getId()), 400);
            }

            $tags = $tagManager->createTagsFromString($form->get('tags')->getData());
            $questionManager->saveQuestion($question);
            foreach ($tags as $tag) {
                /** @var Tag $tag */
                $tag->addQuestion($question);
                $tag->incrementUsageCount();
                $tagManager->saveTag($tag);
                $question->addTag($tag);
            }

            $questionManager->saveQuestion($question);


            return $this->redirectToRoute('question', ['id' => $question->getId()]);
        }

        return $this->render('StackExchangeBundle:Question:question_form.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * @Route("/question/{id}", name="question")
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function getQuestionAction($id, Request $request)
    {
        $questionManager = $this->get('stack_exchange.manager.question');
        $question = $questionManager->findQuestionById($id);


        return $this->render('StackExchangeBundle:Question:question_page.html.twig',
            ['question' => $question]
        );
    }

    /**
     * @Route("/questions", name="questions")
     * @param Request $request
     * @return Response
     */
    public function questionsAction(Request $request)
    {
        $questionManager = $this->get('stack_exchange.manager.question');
        $question = $questionManager->findAllQuestion();

        $em = $this->get('doctrine.orm.entity_manager');
        $dql = "SELECT q FROM StackExchangeBundle:Question q";
        $query = $em->createQuery($dql);

        $qb = $em->getRepository('StackExchangeBundle:Question')->createQueryBuilder('q')
                ->addSelect('q.createdAt as HIDDEN time')
                ->addSelect('q.title as HIDDEN title')
                ->addSelect('q.score as HIDDEN score');
        $query = $qb->getQuery();



        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 10),/*limit per page*/
            [
                'p.createdAt' => 'time'
            ]
        );

        return $this->render('StackExchangeBundle:Question:questions_main.html.twig',
            [
                'questions' => $question,
                'pagination' => $pagination,
            ]
        );
    }

    /**
     * @Route("/question/{id}/edit", name="question_edit")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editQuestionAction($id, Request $request)
    {
        $this->getUser();

        $questionManager = $this->get('stack_exchange.manager.question');
        $tagManager = $this->get('stack_exchange.manager.tag');
        $question = $questionManager->findQuestionById($id);
        if ($question == null) {
            return new Response(sprintf("Cant find question with id '%s'.", $id), 400);
        }

        $tagsInString = $tagManager->convertTagsToString($question->getTags()->getValues());

        $form = $this->get('stack_exchange.form_factory.question')->createForm();
        $form->setData($question);
        $form->get('tags')->setData($tagsInString);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $tags = $tagManager->createTagsFromString($form->get('tags')->getData());

            foreach ($tags as $tag) {
                /** @var Tag $tag */
                $tag->addQuestion($question);
                //TODO: fix tags usage count: move to proper listener!
                $tag->incrementUsageCount();
                $tagManager->saveTag($tag);
                $question->addTag($tag);
            }

            $questionManager->saveQuestion($question);

            return $this->redirectToRoute('question', ['id' => $question->getId()]);
        }

        return $this->render('StackExchangeBundle:Question:question_form.html.twig',
            ['form' => $form->createView()]
        );
    }
}
