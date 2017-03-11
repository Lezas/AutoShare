<?php

// /src/ContentBundle/EntityListener/PostListener.php

namespace CarShowBundle\Entity\Listener;

use CarShowBundle\Document\CarProfile;
use CarShowBundle\Entity\Car;
use Doctrine\ORM\Event\LifecycleEventArgs;
use ONGR\ElasticsearchBundle\Service\Manager;
use Psr\Log\LoggerInterface;

class AutoListener
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


    public function postPersist(Car $auto, LifecycleEventArgs $event)
    {
        $this->saveSearch($auto);
    }

    public function postRemove(Car $auto, LifecycleEventArgs $event)
    {
        $autoDoc = new CarProfile();
        $autoDoc->id = $auto->getId();
        $autoDoc->name = $auto->getBrand() . ' ' . $auto->getModel() . ' ';

        $this->ESManager->remove($autoDoc);
        $this->ESManager->commit();
    }

    public function postUpdate(Car $auto, LifecycleEventArgs $event)
    {
        $this->saveSearch($auto);
    }


    protected function saveSearch(Car $auto)
    {
        $autoDoc = new CarProfile();
        $autoDoc->id = $auto->getId();
        $autoDoc->name = $auto->getBrand() . ' ' . $auto->getModel() . ' ';

        /*
        $this->ESManager->persist($autoDoc);
        $this->ESManager->commit();
        */
    }
}