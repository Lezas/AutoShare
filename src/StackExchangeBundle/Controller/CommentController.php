<?php
/**
 * Created by Lezas.
 * Date: 2017-03-04
 * Time: 18:36
 */

namespace StackExchangeBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Bundle\FrameworkBundle\Templating\TemplateReference;

class CommentController extends FOSRestController
{

    /**
     * Presents the form to use to create a new Vote for a Comment.
     *
     * @param string $id Id of the Question
     *
     * @return View|Response
     */
    public function newQuestionCommentAction($id)
    {
        $question = $this->get('stack_exchange.manager.question')->findQuestionById($id);

        if (null === $question) {
            throw new NotFoundHttpException(sprintf("No question with id '%s' found ", $question->getId()));
        }

        $comment = $this->get('stack_exchange.manager.question_comment')->createComment($question, $this->getUser());

        $form = $this->get('stack_exchange.form_factory.comment')->createForm($this->generateUrl('stack_exchange_post_question_comment', ['id' => $id]));

        $form->setData($comment);

        $view = View::create()
            ->setData(array(
                'id' => $id,
                'form' => $form->createView()
            ))
            ->setTemplate(new TemplateReference('StackExchangeBundle', 'Comment', 'comment_new'));

        return $this->getViewHandler()->handle($view);
    }

    /**
     * Creates a new Vote for the Question from the submitted data.
     *
     * @param Request $request Current request
     * @param string $id Id of the question
     *
     * @return View
     */
    public function postQuestionCommentAction(Request $request, $id)
    {
        $question = $this->get('stack_exchange.manager.question')->findQuestionById($id);

        if (null === $question) {
            throw new NotFoundHttpException(sprintf("No comment with id '%s' found ", $question->getId()));
        }

        $commentManager = $this->get('stack_exchange.manager.question_comment');

        $user = $this->getUser();

        $comment = $commentManager->createComment($question, $user);

        $form = $this->get('stack_exchange.form_factory.comment')->createForm($this->generateUrl('stack_exchange_post_question_comment', ['id' => $id]));
        $form->setData($comment);
        $form->handleRequest($request);

        if ($form->isValid()) {

            $comment->setAuthor($user);
            $commentManager->saveComment($comment);

            return $this->getViewHandler()->handle($this->onCreateCommentSuccess($form, $id));
        }

        return $this->getViewHandler()->handle($this->onCreateCommentError($form, $id));
    }

    /**
     * Creates a new Vote for the Question from the submitted data.
     *
     * @param Request $request Current request
     * @param string $id Id of the question
     *
     * @return View
     */
    public function editQuestionCommentAction(Request $request, $questionId, $commentId)
    {
        $question = $this->get('stack_exchange.manager.question')->findQuestionById($questionId);
        $comment = $this->get('stack_exchange.manager.question_comment')->findCommentById($commentId);

        if (null === $question || null === $comment) {
            throw new NotFoundHttpException(sprintf("Sorry, no comment or question was found"));
        }

        $commentManager = $this->get('stack_exchange.manager.question_comment');

        $user = $this->getUser();

        $form = $this->get('stack_exchange.form_factory.comment')->createForm(
            $this->generateUrl('stack_exchange_edit_question_comment', ['commentId' => $commentId, 'questionId' => $questionId])
        );
        $form->setData($comment);
        $form->handleRequest($request);

        if ($form->isValid()) {

            $comment->setAuthor($user);
            $commentManager->saveComment($comment);

            return $this->getViewHandler()->handle($this->onCreateCommentSuccess($form, $questionId));
        }

        return $this->getViewHandler()->handle($this->onCreateCommentError($form, $questionId));
    }

