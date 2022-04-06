<?php

namespace App\Controller;

use App\Entity\City;
use App\Form\CityType;
use App\Repository\CityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CityController extends AbstractController
{
    #[Route('/cities', name: 'cities')]
    public function cities(CityRepository $cityRepository): Response
    {
        $cities = $cityRepository->findAll();

        return $this->render('city/index.html.twig', [
            'cities' => $cities,
        ]);
    }

    #[Route('/city/new', name: 'new_city')]
    public function newCity(Request $request, EntityManagerInterface $entityManager): Response
    {
        $city = new City();
        $form = $this->createForm(CityType::class, $city);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($city);
            $entityManager->flush();

            return $this->redirectToRoute('cities');
        }

        return $this->render('city/new_city.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/city/update/{id}', name: 'update_city')]
    public function updateCity(City $city, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CityType::class, $city);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($city);
            $entityManager->flush();

            return $this->redirectToRoute('city', array('cityName' => $city->getName()));
        }

        return $this->render('city/new_city.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/city/delete/{id}', name: 'delete_city')]
    public function deleteCity(City $city, Request $request, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($city);
        $entityManager->flush();

        return $this->redirectToRoute('cities');
    }

    #[Route('/city/{cityName}', name: 'city')]
    public function city(CityRepository $cityRepository, string $cityName): Response
    {
        $city = $cityRepository->findOneBy(array('name' => $cityName));

        if (!$city) {
            throw $this->createNotFoundException();
        }

        return $this->render('city/detail.html.twig', [
            'city' => $city,
        ]);
    }
}
