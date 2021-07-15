<?php

namespace App\Controller;

use App\Entity\BonPlan;
use App\Entity\Commentaire;
use App\Entity\Liker;
use App\Events;
use App\Form\BonPlanType;
use App\Form\CommentaireType;
use App\Repository\BonPlanRepository;
use App\Repository\LikerRepository;
use App\Repository\UserRepository;
use DateTime;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface as EventDispatcher ;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class BonPlanController extends AbstractController
{
    /**
     * @Route("/bon-plan", name="bon_plan")
     * @param BonPlanRepository $bonPlanRepository
     * @return Response
     */
    public function index(BonPlanRepository $bonPlanRepository): Response
    {
        $bonplan = $bonPlanRepository->findHot();
        return $this->render('bon_plan/index.html.twig', [
            'bon_plans' => $bonplan,
            'controller_name' => 'BonPlanController',
        ]);
    }

    /**
     * @Route("/bon-plan/new", name="new_bon_plan")
     * @param Request $request
     * @param BonPlanRepository $repository
     * @return Response
     */
    public function new(Request $request, BonPlanRepository $repository, EventDispatcher $eventDispatcher): Response
    {
        $bonPlan = new BonPlan();
        $form = $this->createForm(BonPlanType::class, $bonPlan);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $images = $form->get('apercu')->getData();
            if ($images) {
                $originalFilename = pathinfo($images->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $images->guessExtension();
                try {
                    $images->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {

                }
                $bonPlan->setApercu($newFilename);
            }

            $manager = $this->getDoctrine()->getManager();
            $date = new DateTime("now");
            $date->format("Y-m-d");
            $bonPlan->setDateCreation($date);
            $bonPlan->setUserCrea($this->getUser());
            $manager->persist($bonPlan);
            $manager->flush();
            $event = new GenericEvent($this->getUser(),["Manager"=>$manager]);
            $eventDispatcher->dispatch(Events::USER_POSTED, $event);
            $eventAlert = new GenericEvent($this->getUser(),["Manager"=>$manager,"bonplan"=>$bonPlan]);
            $eventDispatcher->dispatch(Events::USER_ALERT_BOONPLAN, $eventAlert);
            return $this->redirectToRoute('bon_plan');
        }
        return $this->render('bon_plan/new.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/bon-plan/edit/{id}", name="edit_bon_plan")
     * @param Request $request
     * @param BonPlanRepository $repository
     * @return Response
     */
    public function edit(Request $request, BonPlanRepository $repository, BonPlan $bonPlan): Response
    {
        $form = $this->createForm(BonPlanType::class, $bonPlan);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $images = $form->get('apercu')->getData();
            if ($images) {
                $originalFilename = pathinfo($images->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $images->guessExtension();
                if ($newFilename != $bonPlan->getApercu()) {
                    try {
                        $images->move(
                            $this->getParameter('images_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {

                    }
                    $bonPlan->setApercu($newFilename);
                } else {
                    $bonPlan->setApercu($bonPlan->getApercu());

                }
            }
            $manager = $this->getDoctrine()->getManager();
            $bonPlan->setDateCreation($bonPlan->getDateCreation());
            $bonPlan->setUserCrea($this->getUser());
            $manager->persist($bonPlan);
            $manager->flush();
            return $this->redirectToRoute('bon_plan');
        }
        return $this->render('bon_plan/edit.html.twig', [
            'form' => $form->createView(),
            'bonplan' => $bonPlan
        ]);
    }

    /**
     * @Route("/bon-plan/details/{id}", name="bon_plan_details")
     * @param Request $request
     * @param BonPlan $bonPlan
     * @return Response
     */
    public function details(Request $request, BonPlan $bonPlan,EventDispatcher $eventDispatcher): Response
    {
        $commentaire = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $commentaire->setDatePublication(new DateTime());
            $commentaire->setBonPlan($bonPlan);
            $commentaire->setUser($bonPlan->getUserCrea());
            $manager->persist($commentaire);
            $manager->flush();
            $event = new GenericEvent($this->getUser(),["Manager"=>$manager]);
            $eventDispatcher->dispatch(Events::USER_COMM, $event);
            return $this->redirectToRoute('bon_plan_details', ['id' => $bonPlan->getId()]);
        }

        return $this->render('bon_plan/details.html.twig', [
            'bonPlan' => $bonPlan,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/bon-plan/liker/{id}/{value}", name="bon_plan_liker")
     * @param Request $request
     * @param BonPlan $bonPlan
     * @param $value
     * @param LikerRepository $likerRepository
     * @return Response
     */
    public function liker(Request $request, BonPlan $bonPlan, $value, LikerRepository $likerRepository,EventDispatcher $eventDispatcher): Response
    {
        $likers = $likerRepository->findAll();
        $isFinded = false;
        $like = new Liker();

        foreach ($likers as $l) {
            if ($l->getUser() === $bonPlan->getUserCrea()
                && $l->getBonPlan() === $bonPlan) {
                $like = $l;

                if ($value == $l->getPlusOuMoins()) {
                    $isFinded = true;
                }
            }
        }

        if (!$isFinded) {
            $like
                ->setBonPlan($bonPlan)
                ->setUser($bonPlan->getUserCrea())
                ->setPlusOuMoins($value);

            $bonPlan->setNote($bonPlan->getNote() + $value);
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($like);
            $manager->persist($bonPlan);
            $manager->flush();
            $event = new GenericEvent($this->getUser(),["Manager"=>$manager]);
            $eventDispatcher->dispatch(Events::USER_VOTED, $event);
        }

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("bon-plan/signaler/{id}", name="bon_plan_signaler")
     * @param Request $request
     * @param MailerInterface $mailer
     * @param BonPlan $bonPlan
     * @return Response
     * @throws TransportExceptionInterface
     */
    public function bonPlanSignaler(Request $request,
                                    MailerInterface $mailer,
                                    BonPlan $bonPlan,
                                    UserRepository $userRepository,
                                    Security $security): Response
    {
        $users = $userRepository->findAll();
        foreach ($users as $user) {
            foreach ($user->getRoles() as $role) {
                if ($role === "ROLE_ADMIN" && $user !== $security->getUser()) {
                    $mail = new Email();
                    $mail->from($security->getUser()->getMail());
                    $mail->to($user->getMail());
                    $mail->subject("Signalement bon plan");
                    $message = "Le bon plan suivant ne semble pas respecter les conditions de vente de Dealabs: " . "ID: "
                        . $bonPlan->getId() . ", Titre: " . $bonPlan->getTitle();
                    $mail->text($message);
                    $mailer->send($mail);
                }
            }

        }

        return $this->redirectToRoute('bon_plan_details', ['id' => $bonPlan->getId()]);
    }
    /**
     * @Route("bon-plan/save/{id}", name="bon_plan_save")
     * @param BonPlan $bonPlan
     * @return Response
     */
    public function saveBonPlan(BonPlan $bonPlan){

            $user= $this->getUser();
            $user->saveBonPlan($bonPlan);
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($bonPlan);
            $manager->flush();
            return $this->redirectToRoute("home");

    }
}
