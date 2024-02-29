<?php

namespace App\DataFixtures;

use App\Entity\Contact;
use App\Entity\Customer;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use phpDocumentor\Reflection\Types\Array_;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class UserCustomerFixtures extends Fixture implements OrderedFixtureInterface
{
    private Generator $faker;
    public function __Construct(private UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->faker = Factory::create('fr_FR');
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

        for ($i = 0; $i < 9; $i++) {
            $user = new User;
            $user->setEmail($this->faker->email);
            $user->setPassword($this->userPasswordHasher->hashPassword($user, '0000'));

            $customer = new Customer();
            $customer->setLastName($this->faker->lastName);
            $customer->setFirstName($this->faker->firstName);
            $customer->setPhoneNumber($this->faker->phoneNumber);
            $customer->setAddress($this->faker->streetAddress);
            $customer->setZipCode($this->faker->postcode);
            $customer->setBirthDate(new \DateTime());
            $customer->setCity($this->faker->city);

            $user->setCustomer($customer);
            $roles = ['ROLE_GITE', 'ROLE_ECURIE'];
            //mélange les éléments d'un tableau
            shuffle($roles);
            $user->setRoles([$roles[0]]);

            //est ce que $userRoles contient le rôle ecurie
            $userRoles = $user->getRoles();
            if(in_array('ROLE_ECURIE', $userRoles)) {
                $customer->setHorseName($this->faker->word);
            }

            $manager->persist($user);
        }
            $manager->flush();
    }

    public function getOrder()
    {
        return 0;
    }
}
