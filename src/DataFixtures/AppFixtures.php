<?php

namespace App\DataFixtures;

use Faker\Factory;
use Faker\Generator;
use App\Entity\Article;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{

    /**
     * @var Generator
     */
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        // TODO
        // // Création d'une boucle qui va créer 50 articles aléatoires
        // // Chaque article aura un titre, un contenu, une date de publication qui seront générés aléatoirement
        // for ($i = 1; $i <= 50; $i++) {
        //     $article = new Article();
        //     $article->setTitre($this->faker->sentence(4))
        //         ->setContenu($this->faker->paragraph)
        //         ->setDatePublication($this->faker->dateTimeBetween('-6 months'));
        //     $manager->persist($article);
        // }
        // $manager->flush();
    }
}
