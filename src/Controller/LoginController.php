<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;

/**
 * Description of loginController
 *
 * @author bruno
 */
class LoginController extends AbstractController {

    /**
     * @Route("/login", name="login")
     * @return Response
     */
    public function login(AuthenticationUtils $auth) {
        $erreur = $auth->getLastAuthenticationError();
        $lastUserName = $auth->getLastUsername();

        return $this->render('login/login.html.twig', array(
                    'last_username' => $lastUserName, 'error' => $erreur
        ));
    }
    
        /**
     * @Route("/Enregistrement/{res}",defaults = {"res"=null},name="enregistrement")
     */
    public function Enregistrement(Request $request, EntityManagerInterface $em, $res) {
        $succes = false;
        if ($res) {
            switch ($res) {
                case "Y": $message = "Compte crée avec succès";
                    $succes = true;
                    break;
                case "M": $message = "Les mots de passes doivent être identiques";
                    break;
                case "L": $message = "Mot de passe trop court";
                    break;
                case "E": $message = "Ce nom d'utilisateur est déjà pris";
                    break;
                case "C": $message = "Veuillez remplir tous les champs";
                    break;
            }
        } else {
            $message = null;
        }
        return $this->render('login/register.html.twig', array('message' => $message, 'succes' => $succes));
    }

    /**
     * @Route("/CreerCompte",name="creerCompte")
     */
    public function CreerCompte(Request $request, EntityManagerInterface $em) {
        if ($_POST['user'] && $_POST['mdp'] && $_POST['mdpVerif'] && $_POST['mail']) {
            $userExist = $this->getDoctrine()
                    ->getRepository(User::class)
                    ->findOneBy([
                'userName' => $_POST['user'],
            ]);
            if (!$userExist) {
                if (strlen($_POST['mdp']) != 6) {
                    $succes = "L";
                } elseif ($_POST['mdp'] == $_POST['mdpVerif']) {
                    $succes = "Y";
                    $user = new User();
                    $user->setUserName($_POST['user']);
                    $user->setEmail($_POST['mail']);
                    $user->setPassword($_POST['mdp']);
                    $user->setRole("ROLE_USER");
                    $em->persist($user);
                    $em->flush();
                } else {
                    $succes = "M";
                }
            } else {
                $succes = "E";
            }
        } else {
            $succes = "C";
        }
        return $this->redirect($this->generateUrl('enregistrement', ["res" => $succes]));
    }
}
