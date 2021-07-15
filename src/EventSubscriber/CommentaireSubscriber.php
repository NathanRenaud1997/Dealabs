<?php

namespace App\EventSubscriber;

use App\Repository\BadgeRepository;
use App\Repository\BonPlanRepository;
use App\Repository\CodePromoRepository;
use App\Repository\CommentaireRepository;
use App\Repository\UserRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CommentaireSubscriber implements EventSubscriberInterface
{
    private $Badgerepository;
    private $userRepos;
    private $commentaireRepos;

    public function __construct(BadgeRepository $badgeRepository, UserRepository $userRepository, CommentaireRepository $commentaireRepos)
    {
        $this->BadgeRepository=$badgeRepository;
        $this->userRepos= $userRepository;
        $this->commentaireRepos= $commentaireRepos;
    }
    public function onUserCommentaire($event)
    {
        $badge= $this->BadgeRepository->find(3);
        $manager = $event->getArgument("Manager");
        $user= $event->getSubject();
        $count= $this->commentaireRepos->countCommentByUser($user);
        $haveBadge= $this->userRepos->findBadge($badge);

        if($count[0][1]>=10 && $haveBadge[0][1]==0){
            $user->addBadge($badge);
            $manager->persist($user);
            $manager->flush();
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            'user.commentaire' => 'onUserCommentaire',
        ];
    }
}
