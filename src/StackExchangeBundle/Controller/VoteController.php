<?php

namespace StackExchangeBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Bundle\FrameworkBundle\Templating\TemplateReference;

class VoteController extends FOSRestController
{

    /**
     * Presents the form to use to create a new Vote for a Question.
     *
     * @param Request $request Current request
     * @param string $id Id of the Question
     *
     * @return View|Response
     */
    public function newQuestionVoteAction(Request $request, $id)
    {
        $question = $this->get('stack_exchange.manager.question')->findQuestionById($id);

        if (null === $question) {
            throw new NotFoundHttpException(sprintf("No question with id '%s' found ", $question->getId()));
        }

        $vote = $this->get('stack_exchange.manager.question_vote')->createVote($question, $this->getUser());
        $vote->setValue($request->query->get('value', 1));

        $form = $this->get('stack_exchange.form_factory.vote')->createForm($this->generateUrl('stack_exchange_post_question_votes', ['id' => $id]));
        $form->setData($vote);

        $view = View::create()
            ->setData(array(
                'id' => $id,
                'form' => $form->createView()
            ))
            ->setTemplate(new TemplateReference('StackExchangeBundle', 'Vote', 'vote_new'));

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
    public function postQuestionVotesAction(Request $request, $id)
    {
        $question = $this->get('stack_exchange.manager.question')->findQuestionById($id);

        if (null === $question) {
            throw new NotFoundHttpException(sprintf("No question with id '%s' found ", $question->getId()));
        }

        $voteManager = $this->get('stack_exchange.manager.question_vote');

        $user = $this->getUser();
        $doesUserVoted = $voteManager->doesUserVoted($user, $question);

        $vote = $voteManager->createVote($question, $user);

        $form = $this->get('stack_exchange.form_factory.vote')->createForm($this->generateUrl('stack_exchange_post_question_votes', ['id' => $id]));
        $form->setData($vote);
        $form->handleRequest($request);

        if ($form->isValid()) {

            if ($doesUserVoted) {
                $newValue = $vote->getValue();
                $oldValue = $voteManager->findVoteBy(['user' => $user])->getValue();

                //TODO need better handling than this...
                //case if new pos, old neg
                if ($newValue > 0 and $oldValue < 0) {
                    $question->incrementScore(1);
                }
                if ($newValue < 0 and $oldValue > 0) {
                    $question->incrementScore(-1);
                }
                if ($newValue == $oldValue) {
                    throw new NotFoundHttpException(sprintf("You have already voted for question: ", $question->getId()));
                }

            }
            $voteManager->saveVote($vote);

            return $this->getViewHandler()->handle($this->onCreateVoteSuccess($form, $id));
        }

        return $this->getViewHandler()->handle($this->onCreateVoteError($form, $id));
    }

    /**
     * Get the votes of a Question.
     *
     * @param string $id Id of the question
     *
     * @return View
     */
    public function getQuestionVotesAction($id)
    {
        $question = $this->get('stack_exchange.manager.question')->findQuestionById($id);

        if (null === $question) {
            throw new NotFoundHttpException(sprintf("No question with id '%s' found ", $question->getId()));
        }

        $view = View::create()
            ->setData(array(
                'questionScore' => $question->getScore(),
            ))
            ->setTemplate(new TemplateReference('StackExchangeBundle', 'Vote', 'question_votes'));

        return $this->getViewHandler()->handle($view);
    }

    /**
     * Presents the form to use to create a new Vote for a Answer.
     *
     * @param Request $request Current request
     * @param string $id Id of the Answer
     *
     * @return View|Response
     */
    public function newAnswerVoteAction(Request $request, $id)
    {
        $answer = $this->get('stack_exchange.manager.answer')->findAnswerById($id);

        if (null === $answer) {
            throw new NotFoundHttpException(sprintf("No answer with id '%s' found ", $answer->getId()));
        }

        $vote = $this->get('stack_exchange.manager.answer_vote')->createVote($answer, $this->getUser());
        $vote->setValue($request->query->get('value', 1));

        $form = $this->get('stack_exchange.form_factory.vote')->createForm($this->generateUrl('stack_exchange_post_answer_votes', ['id' => $id]));
        $form->setData($vote);

        $view = View::create()
            ->setData(array(
                'id' => $id,
                'form' => $form->createView()
            ))
            ->setTemplate(new TemplateReference('StackExchangeBundle', 'Vote', 'vote_new'));

        return $this->getViewHandler()->handle($view);
    }

    /**
     * @param Request $request Current request
     * @param string $id Id of an answer
     *
     * @return View
     */
    public function postAnswerVotesAction(Request $request, $id)
    {
        $answer = $this->get('stack_exchange.manager.answer')->findAnswerById($id);

        if (null === $answer) {
            throw new NotFoundHttpException(sprintf("No question with id '%s' found ", $answer->getId()));
        }

        $voteManager = $this->get('stack_exchange.manager.answer_vote');

        $user = $this->getUser();
        $doesUserVoted = $voteManager->doesUserVoted($user, $answer);

        $vote = $voteManager->createVote($answer, $user);

        $form = $this->get('stack_exchange.form_factory.vote')->createForm($this->generateUrl('stack_exchange_post_answer_votes', ['id' => $id]));
        $form->setData($vote);
        $form->handleRequest($request);

        if ($form->isValid()) {

            if ($doesUserVoted) {
                $newValue = $vote->getValue();
                $oldValue = $voteManager->findVoteBy(['user' => $user])->getValue();

                //TODO need better handling than this...
                //case if new pos, old neg
                if ($newValue > 0 and $oldValue < 0) {
                    $answer->incrementScore(1);
                }
                if ($newValue < 0 and $oldValue > 0) {
                    $answer->incrementScore(-1);
                }
                if ($newValue == $oldValue) {
                    throw new NotFoundHttpException(sprintf("You have already voted for answer: ", $answer->getId()));
                }

            }
            $voteManager->saveVote($vote);

            return $this->getViewHandler()->handle(View::createRouteRedirect('stack_exchange_get_answer_votes', array('id' => $id), 201));
        }

        return $this->getViewHandler()->handle($this->onCreateVoteError($form, $id));
    }

    /**
     * Get the votes of a comment.
     *
     * @param string $id Id of the question
     *
     * @return View
     */
    public function getAnswerVotesAction($id)
    {
        $answer = $this->get('stack_exchange.manager.answer')->findAnswerById($id);

        if (null === $answer) {
            throw new NotFoundHttpException(sprintf("No question with id '%s' found ", $answer->getId()));
        }

        $view = View::create()
            ->setData(array(
                'answerScore' => $answer->getScore(),
            ))
            ->setTemplate(new TemplateReference('StackExchangeBundle', 'Vote', 'answer_votes'));

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
    protected function onCreateVoteSuccess(FormInterface $form, $id)
    {
        //TODO change path
        return View::createRouteRedirect('stack_exchange_get_question_votes', array('id' => $id), 201);
    }

    /**
     * Returns a HTTP_BAD_REQUEST response when the form submission fails.
     *
     * @param FormInterface $form Form with the error
     * @param string $id Id of the thread
     *
     * @return View
     */
    protected function onCreateVoteError(FormInterface $form, $id)
    {
        $view = View::create()
            ->setStatusCode(400)
            ->setData(array(
                'id' => $id,
                'form' => $form,
            ))
            ->setTemplate(new TemplateReference('StackExchangeBundle', 'Vote', 'vote_new'));

        return $view;
    }

}