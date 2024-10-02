<?php

namespace App\EventListener;

use App\Entity\EncryptableString;
use App\Service\EncryptionService;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PostLoadEventArgs;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::postLoad, method: 'onPostLoad', entity: EncryptableString::class)]
#[AsEntityListener(event: Events::preFlush, method: 'onPreFlush', entity: EncryptableString::class)]
final class EncryptableStringEventListener
{
    public function __construct(
        private readonly EncryptionService  $encryptionService
    ) {}

    public function onPostLoad(
        EncryptableString $entity,
        PostLoadEventArgs $eventArgs
    ) : void {

        $this->encryptionService->decrypt($entity);
    }

    public function onPreFlush(
        EncryptableString $entity,
        PreFlushEventArgs $eventArgs
    ) : void {

        $this->encryptionService->encrypt($entity);
    }

}