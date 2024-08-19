<?php

namespace App\EventListener;

use App\Service\UserActivityLogger;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Core\User\UserInterface;

class LoginListener
{
    private $activityLogger;

    public function __construct(UserActivityLogger $activityLogger)
    {
        $this->activityLogger = $activityLogger;
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        $user = $event->getAuthenticationToken()->getUser();
        // dd($user);
        if ($user instanceof UserInterface) {
            $this->activityLogger->log('login', 'login', $user->getUsername() . ' connecté.');
        }
    }
}
