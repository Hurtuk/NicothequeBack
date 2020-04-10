<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $userNico = new User();
        $userNico->setName('Nico');
        $userNico->setPassword(password_hash('test', PASSWORD_DEFAULT));
        $userNico->setRoles(array('ROLE_ADMIN'));
        $manager->persist($userNico);

        $userMarion = new User();
        $userMarion->setName('Marion');
        $userMarion->setPassword(password_hash('test', PASSWORD_DEFAULT));
        $manager->persist($userMarion);

        $userCaroline = new User();
        $userCaroline->setName('Caroline');
        $userCaroline->setPassword(password_hash('test', PASSWORD_DEFAULT));
        $manager->persist($userCaroline);

        $manager->flush();
    }
}
