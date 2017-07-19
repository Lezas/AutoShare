<?php

namespace StackExchangeBundle\Controller;

use MainBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use StackExchangeBundle\Entity\Answer;
use StackExchangeBundle\Entity\Question;
use StackExchangeBundle\Entity\Tag;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class QuestionController extends Controller
{
    /**
     * TODO: is this function used anywhere?
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
            //TODO WTF IS DIS
            if (null !== $questionManager->findQuestionById($question->getId())) {
                return new Response(sprintf("Duplicate question id '%s'.", $form->getData()->getId()), 400);
            }

            $tags = $tagManager->createTagsFromString($form->get('tags')->getData());
            foreach ($tags as $tag) {
                /** @var Tag $tag */
                $tag->incrementUsageCount();
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
     * @Route("/question/{question}", name="question")
     * @ParamConverter("question", class="StackExchangeBundle:Question")
     * @Security(" false == question.isDeleted()")
     * @param Question $question
     * @param Request $request
     * @return Response
     */
    public function getQuestionAction(Question $question, Request $request)
    {
        $relatedQuestions = $this->get('stack_exchange.manager.question')->findSimilarQuestionsByTags($question);

        return $this->render('StackExchangeBundle:Question:question_page.html.twig', [
                'question' => $question,
                'relatedQuestions' => $relatedQuestions,
            ]
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

        $thisWeekQuestions = $questionManager->findOneWeekQuestions(10);

        $em = $this->get('doctrine.orm.entity_manager');

        //TODO move to manager
        $qb = $em->getRepository('StackExchangeBundle:Question')->createQueryBuilder('q')
            ->addSelect('q.createdAt as HIDDEN time')
            ->addSelect('q.title as HIDDEN title')
            ->addSelect('q.score as HIDDEN score')
            ->addOrderBy('q.answered');
        $query = $qb->getQuery();


        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 10),/*limit per page*/

            array('defaultSortFieldName' => 'time', 'defaultSortDirection' => 'desc')

        );

        return $this->render('StackExchangeBundle:Question:questions_main.html.twig',
            [
                'weekQuestions' => $thisWeekQuestions,
                'questions' => $question,
                'pagination' => $pagination,
            ]
        );
    }

    /**
     * @Route("/question/{question}/edit", name="question_edit")
     * @ParamConverter("question", class="StackExchangeBundle:Question")
     * @Security("user == question.getAuthor() and false == question.isDeleted() and has_role('ROLE_USER')")
     * @param Question $question
     * @param Request $request
     * @return Response
     */
    public function editQuestionAction(Question $question, Request $request)
    {
        $this->getUser();

        $tagManager = $this->get('stack_exchange.manager.tag');
        $tagsInString = $tagManager->convertTagsToString($question->getTags()->getValues());

        $form = $this->get('stack_exchange.form_factory.question')->createForm();
        $form->setData($question);
        $form->get('tags')->setData($tagsInString);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $tags = $tagManager->createTagsFromString($form->get('tags')->getData());

            $oldTags = $question->getTags();
            foreach ($oldTags as $oldTag) {
                if (!$tags->contains($oldTag)) {
                    $question->removeTag($oldTag);
                    $oldTag->removeQuestion($question);
                    $oldTag->incrementUsageCount(-1);
                }
            }

            foreach ($tags as $tag) {
                /** @var Tag $tag */
                if (!$oldTags->contains($tag)) {
                    //TODO: fix tags usage count: move to proper listener!
                    $tag->incrementUsageCount();
                    $question->addTag($tag);
                }
            }

            $this->get('stack_exchange.manager.question')->updateQuestion($question);

            return $this->redirectToRoute('question', ['id' => $question->getId()]);
        }

        return $this->render('StackExchangeBundle:Question:question_form.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * @Route("/question/{question}/answered/{answer}", name="set_question_answered")
     * @ParamConverter("question", class="StackExchangeBundle:Question")
     * @ParamConverter("answer", class="StackExchangeBundle:Answer")
     * @Security("user == question.getAuthor() and false == question.isDeleted() and has_role('ROLE_USER') and question == answer.getQuestion()")
     * @param Question $question
     * @param Answer $answer
     * @return Response
     */
    public function setQuestionAnsweredAction(Question $question, Answer $answer)
    {
        $questionManager = $this->get('stack_exchange.manager.question');

        if (!$questionManager->setQuestionToAnswered($question, $answer)) {
            //TODO add dome kind of error reporting for user

            return new Response("Something wen't wrong!", 400);
        }
        $questionManager->saveQuestion($question);

        return $this->redirectToRoute('question', ['id' => $question->getId()]);
    }

    //need set answered
    //need to remove answered
    //edit get functionas - need first check if there is answered. because answered must be first in the row. then next one's
}
