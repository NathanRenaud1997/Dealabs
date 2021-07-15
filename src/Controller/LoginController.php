<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{
    /**
     * @Route ("/login", name="secu_login")
     */
    public function login(){
        return $this->render('secu/login.html.twig');
    }
    /**
     * @Route ("/deconnexion", name="secu_logout")
     */
    public function logout(){

    }
/**
 * @Route("/api/login", name="api_login", methods={"POST"})
 */
public function apiLogin(){
    $user= $this->getUser();
    return $this->json([
        'username'=>$user->getUsername(),
        'roles'=>$user->getRoles()
    ]);
}
}
