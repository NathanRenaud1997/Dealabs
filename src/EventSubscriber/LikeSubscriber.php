<?php

namespace App\EventSubscriber;

use App\Repository\BadgeRepository;
use App\Repository\BonPlanRepository;
use App\Repository\CodePromoRepository;
use App\Repository\LikerRepository;
use App\Repository\UserRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class LikeSubscriber implements EventSubscriberInterface

{
    private $likeRepos;
    private $Badgerepository;
    private $userRepos;

    public function __construct(LikerRepository $likeRepos,BadgeRepository $badgeRepository,UserRepository $userRepository)
    {
        $this->BadgeRepository=$badgeRepository;
        $this->likeRepos= $likeRepos;
        $this->userRepos= $userRepository;
    }
    public function onUserVote($event)
    {
        $badge= $this->BadgeRepository->find(1);
        $manager = $event->getArgument("Manager");
        $user= $event->getSubject();
        $total= $this->likeRepos->likeByUser($user)[0][1];
        $haveBadge= $this->userRepos->findBadge($badge);
        if($total>=10 && $haveBadge[0][1]==0){
            $user->addBadge($badge);
            $manager->persist($user);
            $manager->flush();
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            'user.vote' => 'onUserVote',
        ];
    }
}
