<?php

namespace App\Controller;

use App\Entity\Country;
use App\Form\CountryType;
use App\Repository\CountryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class CountryController extends AbstractController
{
    #[Route('/countries', name: 'countries')]
    public function countries(CountryRepository $countryRepository): Response
    {
        $countries = $countryRepository->findAll();

        return $this->render('country/index.html.twig', [
            'countries' => $countries,
        ]);
    }

    #[Route('/country/new', name: 'new_country')]
    public function newCountry(Request $request, EntityManagerInterface $entityManager): Response
    {
        $country = new Country();
        $form = $this->createForm(CountryType::class, $country);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $country->setCreatedAt(new \DateTimeImmutable('Europe/Paris'));
            $entityManager->persist($country);
            $entityManager->flush();

            return $this->redirectToRoute('countries');
        }

        return $this->render('country/new_country.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/country/update/{id}', name: 'update_country')]
    public function updateCountry(Country $country, Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(CountryType::class, $country);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $flagFile */
            $flagFile = $form->get('flag')->getData();
            if ($flagFile) {
                $originalFilename = pathinfo($flagFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$flagFile->guessExtension();

                try {
                    $flagFile->move(
                        $this->getParameter('flags_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }
                $country->setFlag($newFilename);
            }
            $country->setUpdatedAt(new \DateTimeImmutable('Europe/Paris'));
            $entityManager->persist($country);
            $entityManager->flush();

            return $this->redirectToRoute('country', array('countryName' => $country->getName()));
        }

        return $this->render('country/new_country.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/country/delete/{id}', name: 'delete_country')]
    public function deleteCountry(Country $country, Request $request, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($country);
        $entityManager->flush();

        return $this->redirectToRoute('countries');
    }

    #[Route('/country/{countryName}', name: 'country')]
    public function country(CountryRepository $countryRepository, string $countryName): Response
    {
        $country = $countryRepository->findOneBy(array('name' => $countryName));

        if (!$country) {
            throw $this->createNotFoundException();
        }

        return $this->render('country/detail.html.twig', [
            'country' => $country,
        ]);
    }
}
