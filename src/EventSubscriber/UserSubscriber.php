<?php

namespace App\EventSubscriber;

use App\Entity\User;
use App\Repository\BadgeRepository;
use App\Repository\BonPlanRepository;
use App\Repository\CodePromoRepository;
use App\Repository\UserRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserSubscriber implements EventSubscriberInterface
{
    private $CodePromorepository;
    private $BonPlanrepository;
    private $Badgerepository;
    private $userRepos;

    public function __construct(CodePromoRepository $CodePromorepository,BonPlanRepository $reposBonPLan,BadgeRepository $badgeRepository,UserRepository $userRepository)
    {
         $this->BadgeRepository=$badgeRepository;
         $this->BonPlanrepository=$reposBonPLan;
         $this->CodePromorepository= $CodePromorepository;
         $this->userRepos= $userRepository;
    }
    public function onUserDeals($event)
    {
        $badge= $this->BadgeRepository->find(2);
        $manager = $event->getArgument("Manager");
        $user= $event->getSubject();

        $countCodePromo=$this->CodePromorepository->countByUserId($user);
        $countBonPlan= $this->BonPlanrepository->countByUserId($user);
        $total= $countBonPlan[0][1] + $countCodePromo[0][1];
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
            'user.deals' => 'onUserDeals',
        ];
    }
}
