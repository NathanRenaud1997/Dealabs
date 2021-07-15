<?php

namespace App\Controller;

use App\Entity\CodePromo;
use App\Entity\Commentaire;
use App\Entity\Liker;
use App\Events;
use App\Form\CodePromoType;
use App\Form\CommentaireType;
use App\Repository\CodePromoRepository;
use App\Repository\LikerRepository;
use App\Repository\UserRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface as EventDispatcher ;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class CodePromoController extends AbstractController
{
    /**
     * @Route("/code-promo", name="code_promo")
     */
    public function index(CodePromoRepository $repos): Response
    {
        $codePromo = $repos->findHot();
        return $this->render('code_promo/index.html.twig', [
            'code_promos' => $codePromo,
            'controller_name' => 'CodePromoController',
        ]);
    }

    /**
     * @Route("/code-promo/new", name="code_promo_new")
     * @param Request $request
     * @param CodePromoRepository $repository
     * @return Response
     */
    public function new(Request $request, CodePromoRepository $repository, EventDispatcher $eventDispatcher): Response
    {
        $codePromo = new CodePromo();
        $form = $this->createForm(CodePromoType::class, $codePromo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
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
                $codePromo->setApercu($newFilename);
            }
            $date = new DateTime("now");
            $date->format("Y-m-d");
            $codePromo->setDateCreation($date);

            $codePromo->setUserCrea($this->getUser());

            $manager->persist($codePromo);
            $manager->flush();
            $event = new GenericEvent($this->getUser(),["Manager"=>$manager]);
            $eventDispatcher->dispatch(Events::USER_POSTED, $event);
            $eventAlert = new GenericEvent($this->getUser(),["Manager"=>$manager,"codepromo"=>$codePromo]);
            $eventDispatcher->dispatch(Events::USER_ALERT_CODEPROMO, $eventAlert);
            return $this->redirectToRoute('code_promo');
        }


        return $this->render('code_promo/new.html.twig', [
            'form' => $form->createView(),
            'controller_name' => 'CodePromoController',
        ]);
    }
    /**
     * @Route("/code-promo/edit/{id}", name="edit_code_promo")
     * @param Request $request
     * @param CodePromoRepository $repository
     * @return Response
     */
    public function edit(Request $request,CodePromoRepository $repository,CodePromo $codePromo): Response
    {
     

        $form= $this->createForm(CodePromoType::class,$codePromo);
        $form->handleRequest($request);


        if($form->isSubmitted() && $form->isValid()) {
            $images = $form->get('apercu')->getData();
            if ($images) {
                $originalFilename = pathinfo($images->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $images->guessExtension();
                if($newFilename!=$codePromo->getApercu()){
                    try {
                        $images->move(
                            $this->getParameter('images_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {

                    }
                    $codePromo->setApercu($newFilename);
                }else{
                    $codePromo->setApercu($codePromo->getApercu());

                }
            }
            $manager = $this->getDoctrine()->getManager();
            $codePromo->setDateCreation($codePromo->getDateCreation());
            $codePromo->setUserCrea($this->getUser());
            $manager->persist($codePromo);
            $manager->flush();
            return $this->redirectToRoute('code_promo');
        }
        return $this->render('code_promo/edit.html.twig', [
            'form'=>$form->createView(),
            'codepromo'=>$codePromo
        ]);
    }



    /**
     * @Route("/code-promo/details/{id}", name="code_promo_details")
     * @param Request $request
     * @param CodePromo $codePromo
     * @return Response
     */
    public function details(Request $request, CodePromo $codePromo, EventDispatcher $eventDispatcher): Response
    {
        $commentaire = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $commentaire->setDatePublication(new DateTime());
            $commentaire->setCodePromo($codePromo);
            $commentaire->setUser($codePromo->getUserCrea());
            $manager->persist($commentaire);
            $manager->flush();
            $event = new GenericEvent($this->getUser(),["Manager"=>$manager]);
            $eventDispatcher->dispatch(Events::USER_POSTED, $event);
            return $this->redirectToRoute('code_promo_details', ['id' => $codePromo->getId()]);
        }

        return $this->render('code_promo/details.html.twig', [
            'codePromo' => $codePromo,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/code-promo/liker/{id}/{value}", name="code_promo_liker")
     * @param Request $request
     * @param CodePromo $codePromo
     * @param $value
     * @param LikerRepository $likerRepository
     * @return Response
     */
    public function liker(Request $request, CodePromo $codePromo, $value, LikerRepository $likerRepository,EventDispatcher $eventDispatcher): Response
    {
        $likers = $likerRepository->findAll();
        $isFinded = false;
        $like = new Liker();

        foreach ($likers as $l) {
            if ($l->getUser() === $codePromo->getUserCrea()
                && $l->getCodePromo() === $codePromo) {
                $like = $l;

                if ($value == $l->getPlusOuMoins()) {
                    $isFinded = true;
                }
            }
        }

        if (!$isFinded) {
            $like
                ->setCodePromo($codePromo)
                ->setUser($codePromo->getUserCrea())
                ->setPlusOuMoins($value);

            $manager = $this->getDoctrine()->getManager();
            $codePromo->setNote($codePromo->getNote() + $value);
            $manager->persist($like);
            $manager->persist($codePromo);
            $manager->flush();
            $event = new GenericEvent($this->getUser(),["Manager"=>$manager]);
            $eventDispatcher->dispatch(Events::USER_VOTED, $event);
        }

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("code-promo/signaler/{id}", name="code_promo_signaler")
     * @param CodePromo $codePromo
     * @return Response
     */
    public function codePromoSignaler(Request $request,
                                      MailerInterface $mailer,
                                      CodePromo $codePromo,
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
                    $mail->subject("Signalement code promo");
                    $message = "Le code promo suivant ne semble pas respecter les conditions de vente de Dealabs: " . "ID: "
                        . $codePromo->getId() . ", Titre: " . $codePromo->getTitle();
                    $mail->text($message);
                    $mailer->send($mail);
                }
            }

        }

        return $this->redirectToRoute('code_promo_details', ['id' => $codePromo->getId()]);
    }
    /**
     * @Route("code-promo/save/{id}", name="code_promo_save")
     * @param CodePromo $codePromo
     * @return Response
     */
    public function saveBonPlan(CodePromo $codePromo){

        $user= $this->getUser();
        $user->saveCodePromo($codePromo);
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($codePromo);
        $manager->flush();
        return $this->redirectToRoute("home");

    }
}
