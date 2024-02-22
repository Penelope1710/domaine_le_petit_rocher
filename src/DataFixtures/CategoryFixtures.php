<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class CategoryFixtures extends Fixture implements OrderedFixtureInterface
{

    private Generator $faker;

    public function __Construct()
    {
        $this->faker = Factory::create('fr FR');
    }

    public function load(ObjectManager $manager): void
    {
        $category = new Category();
        $category->setName('Ballades à cheval');
        $manager->persist($category);

        $category = new Category();
        $category->setName('Festivités');
        $manager->persist($category);

        $manager->flush();
    }
    public function getOrder()
    {
        return 1;
    }
}