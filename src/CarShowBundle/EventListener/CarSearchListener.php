<?php
/**
 * Created by Lezas.
 * Date: 2017-03-10
 * Time: 20:58
 */

namespace CarShowBundle\EventListener;

use CarShowBundle\Document\CarProfileDocument;
use CarShowBundle\Document\PostDocument;
use CarShowBundle\Entity\Car;
use CarShowBundle\Entity\Post;
use CarShowBundle\Event\CarEvent;
use CarShowBundle\Events;
use ONGR\ElasticsearchBundle\Collection\Collection;
use ONGR\ElasticsearchBundle\Service\Manager;
use Psr\Log\LoggerInterface;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CarSearchListener implements EventSubscriberInterface
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


    public function createDocument(CarEvent $event)
    {

        if (!$this->ESManager->getClient()->ping()) {
            return;
        }

        /** @var Car $car */
        $car = $event->getCar();

        $carDoc = new CarProfileDocument();
        $carDoc->setId($car->getId());
        $carDoc->setCarId($car->getId());
        $carDoc->setBrand($car->getBrand());
        $carDoc->setModel($car->getModel());
        $carDoc->setYear($car->getYear()->format('Y-m-d H:i:s'));
        $carDoc->setBodyType($car->getBodyType());
        $carDoc->setPowerTrain($car->getPowerTrain());
        $carDoc->setEngineCapacity($car->getEngineCapacity());
        $carDoc->setFuelType($car->getFuelType());
        $carDoc->setAdditionalInfo($car->getAdditionalInfo());
        $carDoc->setCreatedAt($car->getCreatedAt());
        $carDoc->setPower($car->getPower());
        $carDoc->setOwner($car->getUser()->getUsername());

        /** @var Post $post */
        foreach ($car->getPosts() as $post) {
            $postDocument = new PostDocument();
            $postDocument->setTitle($post->getTitle());
            $postDocument->setMileage($post->getMileage());
            $postDocument->setText($post->getText());
            $postDocument->setCreatedAt($post->getDate()->format('Y-m-d H:i:s'));
            $carDoc->addPost($postDocument);
        }

        $manager = $this->ESManager;
        $manager->persist($carDoc);
        $manager->commit();

    }

    public function updateDocument(CarEvent $event)
    {
        if (!$this->ESManager->getClient()->ping()) {
            return;
        }
        /** @var Car $car */
        $car = $event->getCar();

        $carDoc = $this->ESManager->find('CarShowBundle:CarProfileDocument', $car->getId());

        if (null == $carDoc) {
            return;
        }

        /** @var CarProfileDocument $questionDoc */
        //Need to reset tags

        $carDoc = new CarProfileDocument();
        $carDoc->setId($car->getId());
        $carDoc->setCarId($car->getId());
        $carDoc->setBrand($car->getBrand());
        $carDoc->setModel($car->getModel());
        $carDoc->setYear($car->getYear()->format('Y-m-d H:i:s'));
        $carDoc->setBodyType($car->getBodyType());
        $carDoc->setPowerTrain($car->getPowerTrain());
        $carDoc->setEngineCapacity($car->getEngineCapacity());
        $carDoc->setFuelType($car->getFuelType());
        $carDoc->setAdditionalInfo($car->getAdditionalInfo());
        $carDoc->setCreatedAt($car->getCreatedAt());
        $carDoc->setPower($car->getPower());
        $carDoc->setOwner($car->getUser()->getUsername());

        /** @var Post $post */
        foreach ($car->getPosts() as $post) {
            $postDocument = new PostDocument();
            $postDocument->setTitle($post->getTitle());
            $postDocument->setMileage($post->getMileage());
            $postDocument->setText($post->getText());
            $postDocument->setCreatedAt($post->getDate()->format('Y-m-d H:i:s'));
            $carDoc->addPost($postDocument);
        }

        $manager = $this->ESManager;
        $manager->persist($carDoc);
        $manager->commit();
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            Events::CAR_POST_PERSIST => 'createDocument',
            Events::CAR_POST_UPDATE => 'updateDocument',
            Events::CAR_DELETE => 'removeDocument'
        ];
    }

    public function removeDocument(CarEvent $event)
    {
        if (!$this->ESManager->getClient()->ping()) {
            return;
        }
        /** @var Car $car */
        $car = $event->getCar();

        $carDoc = $this->ESManager->find('CarShowBundle:CarProfileDocument', $car->getId());

        if (null == $carDoc) {
            return;
        }

        $manager = $this->ESManager;
        $manager->remove($carDoc);
        $manager->commit();
    }
}