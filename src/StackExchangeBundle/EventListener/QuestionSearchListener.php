<?php
/**
 * Created by Lezas.
 * Date: 2017-03-10
 * Time: 20:58
 */

namespace StackExchangeBundle\EventListener;

use ONGR\ElasticsearchBundle\Collection\Collection;
use ONGR\ElasticsearchBundle\Service\Manager;
use Psr\Log\LoggerInterface;
use StackExchangeBundle\Document\QuestionDocument;
use StackExchangeBundle\Document\TagDocument;
use StackExchangeBundle\Entity\Tag;
use StackExchangeBundle\Event\QuestionEvent;
use StackExchangeBundle\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class QuestionSearchListener implements EventSubscriberInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    private $ESManager;

    public function __construct(LoggerInterface $logger, Manager $manager)
    {
        $this->logger = $logger;
        $this->ESManager = $manager;
    }


    public function createDocument(QuestionEvent $event)
    {
        if (!$this->ESManager->getClient()->ping()) {
            return;
        }
        $question = $event->getQuestion();

        $questionDoc = new QuestionDocument();
        $questionDoc->setId($question->getId());
        $questionDoc->setTitle($question->getTitle());
        $questionDoc->setQuestionId($question->getId());
        $questionDoc->setBody($question->getText());

        /** @var Tag $tag */
        foreach ($question->getTags() as $tag) {
            $tagDocument = new TagDocument();
            $tagDocument->setTitle($tag->getName());
            $questionDoc->addTag($tagDocument);
        }
        $manager = $this->ESManager;
        $manager->persist($questionDoc);
        $manager->commit();
    }

    public function updateDocument(QuestionEvent $event)
    {
        if (!$this->ESManager->getClient()->ping()) {
            return;
        }
        $question = $event->getQuestion();

        $questionDoc = $this->ESManager->find('StackExchangeBundle:QuestionDocument', $question->getId());

        if (null == $questionDoc) {
            return;
        }

        /** @var QuestionDocument $questionDoc */
        //Need to reset tags
        $questionDoc->setTags(new Collection());

        $questionDoc->setId($question->getId());
        $questionDoc->setTitle($question->getTitle());
        $questionDoc->setQuestionId($question->getId());
        $questionDoc->setBody($question->getText());

        /** @var Tag $tag */
        foreach ($question->getTags() as $tag) {
            $tagDocument = new TagDocument();
            $tagDocument->setTitle($tag->getName());
            $questionDoc->addTag($tagDocument);
        }
        $manager = $this->ESManager;
        $manager->persist($questionDoc);
        $manager->commit();
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            Events::QUESTION_POST_PERSIST => 'createDocument',
            Events::QUESTION_POST_UPDATE => 'updateDocument'
        ];
    }
}