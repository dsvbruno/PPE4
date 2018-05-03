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
 * Description of Produit
 *
 * @author bruno
 */
class MenuController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController {
    /**
     * @Route("/menu", name = "Menu")
     * @return response 
     */
    
    public function PresentationMenu(){
        return $this->render('products/menu.html.twig');
    }
}
