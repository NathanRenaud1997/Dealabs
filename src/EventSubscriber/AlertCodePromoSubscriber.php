<?php

namespace App\EventSubscriber;

use App\Repository\AlerteRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AlertCodePromoSubscriber implements EventSubscriberInterface
{
    private $alertRepos;


    public function __construct(AlerteRepository $alertRepos)
    {
        $this->alertRepos= $alertRepos;
    }
    public function onUserAlertCodepromo($event)
    {
        $user= $event->getSubject();
        $keywords = $this->alertRepos->findAllKeywordByUser($user)[0];

        $manager = $event->getArgument("Manager");
        $codepromo=  $event->getArgument("codepromo");
        foreach ($keywords as $keyword){
            $splitKeyword= explode(" ",$keyword);
            foreach ($splitKeyword as $key){
                if($codepromo->getTitle()===$key){
                    $user->addAlertesCodePromo($codepromo);
                    $manager->persist($user);
                    $manager->flush();
                }
            }

        }

    }

    public static function getSubscribedEvents()
    {
        return [
            'user.alert.codepromo' => 'onUserAlertCodepromo',
        ];
    }
}
