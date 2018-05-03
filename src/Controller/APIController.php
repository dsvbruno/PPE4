<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller;

use App\Entity\Produits;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * Description of APiController
 *
 * @author bruno
 */
class APIController extends AbstractController {
    /**
     * 
     * @Route ("/apiGet/produit/{id}",name="getProduitById")
     */
    public function apiGetProduitUserMethodeClassique($id, EntityManagerInterface $em) {
        $unProduit = $em->getRepository(Produits::class)->findOneByProId($id);

        $serializer = $this->get('serializer');
        $data = $serializer->serialize($unProduit, 'json');
        $response = new Response($data);
        $response->headers->set('content-type', 'application/json');
        $response->headers->set('Ok', 'oui');
        return $response;
    }
    
    /**
     * 
     * @Route ("/apiGet/produits/all",name="getProduits")
     */
    public function apiGetAllProduitUserMethodeClassique(EntityManagerInterface $em) {
        $produits = $em->getRepository(Produits::class)->findAll();
        $serializer = $this->get('serializer');
        $data = $serializer->serialize($produits, 'json');
        $response = new Response($data);
        $response->headers->set('content-type', 'application/json');
        $response->headers->set('Ok', 'oui');
        return $response;
    }

    /**
     * 
     * @Route ("/apiGetAuto/produit/{id}",name="getProduitByIdAuto")
     */
    public function apiUserMethodeAuto(Produits $unProduit, EntityManagerInterface $em) {
        $serializer = $this->get('serializer');
        $data = $serializer->serialize($unProduit, 'json');
        $response = new Response($data);
        $response->headers->set('content-type', 'application/json');
        $response->headers->set('Ok', 'oui');
        return $response;
    }

    /**
     * 
     * @Route ("/setproduit",name="api_set_produit",methods="post")
     */
    public function setProduit(Request $request, EntityManagerInterface $em) {

        $serializer = $this->get('serializer');
        $unProduit = $serializer->deserialize($request->getContent(), Produits::class, 'json');

        $em->persist($unProduit);
        $em->flush();

        $response = new Response("L'ajout est réalisé");
        $response->headers->set('Content-type', 'application/text');
        $response->headers->set('Ok', 'oui');
//        $lesDonnees = json_decode($request->getContent());
//        dump($lesDonnees);
        return $response;
    }

}
