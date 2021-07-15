<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Events;
use App\Repository\BonPlanRepository;
use App\Repository\CodePromoRepository;
use App\Repository\CommentaireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @param CodePromoRepository $codePromoRepository
     * @param BonPlanRepository $bonPlanRepository
     * @return Response
     */
    public function index(CodePromoRepository $codePromoRepository,
                          BonPlanRepository $bonPlanRepository): Response
    {


        $codePromos = $codePromoRepository->findALaUne();
        $bonPlans = $bonPlanRepository->findALaUne();

        return $this->render('home/home.html.twig', [
            'bonPlans' => $bonPlans,
            'codePromos' => $codePromos,
        ]);
    }
    /**
     * @Route("/home-hot", name="homeHot")
     * @param CodePromoRepository $codePromoRepository
     * @param BonPlanRepository $bonPlanRepository
     * @return Response
     */
    public function indexHot(CodePromoRepository $codePromoRepository,
                          BonPlanRepository $bonPlanRepository): Response
    {
        $hotPromo= $codePromoRepository->findHot();
        $hotBonPlan= $bonPlanRepository->findHot();

        return $this->render('home/hot.html.twig', [

            'codePromos'=>$hotPromo,
            'bonPlans'=>$hotBonPlan
        ]);
    }
    /**
     * @Route("/search", name="search_deals", methods={"GET"})
     *
     */
    public function searchDeal(Request $request, BonPlanRepository $repository, CodePromoRepository $codePromoRepository){

      $valueSearch=$_GET['searchValue'];
        $bonplans = $repository->findBySearch($valueSearch);
        $codePromos=$codePromoRepository->findBySearch($valueSearch);
        return $this->render('home/search.html.twig', [
            'codePromos'=>$codePromos,
            'bonPlans'=>$bonplans
        ]);
    }
}
