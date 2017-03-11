<?php

namespace StackExchangeBundle\EventListener;

use StackExchangeBundle\Event\QuestionEvent;
use StackExchangeBundle\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class QuestionAcceptedAnswerListener implements EventSubscriberInterface
{
    public function aetQuestionAnswerAccepted(QuestionEvent $event)
    {
        $question = $event->getQuestion();

        if ($question->isAnswered() == false) {
            $question->setAnswered(true);
        }

    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            Events::QUESTION_ANSWER_ACCEPTED => 'aetQuestionAnswerAccepted',
        );
    }
}