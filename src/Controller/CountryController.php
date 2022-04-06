<?php

namespace App\Controller;

use App\Entity\Country;
use App\Form\CountryType;
use App\Repository\CountryRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CountryController extends AbstractController
{
    #[Route('/country-list', name: 'app_country')]
    public function index(CountryRepository $countryRepository): Response
    {
        $countryList = $countryRepository->findAll();
        return $this->render('country/index.html.twig', [
            'countryList' => $countryList,
        ]);
    }

    #[Route('/country/new', name: 'new_country')]
    public function newCountry(Request $request, EntityManagerInterface $entityManager): Response
    {

        $country = new Country();
        $form = $this->createForm(CountryType::class, $country);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

                $entityManager->persist($country);
                $entityManager->flush();

                return $this->redirectToRoute("app_country");
        }
             
        return $this->render('country/country_new.html.twig', [
         'form' => $form->createView()
        ]);
    }


    #[Route('/country/edit/{id}', name: 'edit_country')]
    public function editCountry(Country $country, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CountryType::class, $country);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

                $entityManager->persist($country);
                $entityManager->flush();

                return $this->redirectToRoute("app_country");
        }
             
        return $this->render('country/country_new.html.twig', [
         'form' => $form->createView()
        ]);
    }

    #[Route('/country/delete/{id}', name: 'delete_country')]
    public function deleteCity(Country $country, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($country);
        $entityManager->flush();

        return $this->redirectToRoute("app_country");
    }


    #[Route('/country/{countryName}', name: 'country_detail')]
    public function countryDetail($countryName, CountryRepository $countryRepository): Response
    {

        
        $country = $countryRepository->findOneBy([
            'name'=> $countryName
        ]);

        if ( !$country){
            throw $this->createNotFoundException();
        }

        return $this->render("country/country_detail.html.twig", [
            "country" => $country,
        ]);
    }

    
}
