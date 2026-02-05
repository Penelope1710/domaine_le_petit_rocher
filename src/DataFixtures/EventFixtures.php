<?php

namespace App\DataFixtures;

use App\Entity\Category;
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
   private const REAL_EVENTS = [
        ['name' => 'Balade à cheval en forêt de Fontainebleau', 'details' => 'Sortie guidée en pleine nature.', 'category' => 'Ballades à cheval'],
        ['name' => 'Randonnée équestre au coucher du soleil', 'details' => 'Balade en fin de journée.', 'category' => 'Ballades à cheval'],
        ['name' => 'Initiation à l’équitation pour débutants', 'details' => 'Découverte du cheval.', 'category' => 'Ballades à cheval'],
        ['name' => 'Balade nocturne encadrée', 'details' => 'Sortie sous les étoiles.', 'category' => 'Ballades à cheval'],
        ['name' => 'Stage de perfectionnement équestre', 'details' => 'Pour cavaliers intermédiaires.', 'category' => 'Ballades à cheval'],
        ['name' => 'Balade en bord de rivière', 'details' => 'Parcours nature rafraîchissant.', 'category' => 'Ballades à cheval'],
        ['name' => 'Sortie équestre en montagne', 'details' => 'Parcours panoramique.', 'category' => 'Ballades à cheval'],
        ['name' => 'Balade découverte de la faune locale', 'details' => 'Observation nature.', 'category' => 'Ballades à cheval'],

        ['name' => 'Fête du centre équestre', 'details' => 'Animations pour tous.', 'category' => 'Festivités'],
        ['name' => 'Marché artisanal au haras', 'details' => 'Produits locaux.', 'category' => 'Festivités'],
        ['name' => 'Soirée barbecue entre cavaliers', 'details' => 'Moment convivial.', 'category' => 'Festivités'],
        ['name' => 'Journée portes ouvertes du haras', 'details' => 'Visite des installations.', 'category' => 'Festivités'],
        ['name' => 'Soirée guinguette au centre équestre', 'details' => 'Musique et danse.', 'category' => 'Festivités'],
        ['name' => 'Fête de la moisson au haras', 'details' => 'Animations champêtres.', 'category' => 'Festivités'],
        ['name' => 'Vide-grenier du centre équestre', 'details' => 'Brocante conviviale.', 'category' => 'Festivités'],
        ['name' => 'Repas champêtre entre cavaliers', 'details' => 'Déjeuner en plein air.', 'category' => 'Festivités'],

        ['name' => 'Balade équestre au lever du soleil', 'details' => 'Départ matinal pour profiter du calme et des paysages.', 'category' => 'Ballades à cheval'],
        ['name' => 'Randonnée équestre en forêt domaniale', 'details' => 'Parcours balisé à travers la forêt.', 'category' => 'Ballades à cheval'],
        ['name' => 'Sortie équestre découverte des chemins ruraux', 'details' => 'Itinéraire champêtre accessible à tous.', 'category' => 'Ballades à cheval'],
        ['name' => 'Balade à cheval autour du lac', 'details' => 'Parcours tranquille avec vue sur le lac.', 'category' => 'Ballades à cheval'],
        ['name' => 'Stage d’équitation pour adolescents', 'details' => 'Ateliers techniques et sorties encadrées.', 'category' => 'Ballades à cheval'],
        ['name' => 'Sortie équestre spéciale débutants', 'details' => 'Parcours court et sécurisé.', 'category' => 'Ballades à cheval'],
        ['name' => 'Balade équestre à la campagne', 'details' => 'Découverte des sentiers agricoles.', 'category' => 'Ballades à cheval'],
        ['name' => 'Randonnée équestre du dimanche', 'details' => 'Sortie conviviale hebdomadaire.', 'category' => 'Ballades à cheval'],

        ['name' => 'Soirée quiz au centre équestre', 'details' => 'Jeu en équipe et collation offerte.', 'category' => 'Festivités'],
        ['name' => 'Projection cinéma en plein air au haras', 'details' => 'Film familial sous les étoiles.', 'category' => 'Festivités'],
        ['name' => 'Soirée jeux de société au haras', 'details' => 'Jeux pour tous les âges.', 'category' => 'Festivités'],
        ['name' => 'Brunch du dimanche au centre équestre', 'details' => 'Moment convivial autour d’un buffet.', 'category' => 'Festivités'],
        ['name' => 'Fête de fin de saison du centre équestre', 'details' => 'Clôture de saison avec animations.', 'category' => 'Festivités'],
        ['name' => 'Apéritif d’accueil des nouveaux membres', 'details' => 'Rencontre entre cavaliers.', 'category' => 'Festivités'],
        ['name' => 'Soirée concert au haras', 'details' => 'Concert live en plein air.', 'category' => 'Festivités'],
        ['name' => 'Repas partagé des cavaliers', 'details' => 'Chacun apporte un plat.', 'category' => 'Festivités'],
    ];

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        // On récupère tous les users et on en prend 8
        $users = $manager->getRepository(User::class)->findAll();
        $users = array_slice($users, 0, 8);

        // On récupère les catégories créées par CategoryFixtures
        $categories = $manager->getRepository(Category::class)->findAll();

        $categoriesByName = [];
        foreach ($categories as $category) {
            $categoriesByName[$category->getName()] = $category;
        }

        // On mélange tous les events pour les répartir
        $allEvents = self::REAL_EVENTS;
        shuffle($allEvents);

        $eventIndex = 0;

        foreach ($users as $user) {
            $userEvents = array_slice($allEvents, $eventIndex, 4);
            $eventIndex += 4;

            foreach ($userEvents as $eventData) {
                $event = new Event();
                $event->setCreatedBy($user);
                $event->setName($eventData['name']);
                $event->setEventDetails($eventData['details']);

                $startDate = $this->faker->dateTimeBetween('2026-01-01', '2026-09-30');
                $event->setStartDate($startDate);

                $deadLine = clone $startDate;
                $deadLine->modify('-' . rand(2, 4) . ' days');
                $event->setDeadLine($deadLine);

                $event->setStartTime($this->faker->dateTime());
                $event->setCategory($categoriesByName[$eventData['category']]);

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
