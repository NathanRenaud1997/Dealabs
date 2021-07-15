<?php


namespace App\Controller;


use App\Entity\Alerte;
use App\Entity\BonPlan;
use App\Entity\User;
use App\Events;
use App\Form\AlerteType;
use App\Form\BonPlanType;
use App\Repository\AlerteRepository;
use App\Repository\BadgeRepository;
use App\Repository\BonPlanRepository;
use App\Repository\CodePromoRepository;
use App\Repository\CommentaireRepository;
use App\Repository\LikerRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class MyAccountController extends AbstractController
{
    /**
     * @Route("/mon-compte/apercu", name="mon_compte_apercu")
     * @param BonPlanRepository $bonPlanRepository
     * @param LikerRepository $likerRepository
     * @param CommentaireRepository $commentaireRepository
     * @param CodePromoRepository $codePromoRepository
     * @param UserRepository $userRepository
     * @param Security $security
     * @return Response
     */
    public function index(BonPlanRepository $bonPlanRepository,
                          LikerRepository $likerRepository,
                          CommentaireRepository $commentaireRepository,
                          CodePromoRepository $codePromoRepository,
                          UserRepository $userRepository,
                          Security $security): Response
    {
        $countBonPlan = $bonPlanRepository->countByUserId($security->getUser()->getId());
        $countCodePromo = $codePromoRepository->countByUserId($security->getUser()->getId());
        $finalCount = $countBonPlan[0][1] + $countCodePromo[0][1];
        $countLike = $likerRepository->likeByUser($this->getUser())[0][1];
        $countComm = $commentaireRepository->countCommentByUser($this->getUser())[0][1];
        $countCommentaries = $userRepository
            ->find($security->getUser())
            ->getCommentaires()
            ->count();
        $hottestCodePromo = $codePromoRepository
            ->selectHottestCodePromoByUserId($security->getUser())[0][1];
        $hottestBonPlan = $bonPlanRepository->selectHottestBonPlanByUserId($security->getUser())[0][1];
        $hottestRate = $hottestCodePromo > $hottestBonPlan ? $hottestCodePromo : $hottestBonPlan;
        $averageRateCodePromo = $codePromoRepository->selectAverageRateByUserId($security->getUser())[0][1];
        $averageRateBonPlan = $bonPlanRepository->selectAverageRateByUserId($security->getUser())[0][1];
        $averageRateDeal = ($averageRateBonPlan + $averageRateCodePromo) / 2;
        $countDeals = $countBonPlan[0][1] + $countCodePromo[0][1];
        $countHotBonPlan = $bonPlanRepository->countHotByUserId($security->getUser())[0][1];
        $countHotCodePromo = $codePromoRepository->countHotByUserId($security->getUser())[0][1];
        $hotPercentage = ($countHotBonPlan + $countHotCodePromo) / $countDeals * 100;
        $progressCountComm = $this->getProgress($countComm);
        $progressCountDeal = $this->getProgress($countDeals);
        $progressCountLike = $this->getProgress($countLike);

        return $this->render("my_account/overview.html.twig", [
            'count' => $finalCount,
            'progressDeal' => $progressCountDeal,
            'LikeUser' => $progressCountLike,
            'CommUser' => $progressCountComm,
            'countDeals' => $countDeals,
            'countCommentaries' => $countCommentaries,
            'hottestRate' => $hottestRate,
            'averageRateDeal' => $averageRateDeal,
            'hotPercentage' => $hotPercentage
        ]);
    }

    /**
     * @Route("/mon-compte/published-deals", name="published_deals")
     * @param BonPlanRepository $bonPlanRepository
     * @param CodePromoRepository $codePromoRepository
     * @param Security $security
     * @return Response
     */
    public function publishedDeals(BonPlanRepository $bonPlanRepository,
                                   CodePromoRepository $codePromoRepository,
                                   Security $security): Response
    {
        $countBonPlan = $bonPlanRepository->countByUserId($security->getUser()->getId());
        $countCodePromo = $codePromoRepository->countByUserId($security->getUser()->getId());
        $countDeals = $countBonPlan[0][1] + $countCodePromo[0][1];
        $bonPlans = $bonPlanRepository->findByUserId($security->getUser()->getId());
        $codePromos = $codePromoRepository->findByUserId($security->getUser()->getId());
        return $this->render("my_account/published-deal.html.twig", [
            "bonPlans" => $bonPlans,
            "codePromos" => $codePromos,
            'countDeals' => $countDeals
        ]);
    }

    /**
     * @Route("/mon-compte/saved_deals", name="saved_deals")
     * @param BonPlanRepository $bonPlanRepository
     * @param CodePromoRepository $codePromoRepository
     * @param Security $security
     * @return Response
     */
    public function savedDeals(BonPlanRepository $bonPlanRepository,
                               CodePromoRepository $codePromoRepository,
                               Security $security): Response
    {
        $bonPlans = $this->getUser()->getBonPlanSave();
        $codePromos = $this->getUser()->getCodePromoSaves();

        $countBonPlan = $bonPlanRepository->countByUserId($security->getUser()->getId());
        $countCodePromo = $codePromoRepository->countByUserId($security->getUser()->getId());
        $countDeals = $countBonPlan[0][1] + $countCodePromo[0][1];
        return $this->render("my_account/DealSave.html.twig", [
            "bonPlans" => $bonPlans,
            "codePromos" => $codePromos,
            'countDeals' => $countDeals
        ]);
    }

    /**
     * @Route("/mon-compte/my-alerts", name="my_alerts")
     * @param Request $request
     * @param BonPlanRepository $bonPlanRepository
     * @param CodePromoRepository $codePromoRepository
     * @param AlerteRepository $alerteRepository
     * @param Security $security
     * @return Response
     */
    public function myAlerts(Request $request,
                             BonPlanRepository $bonPlanRepository,
                             CodePromoRepository $codePromoRepository,
                             AlerteRepository $alerteRepository,
                             Security $security): Response
    {
        $countBonPlan = $bonPlanRepository->countByUserId($security->getUser()->getId());
        $countCodePromo = $codePromoRepository->countByUserId($security->getUser()->getId());
        $countDeals = $countBonPlan[0][1] + $countCodePromo[0][1];

        $alertes = $bonPlanRepository->findAlertByUserId($this->getUser());
        $alertesCodePromo = $codePromoRepository->findAlertByUserId($this->getUser());


        return $this->render("my_account/my_alerts.html.twig", [
            'countDeals' => $countDeals,
            'bonPlans' => $alertes,
            'codePromos'=>$alertesCodePromo
        ]);
    }

    /**
     * @Route("/mon-compte/manage-alerts", name="manage_alerts")
     * @param BonPlanRepository $bonPlanRepository
     * @param CodePromoRepository $codePromoRepository
     * @param Security $security
     * @return Response
     */
    public function manageMyAlerts(Request $request,
                                   BonPlanRepository $bonPlanRepository,
                                   CodePromoRepository $codePromoRepository,
                                   AlerteRepository $alerteRepository,
                                   Security $security): Response
    {
        $alerte = new Alerte();
        $form = $this->createForm(AlerteType::class, $alerte);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $alerte->setUser($this->getUser());
            $manager->persist($alerte);
            $manager->flush();
            return $this->redirectToRoute('manage_alerts');
        }

        $countBonPlan = $bonPlanRepository->countByUserId($security->getUser()->getId());
        $countCodePromo = $codePromoRepository->countByUserId($security->getUser()->getId());
        $countDeals = $countBonPlan[0][1] + $countCodePromo[0][1];
        $alertes = $alerteRepository->findByUserId($this->getUser());

        return $this->render("my_account/manage_alerts.html.twig", [
            'countDeals' => $countDeals,
            'form' => $form->createView(),
            'alertes' => $alertes
        ]);
    }

    /**
     * @param $count
     * @return float|int
     */
    public function getProgress($count)
    {
        $progressCount = $count * 100 / 10;
        return $progressCount;
    }
}