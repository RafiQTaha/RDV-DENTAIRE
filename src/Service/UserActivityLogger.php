<?php

namespace App\Service;

use App\Entity\UserActivity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class UserActivityLogger
{
    private $em;
    private $security;

    public function __construct(EntityManagerInterface $em, Security $security,)
    {
        $this->em = $em;
        $this->security = $security;
    }

    public function log($action, $page, $description = null)
    {
        $user = $this->security->getUser();

        if ($user) {
            $activity = new UserActivity();
            $activity->setUser($user);
            $activity->setAction($action);
            $activity->setPage($page);
            $activity->setDescription($description);
            $activity->setCreated(new \DateTime());

            $this->em->persist($activity);
            $this->em->flush();
        }
    }
}
