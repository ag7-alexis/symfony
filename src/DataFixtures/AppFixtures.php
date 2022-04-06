<?php

namespace App\DataFixtures;

use App\Entity\City;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $city = new City();
        $city->setName("Lyon");
        $city->setDescription("Lyon is the capital of France. It is the largest city in the department of Auvergne-Rhône-Alpes.");
        $city->setSite("https://www.lyon.fr/");
        $manager->persist($city);
 
        $city = new City();
        $city->setName("Grenoble");
        $city->setDescription("Grenoble is the capital of France. It is the largest city in the department of Auvergne-Rhône-Alpes.");
        $city->setSite("https://www.grenoble.fr/");
        $manager->persist($city);

        $city = new City();
        $city->setName("Paris");
        $city->setDescription("Paris is the capital of France. It is the largest city in the department of Auvergne-Rhône-Alpes.");
        $city->setSite("https://www.paris.fr/");
        $manager->persist($city);

        $manager->flush();

        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
