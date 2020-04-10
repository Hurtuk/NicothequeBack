<?php

namespace App\DataFixtures;

use App\Entity\Movie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MovieFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $movieMemento = new Movie();
        $movieMemento->setTitle('Memento');
        $movieMemento->setTitleFr('Memento');
        $movieMemento->setYear('2000');
        $movieMemento->setCategories('Thriller;Drame');
        $movieMemento->setDirectors('Christopher Nolan');
        $movieMemento->setActors('Guy Pearce');
        $movieMemento->setOverview('Leonard Shelby ne porte que des costumes de grands couturiers et ne se
         déplace qu\'au volant de sa Jaguar . En revanche, il habite dans des motels miteux et règle ses notes avec 
         d\'épaisses liasses de billets . Leonard n\'a qu\'une idée en tête : traquer l\'homme qui a violé et assassiné 
         sa femme afin de se venger . Sa recherche du meurtrier est rendue plus difficile par le fait qu\'il souffre 
         d\'une forme rare et incurable d\'amnésie . Bien qu\'il puisse se souvenir de détails de son passé, il est 
         incapable de savoir ce qu\'il a fait dans le quart d\'heure précédent, où il se trouve, où il va et pourquoi .
          Pour ne jamais perdre son objectif de vue, il a structuré sa vie à l\'aide de fiches, de notes, de photos, de
           tatouages sur le corps . C\'est ce qui l\'aide à garder contact avec sa mission, à retenir les informations
            et à garder une trace, une notion de l\'espace et du temps. ');
        $movieMemento->setMark(9.0);
        $movieMemento->setLength(116);
        $movieMemento->setOwned(false);
        $manager->persist($movieMemento);

        $manager->flush();
    }
}
