<?php

namespace App\EventListener;

use App\Entity\Abstract\DateLoggableEntity;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::preFlush, method: 'onPreFlush', entity: DateLoggableEntity::class)]
final class DateLoggableEntityPreFlushEventListener
{
    public function onPreFlush(
        DateLoggableEntity $entity,
        PreFlushEventArgs $eventArgs
    ) : void {

        $entity->_onSave();
    }
}