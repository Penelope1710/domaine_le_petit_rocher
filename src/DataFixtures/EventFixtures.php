<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Customer;
use App\Entity\Event;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;


class EventFixtures extends Fixture implements OrderedFixtureInterface
{
    private Generator $faker;
    public function __Construct()
    {
        $this->faker = Factory::create('fr FR');
    }

    public function load(ObjectManager $manager): void
    {
        $categories = $manager->getRepository(Category::class)->findAll();


        // Event
        $users = $manager->getRepository(User::class)->findAll();
        foreach ($users as $user) {

            for ($i = 0; $i < 4; $i++) {
                $event = new Event();
                $event->setCreatedBy($user);
                $event->setName($this->faker->words(3, 5));
                $event->setStartDate($this->faker->dateTimeBetween('2024-03-20', '2024-06-20'));
                $event->setDeadLine($this->faker->dateTimeBetween('-2 days', $event->getStartDate()));
                $event->setStartTime($this->faker->dateTime());
                $event->setEventDetails($this->faker->text(50));
                $manager->persist($event);
            }
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }
}
