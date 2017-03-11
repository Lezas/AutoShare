<?php
/**
 * Created by PhpStorm.
 * User: pkupe
 * Date: 2017-02-16
 * Time: 12:36
 */

namespace StackExchangeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


class AnswerController extends Controller
{
    /**
     * @param $questionId
     * @return Response
     */
    public function newAnswerAction($questionId)
    {
        $question = $this->get('stack_exchange.manager.question')->findQuestionById($questionId);
        if (!$question) {
            throw new NotFoundHttpException(sprintf('Question with identifier of "%s" does not exist', $questionId));
        }

        $answer = $this->get('stack_exchange.manager.answer')->createAnswer($question);

        $form = $this->get('stack_exchange.form_factory.answer')->createForm($this->generateUrl('answer_POST', ['questionId' => $questionId]));

        $form->setData($answer);

        return $this->render('StackExchangeBundle:Answer:answer_form.html.twig',
            ['form_answer' => $form->createView()]
        );
    }

    /**
     * @Route("/{questionId}/answer/POST", name="answer_POST")
     * @param $questionId
     * @param Request $request
     * @return Response
     */
    public function postAnswerAction($questionId, Request $request)
    {
        $question = $this->get('stack_exchange.manager.question')->findQuestionById($questionId);
        if (!$question) {
            throw new NotFoundHttpException(sprintf('Question with identifier of "%s" does not exist', $questionId));
        }

        $answerManager = $this->get('stack_exchange.manager.answer');
        $answer = $answerManager->createAnswer($question);

        $form = $this->get('stack_exchange.form_factory.answer')->createForm($this->generateUrl('answer_POST', ['questionId' => $questionId]));
        $form->setData($answer);

        $form->handleRequest($request);

        if ($form->isValid()) {
            if ($answerManager->saveAnswer($answer) !== false) {

                return $this->redirectToRoute('question', ['id' => $questionId]);
            }
        }

        //TODO move to normal method
        dump($form->getErrors());
        exit;
    }

    /**
     * @Route("/answer/{id}/edit", name="answer_edit")
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function editAnswerAction($id, Request $request)
    {
        $this->getUser();

        $answerManager = $this->get('stack_exchange.manager.answer');
        $answer = $answerManager->findAnswerById($id);
        if ($answer == null) {
            return new Response(sprintf("Cant find question with id '%s'.", $id), 400);
        }


        $form = $this->get('stack_exchange.form_factory.answer')->createForm();
        $form->setData($answer);
        $form->handleRequest($request);

        if ($form->isValid()) {

            $answerManager->saveAnswer($answer);

            return $this->redirectToRoute('question', ['id' => $answer->getQuestion()->getId()]);
        }

        return $this->render('@StackExchange/Answer/answer_edit.html.twig',
            ['form' => $form->createView()]
        );
    }
}