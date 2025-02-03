<?php

namespace App\Trait;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\Service\Attribute\Required;

trait UserProviderTrait
{
    private Security $security;

    #[Required]
    public function setSecurity(Security $security): void {
        $this->security = $security;
    }

    public function getUser(): UserInterface|null {
        return $this->security->getUser();
    }
}