<?php
namespace AppBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;

class TimestampableSubscriber implements EventSubscriber {

    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return array
     */
    public function getSubscribedEvents() {
        return array(
            Events::prePersist,
            Events::preUpdate
        );
    }

    /**
     * @param LifecycleEventArgs
     */
    public function prePersist(LifecycleEventArgs $eventArgs) {
        $entity = $eventArgs->getEntity();
        $entity->setCreatedAt(new \DateTime('now', new \DateTimeZone('UTC')));
    }

    /**
     * @param LifecycleEventArgs
     */
    public function preUpdate(LifecycleEventArgs $eventArgs) {
        $entity = $eventArgs->getEntity();
        $entity->setUpdatedAt(new \DateTime('now', new \DateTimeZone('UTC')));
    }
}