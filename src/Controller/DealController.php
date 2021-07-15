<?php


namespace App\Controller;


use App\Repository\BonPlanRepository;
use App\Repository\CodePromoRepository;
use Doctrine\Common\Collections\ArrayCollection;

class DealController
{
    private $reposBonplan;
    private $reposCodepromo;
    public function __construct(BonPlanRepository $bonPlanRepository,CodePromoRepository $codePromoRepository)
    {
        $this->reposBonplan=$bonPlanRepository;
        $this->reposCodepromo=$codePromoRepository;
    }

    public function __invoke()
    {
        $codepromo= $this->reposCodepromo->findALaUne();
        $bonplan= $this->reposBonplan->findALaUne();
        $dealCollection= new ArrayCollection([
            "codepromo"=>$codepromo,
            "bonplan" =>$bonplan
        ]);


        return $dealCollection;
    }

}