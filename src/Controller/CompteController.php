<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Commandes;
use App\Entity\Contenu;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;


/**
 * Description of CompteController
 *
 * @author bruno
 */
class CompteController extends AbstractController {
    /**
     * @Route("/compte",name="compte")
     * @return Response
     */
    public function compteController() {
        if (!($this->getUser()))
            return $this->redirect($this->generateUrl('login'));
        return $this->render('compte/index.html.twig');
    }

    /**
     * @Route("/compte/ChangerMotDePasse/{succes}",defaults = {"succes"=null},name="changerMdp",methods="GET")
     * @return Response
     */
    public function changerMdpController(Request $request, $succes) {
        return $this->render('compte/changerMdp.html.twig', ["succes" => $succes]);
    }

    /**
     * @Route("/compte/postChangerMotDePasse",name="postChangerMdp",methods="POST")
     * @return Response
     */
    public function postChangerMdpController(Request $request,  EntityManagerInterface $em) {
        if (strlen($_POST['mdp']) != 6) {
            $succes = "L";
        } elseif ($_POST['mdp'] == $_POST['mdpVerif']) {
            $succes = "Y";
            $user = $this->getDoctrine()
                    ->getRepository(User::class)
                    ->findOneBy([
                'userName' => $this->getUser()->getUsername()
            ]);
            $user->setPassword($_POST['mdp']);
            $em->persist($user);
            $em->flush();
        } else {
            $succes = "N";
        }
        return $this->redirect($this->generateUrl('changerMdp', ["succes" => $succes]));
    }

    /**
     * @Route("/compte/commandes",name="commandes")
     * @return Response
     */
    public function commandesController() {
        if (!($this->getUser()))
            return $this->redirect($this->generateUrl('login'));
        $commandes = $this->getDoctrine()
                ->getRepository(Commandes::class)
                ->findBy([
            'userId' => $this->getUser()->getUsername()
        ]);
        $IdCommandes = [];
        foreach ($commandes as $com) {
            array_push($IdCommandes, $com->getComId());
        }
        $contenu = $this->getDoctrine()
                ->getRepository(Contenu::class)
                ->findBy([
            'idCommande' => $IdCommandes
        ]);
        return $this->render('compte/consulterCommandes.html.twig', [
                    'commandes' => $commandes,
                    'contenu' => $contenu]);
    }

}
