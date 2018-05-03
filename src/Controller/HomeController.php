<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * Description of AccueilController
 *
 * @author bruno
 */
class HomeController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController {

    /**
     * @route("/",name="redirectHome")
     * @return Response
     */
    public function redirectToAcceuil() {
        return $this->redirect($this->generateUrl('home'));
    }

    /**
     * @route("/Accueil/{categorie}",defaults = {"categorie"=null},name="home")
     * @return Response
     */
    public function indexController($categorie) {
        if ($categorie) {

            $catId = $this->getDoctrine()
                    ->getRepository(\App\Entity\Categorie::class)
                    ->findOneByCatLibelle($categorie);
            if ($catId) {
                $produits = $this->getDoctrine()
                        ->getRepository(\App\Entity\Produits::class)
                        ->findByCatId($catId->getCatId());
                $page = $catId;
            } else {
                $page = 'Catégorie "' . $categorie . '" innexistante';
                $produits = null;
            }
        } else {
            $produits = $this->getDoctrine()
                    ->getRepository(\App\Entity\Produits::class)
                    ->findAll();
            $page = "Accueil";
        }
        $categories = $this->getDoctrine()
                ->getRepository(\App\Entity\Categorie::class)
                ->findAll();


        return $this->render('home/index.html.twig', [
                    'produits' => $produits,
                    'categories' => $categories,
                    'page' => $page]);
    }

}
