<?php

namespace App\Controller;

use App\Entity\City;
use App\Form\CityType;
use App\Repository\CityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CityController extends AbstractController
{
    #[Route('/city-list', name: 'app_city')]
    public function index(CityRepository $cityRepository): Response
    {
       
        $cityList = $cityRepository->findAll();
        return $this->render('city/index.html.twig', [
            'cityList' => $cityList,
        ]);
    }

    #[Route('/city/new', name: 'new_city')]
    public function newCity(Request $request, EntityManagerInterface $entityManager): Response
    {

        $city = new City();
        $form = $this->createForm(CityType::class, $city);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

                $entityManager->persist($city);
                $entityManager->flush();

                return $this->redirectToRoute("app_city");
        }
             
        return $this->render('city/city_new.html.twig', [
         'form' => $form->createView()
        ]);
    }

    //create route to update a city
    #[Route('/city/edit/{id}', name: 'edit_city')]
    public function editCity(City $city, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CityType::class, $city);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

                $entityManager->persist($city);
                $entityManager->flush();

                return $this->redirectToRoute("app_city");
        }
             
        return $this->render('city/city_new.html.twig', [
         'form' => $form->createView()
        ]);
    }

    //create route to delete a city
    #[Route('/city/delete/{id}', name: 'delete_city')]
    public function deleteCity(City $city, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($city);
        $entityManager->flush();

        return $this->redirectToRoute("app_city");
    }


    #[Route('/city/{cityName}', name: 'city_detail')]
    public function cityDetail($cityName, CityRepository $cityRepository): Response
    {

        
        $city = $cityRepository->findOneBy([
            'name'=> $cityName
        ]);

        if ( !$city){
            throw $this->createNotFoundException();
        }

        return $this->render("city/city_detail.html.twig", [
            "city" => $city,
        ]);
    }
}
