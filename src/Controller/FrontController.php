<?php

namespace App\Controller;

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractController {

        
    #[Route("/", name: "homepage")]
    public function homepage(){

        $cityList = ["Lyon","Paris","Grenoble"];

        return $this->render("homepage.html.twig", [
            'cityList' => $cityList 
        ]);
    }

}