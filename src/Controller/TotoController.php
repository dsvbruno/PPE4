<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Totos;

/**
 * Description of TotoController
 *
 * @author bruno
 */
class TotoController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController {

    /**
     * @Route("/totosclassique/{id}", name = "toto_classique")
     */
    public function apiTotoMethodeClassique($id, EntityManagerInterface $em) {
        $unToto = $em->getRepository(Totos::class)->findById($id);
        $serializer = $this->get('serializer');
        $data = $serializer->serialize($unToto[0], 'json');
        $response = new Response($data);
        $response->headers->set('Content-type', 'application/json');
        $response->headers->set('Ok', 'oui');
        return $response;
    }
    
    /**
     * 
     * @Route("/totosautomatique/{id}", name="toto_auto")
     */
    
    public function apiTotoMethodeAutomatique(Totos $unToto = null) {
        if ($unToto === null) {
            $response = new Response($unToto);
            $response->headers->set('Ok', 'non');
            $response->setStatusCode(404);
            return $response;
        }
        $serializer = $this->get('serializer');
        $data = $serializer->serialize($unToto, 'json');
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Ok', 'oui');
        return $response;
    }

    /**
     * @Route("/ajouttoto", name="ajout_toto", methods="post")
     */
    public function ajoutToto(Request $request, EntityManagerInterface $em) {
       // On désérialize la donnée et on obtient un objet de type Toto
        $serializer = $this->get('serializer'); 
        $unToto = $serializer->deserialize($request->getContent(), Totos::class, 'json');

            // On ajoute le toto à la BDD 
            dump($unToto);
            $em->persist($unToto); 
            $em->flush(); 
            
            // On construit une réponse retournée 
            $response = new Response("L'ajout est réalisé!"); 
            $response->headers->set('Content-Type', 'application/text'); 
            $response->headers->set('Ok', 'oui'); 
            return $response;
        }

    }