    /**
     * Presents the form to use to create a new Vote for a Comment.
     *
     * @param string $id Id of the Answer
     *
     * @return View|Response
     */
    public function newAnswerCommentAction($id)
    {
        $answer = $this->get('stack_exchange.manager.answer')->findAnswerById($id);

        if (null === $answer) {
            throw new NotFoundHttpException(sprintf("No question with id '%s' found ", $answer->getId()));
        }

        $comment = $this->get('stack_exchange.manager.answer_comment')->createComment($answer, $this->getUser());

        $form = $this->get('stack_exchange.form_factory.comment')->createForm($this->generateUrl('stack_exchange_post_answer_comment', ['id' => $id]));

        $form->setData($comment);

        $view = View::create()
            ->setData(array(
                'id' => $id,
                'form' => $form->createView()
            ))
            ->setTemplate(new TemplateReference('StackExchangeBundle', 'Comment', 'comment_new'));

        return $this->getViewHandler()->handle($view);
    }

    /**
     * Creates a new Vote for the Question from the submitted data.
     *
     * @param Request $request Current request
     * @param string $id Id of the question
     *
     * @return View
     */
    public function postAnswerCommentAction(Request $request, $id)
    {
        $answer = $this->get('stack_exchange.manager.answer')->findAnswerById($id);

        if (null === $answer) {
            throw new NotFoundHttpException(sprintf("No answer with id '%s' found ", $answer->getId()));
        }

        $commentManager = $this->get('stack_exchange.manager.answer_comment');

        $user = $this->getUser();

        $comment = $commentManager->createComment($answer, $user);

        $form = $this->get('stack_exchange.form_factory.comment')->createForm($this->generateUrl('stack_exchange_post_answer_comment', ['id' => $id]));
        $form->setData($comment);
        $form->handleRequest($request);

        if ($form->isValid()) {

            $commentManager->saveComment($comment);

            return $this->getViewHandler()->handle($this->onCreateCommentSuccess($form, $answer->getQuestion()->getId()));
        }

        return $this->getViewHandler()->handle($this->onCreateCommentError($form, $id));
    }

    /**
     * Creates a new Vote for the Question from the submitted data.
     *
     * @param Request $request Current request
     * @param string $id Id of the question
     *
     * @return View
     */
    public function editAnswerCommentAction(Request $request, $answerId, $commentId)
    {
        $answer = $this->get('stack_exchange.manager.answer')->findAnswerById($answerId);
        $comment = $this->get('stack_exchange.manager.answer_comment')->findCommentById($commentId);

        if (null === $answer || null === $comment) {
            throw new NotFoundHttpException(sprintf("Sorry, no comment or answer was found"));
        }

        $commentManager = $this->get('stack_exchange.manager.answer_comment');

        $user = $this->getUser();

        $form = $this->get('stack_exchange.form_factory.comment')->createForm(
            $this->generateUrl('stack_exchange_edit_answer_comment', ['commentId' => $commentId, 'answerId' => $answerId])
        );
        $form->setData($comment);
        $form->handleRequest($request);

        if ($form->isValid()) {

            $comment->setAuthor($user);
            $commentManager->saveComment($comment);

            return $this->getViewHandler()->handle($this->onCreateCommentSuccess($form, $comment->getAnswer()->getQuestion()->getId()));
        }

        $view = View::create()
            ->setData(array(
                'id' => $answer->getId(),
                'form' => $form->createView()
            ))
            ->setTemplate(new TemplateReference('StackExchangeBundle', 'Comment', 'comment_new'));

        return $this->getViewHandler()->handle($view);
    }

    /**
     * Action executed when a vote was succesfully created.
     *
     * @param FormInterface $form Form with the error
     * @param string $id Id of the thread
     *
     * @return View
     * @todo Think about what to show. For now the new score of the comment.
     */
    protected function onCreateCommentSuccess(FormInterface $form, $id)
    {
        //TODO change path
        return View::createRouteRedirect('question', array('id' => $id), 201);
    }

    /**
     * Returns a HTTP_BAD_REQUEST response when the form submission fails.
     *
     * @param FormInterface $form Form with the error
     * @param string $id Id of the thread
     *
     * @return View
     */
    protected function onCreateCommentError(FormInterface $form, $id)
    {
        $view = View::create()
            ->setStatusCode(400)
            ->setData(array(
                'id' => $id,
                'form' => $form,
            ))
            ->setTemplate(new TemplateReference('StackExchangeBundle', 'Comment', 'comment_new'));

        return $view;
    }

}