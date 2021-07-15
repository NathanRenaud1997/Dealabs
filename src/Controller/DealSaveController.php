<?php


namespace App\Controller;


use App\Entity\User;
use App\Repository\BonPlanRepository;
use App\Repository\CodePromoRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\Security;

class DealSaveController
{
    private $reposBonplan;
    private $reposCodepromo;
    private $userRepository;
    public function __construct(BonPlanRepository $bonPlanRepository, CodePromoRepository $codePromoRepository,UserRepository $security)
    {
        $this->reposBonplan = $bonPlanRepository;
        $this->reposCodepromo = $codePromoRepository;
        $this->userRepository=$security;
    }

    public function __invoke(User $user)
    {
        $bonPlans = $user->getBonPlanSave();
        $codePromos = $user->getCodePromoSaves();
        $dealCollection = new ArrayCollection([
            "codepromo" => $codePromos,
            "bonplan" => $bonPlans
        ]);


        return $dealCollection;

    }
}