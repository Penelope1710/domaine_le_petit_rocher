<?php

namespace App\DataFixtures;

use App\Entity\Contact;
use App\Entity\Event;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;


class EventFixtures extends Fixture
{
    private Generator $faker;
    public function __Construct()
    {
        $this->faker = Factory::create('fr FR');
    }

    public function load(ObjectManager $manager): void
    {
        // Event
        for ($i = 0; $i < 10; $i++) {
            $event = new Event();
            $event->setName = $this->faker->words(3, 5);
            $event->setStartDate = $this->faker->dateTimeBetween('2024-03-20', '2024-06-20');
            $event->setDeadLine = $this->faker->dateTimeBetween($event->getStartDate(), '-2 days');
            $event->setStartTime = $this->faker->time();
            $event->setEventDetails = $this->faker->text(50);


            $manager->persist($event);
        }

        $manager->flush();
    }
}
