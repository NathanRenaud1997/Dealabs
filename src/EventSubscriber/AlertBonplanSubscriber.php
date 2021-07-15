<?php

namespace App\EventSubscriber;

use App\Repository\AlerteRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AlertBonplanSubscriber implements EventSubscriberInterface
{
    private $alertRepos;


    public function __construct(AlerteRepository $alertRepos)
    {
        $this->alertRepos= $alertRepos;
    }

    public function onUserAlertBonplan($event)
    {
        $user= $event->getSubject();
        $keywords = $this->alertRepos->findAllKeywordByUser($user)[0];

        $manager = $event->getArgument("Manager");
        $bonplan=  $event->getArgument("bonplan");
        foreach ($keywords as $keyword){
            $splitKeyword= explode(" ",$keyword);
            foreach ($splitKeyword as $key){

                if($bonplan->getTitle()===$key){
                    //dd($key);
                    $user->addAlertesBonPlan($bonplan);
                    $manager->persist($user);
                    $manager->flush();
                }
            }

        }

    }

    public static function getSubscribedEvents()
    {
        return [
            'user.alert.bonplan' => 'onUserAlertBonplan',
        ];
    }
}
