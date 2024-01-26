<?php

namespace App\DataFixtures;

use App\Entity\Contact;
use App\Entity\Customer;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class UserFixtures extends Fixture
{
    private Generator $faker;
    public function __Construct(private UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->faker = Factory::create('fr FR');
    }

    public function load(ObjectManager $manager): void
    {
        // User
        $user = new User();
        $user->setEmail('penelope.bourg@orange.fr');
        $user->setPassword($this->userPasswordHasher->hashPassword($user, 'Nala1234&'));

        $customer = new Customer();
        $customer->setLastName('BOURG');
        $customer->setFirstName('Pénélope');
        $customer->setPhoneNumber('0611223344');
        $customer->setAddress('Le Pré Barré');
        $customer->setZipCode('44850');
        $customer->setBirthDate(new \DateTime());
        $customer->setHorseName('Murk');
        $customer->setCity('Ligné');

        $user->setCustomer($customer);
        $user->setRoles(['ROLE_ADMIN']);

            $manager->persist($user);
            $manager->flush();
    }
}
