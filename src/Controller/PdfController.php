<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller;

use Dompdf\Dompdf;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Commandes;
use App\Entity\Contenu;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Description of PdfController
 *
 * @author bruno
 */
class PdfController extends AbstractController {

    /**
     * @route("/facturePdf/{idCommande}",name="getFacturePdf")
     *
     * @return Response
     */
    public function getFacturePdf($idCommande) {

        if (!($this->getUser()))
            return $this->redirect($this->generateUrl('login'));
        $contenu = $this->getDoctrine()
                ->getRepository(Contenu::class)
                ->findBy([
            'idCommande' => $idCommande
        ]);

        $dateActu = date('d-m-Y');

        $total = 0;
        $contenuHtml = "<html>
                            <head>
                                <style>
                                    table, th, td {
                                        border: 1px solid black;
                                    }
                                </style>
                            </head>"
                . "<h1 style='text-align: center;font-size: 150%;'><b>Bookstore</b></h1> <br/>"
                . "<p>Facture du " . $dateActu . "</p>"
                . "<br/>";

        $contenuHtml = $contenuHtml . "<p>Commande n°" . $idCommande . "</p>";
        $contenuHtml = $contenuHtml . "<table>
                    <thead>
                        <tr>
                            <th style ='width: 300px;'>Produit</th>
                            <th style ='width: 100px;'>Prix</th>
                            <th style ='width: 100px;'>Quantité</th>
                            <th style ='width: 100px;'>Total</th>                  
                        </tr>
                    </thead> ";
        foreach ($contenu as $cont) {
            $total = $total + ($cont->getContenuPrix() * $cont->getQuantite() );
            $contenuHtml = $contenuHtml .
                    " <tbody> 
                                <tr> 
                                    <td style ='width: 300px;'>
                                    " . $cont->getIdProduit()->getProNom() . "
                                    </td>
                                    <td style ='width: 100px;'>
                                    " . $cont->getContenuPrix() . "
                                    </td>
                                    <td style ='width: 100px;'>
                                    " . $cont->getQuantite() . "
                                    </td>
                                    <td style ='width: 100px;'>
                                    " . $cont->getContenuPrix() * $cont->getQuantite() . "
                                    </td>
                                </tr>
                           </tbody>";

//                    $contenuHtml = $contenuHtml. "<p>Produit : " . $cont->getIdProduit()->getProNom()
//                            . " Prix unitaire : " . $cont->getContenuPrix()
//                            . " Quantité : " . $cont->getQuantite()
//                            . " Total : " . $cont->getContenuPrix() * $cont->getQuantite() . "</p>"
//                            . "<hr>";
//                
        }
        $contenuHtml = $contenuHtml . "<br/> <p style='text-align: right;' >Total : " . $total . "</p>";
        $contenuHtml = $contenuHtml . "</html>";
        // On  crée une instance de Dompdf
        $dompdf = new Dompdf();
        //  On  ajoute le texte à afficher
        $dompdf->loadHtml($contenuHtml);
        // On fait générer le pdf  à Dompdf ...
        $dompdf->render();
        //  et on l'affiche dans un   objet Response
        return new Response($dompdf->stream("Mes Commandes " . $dateActu));
    }

}
